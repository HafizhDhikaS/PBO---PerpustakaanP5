<?php

declare(strict_types=1);

require_once __DIR__ . '/ItemPerpustakaan.php';
require_once __DIR__ . '/Anggota.php';
require_once __DIR__ . '/Peminjaman.php';

/**
 * Pengelola utama. Menyimpan koleksi, anggota, dan semua catatan peminjaman.
 * Objek Peminjaman dibuat di dalam class ini (composition).
 */
class Perpustakaan
{
    private string $nama;
    /** @var ItemPerpustakaan[] */
    private array $koleksi = [];
    /** @var Anggota[] */
    private array $anggota = [];
    /** @var Peminjaman[] */
    private array $daftarPeminjaman = [];

    public function __construct(string $nama)
    {
        $this->nama = $nama;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function tambahItem(ItemPerpustakaan $item): void
    {
        $this->koleksi[$item->getKode()] = $item;
    }

    public function daftarkanAnggota(Anggota $anggota): void
    {
        $this->anggota[$anggota->getId()] = $anggota;
    }

    /** @return ItemPerpustakaan[] */
    public function getKoleksi(): array
    {
        return $this->koleksi;
    }

    public function pinjam(string $idAnggota, string $kodeItem, string $tanggal): string
    {
        if (!isset($this->anggota[$idAnggota])) {
            return "GAGAL: anggota $idAnggota tidak terdaftar.";
        }
        if (!isset($this->koleksi[$kodeItem])) {
            return "GAGAL: item $kodeItem tidak ditemukan.";
        }

        $item = $this->koleksi[$kodeItem];
        if (!$item->isTersedia()) {
            return "GAGAL: \"{$item->getJudul()}\" sedang dipinjam.";
        }

        $anggota = $this->anggota[$idAnggota];
        $peminjaman = new Peminjaman($anggota, $item, $tanggal);
        $this->daftarPeminjaman[] = $peminjaman;
        $item->tandaiDipinjam();

        $tempo = $peminjaman->getJatuhTempo()->format('Y-m-d');
        return "OK: {$anggota->getNama()} meminjam \"{$item->getJudul()}\" pada $tanggal. Jatuh tempo $tempo.";
    }

    public function kembalikan(string $idAnggota, string $kodeItem, string $tanggal): string
    {
        foreach ($this->daftarPeminjaman as $peminjaman) {
            $cocok = $peminjaman->isAktif()
                && $peminjaman->getAnggota()->getId() === $idAnggota
                && $peminjaman->getItem()->getKode() === $kodeItem;

            if ($cocok) {
                $hariTerlambat = $peminjaman->selesai($tanggal);
                $peminjaman->getItem()->tandaiKembali();

                $nama = $peminjaman->getAnggota()->getNama();
                $judul = $peminjaman->getItem()->getJudul();
                $denda = number_format($peminjaman->getDenda(), 0, ',', '.');

                if ($hariTerlambat === 0) {
                    return "OK: $nama mengembalikan \"$judul\" tepat waktu. Denda Rp0.";
                }
                return "OK: $nama mengembalikan \"$judul\" terlambat $hariTerlambat hari. Denda Rp$denda.";
            }
        }
        return "GAGAL: tidak ada peminjaman aktif untuk $idAnggota dengan item $kodeItem.";
    }

    public function totalDenda(): int
    {
        $total = 0;
        foreach ($this->daftarPeminjaman as $peminjaman) {
            $total += $peminjaman->getDenda();
        }
        return $total;
    }
}
