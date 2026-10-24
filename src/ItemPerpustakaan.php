<?php

declare(strict_types=1);

/**
 * Class induk (abstract) untuk semua koleksi perpustakaan.
 * Tidak bisa dibuat objeknya langsung, hanya lewat subclass (Buku, Majalah, Skripsi).
 */
abstract class ItemPerpustakaan
{
    private string $kode;
    private string $judul;
    private bool $tersedia = true;

    public function __construct(string $kode, string $judul)
    {
        $this->kode = $kode;
        $this->judul = $judul;
    }

    public function getKode(): string
    {
        return $this->kode;
    }

    public function getJudul(): string
    {
        return $this->judul;
    }

    public function isTersedia(): bool
    {
        return $this->tersedia;
    }

    // Status hanya diubah lewat dua method ini, tidak bisa diubah langsung dari luar.
    public function tandaiDipinjam(): void
    {
        $this->tersedia = false;
    }

    public function tandaiKembali(): void
    {
        $this->tersedia = true;
    }

    // Method abstract: setiap subclass WAJIB mengisinya dengan aturan masing-masing.
    abstract public function getJenis(): string;

    abstract public function getBatasPinjamHari(): int;

    abstract public function hitungDenda(int $hariTerlambat): int;

    // Method biasa yang nanti di-override oleh subclass tertentu.
    public function getInfo(): string
    {
        return "[{$this->getJenis()}] {$this->kode} - {$this->judul}";
    }
}
