<?php

declare(strict_types=1);

require_once __DIR__ . '/Anggota.php';
require_once __DIR__ . '/ItemPerpustakaan.php';

/**
 * Catatan satu kali peminjaman: siapa yang pinjam, apa yang dipinjam, dan kapan.
 */
class Peminjaman
{
    private Anggota $anggota;
    private ItemPerpustakaan $item;
    private DateTimeImmutable $tanggalPinjam;
    private ?DateTimeImmutable $tanggalKembali = null;
    private int $denda = 0;

    public function __construct(Anggota $anggota, ItemPerpustakaan $item, string $tanggalPinjam)
    {
        $this->anggota = $anggota;
        $this->item = $item;
        $this->tanggalPinjam = new DateTimeImmutable($tanggalPinjam);
    }

    public function getAnggota(): Anggota
    {
        return $this->anggota;
    }

    public function getItem(): ItemPerpustakaan
    {
        return $this->item;
    }

    public function isAktif(): bool
    {
        return $this->tanggalKembali === null;
    }

    public function getDenda(): int
    {
        return $this->denda;
    }

    public function getJatuhTempo(): DateTimeImmutable
    {
        $batas = $this->item->getBatasPinjamHari();
        return $this->tanggalPinjam->modify("+{$batas} days");
    }

    // Menutup peminjaman dan menghitung denda lewat objek item (polymorphism).
    public function selesai(string $tanggalKembali): int
    {
        $this->tanggalKembali = new DateTimeImmutable($tanggalKembali);

        $hariTerlambat = 0;
        if ($this->tanggalKembali > $this->getJatuhTempo()) {
            $hariTerlambat = (int) $this->getJatuhTempo()->diff($this->tanggalKembali)->days;
        }

        $this->denda = $this->item->hitungDenda($hariTerlambat);
        return $hariTerlambat;
    }
}
