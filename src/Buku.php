<?php

declare(strict_types=1);

require_once __DIR__ . '/ItemPerpustakaan.php';

class Buku extends ItemPerpustakaan
{
    private string $penulis;

    public function __construct(string $kode, string $judul, string $penulis)
    {
        parent::__construct($kode, $judul);
        $this->penulis = $penulis;
    }

    public function getPenulis(): string
    {
        return $this->penulis;
    }

    public function getJenis(): string
    {
        return 'Buku';
    }

    public function getBatasPinjamHari(): int
    {
        return 7;
    }

    // Denda buku: Rp1.000 per hari
    public function hitungDenda(int $hariTerlambat): int
    {
        return $hariTerlambat * 1000;
    }

    // Overriding: info buku ditambah nama penulis
    public function getInfo(): string
    {
        return parent::getInfo() . " (penulis: {$this->penulis})";
    }
}
