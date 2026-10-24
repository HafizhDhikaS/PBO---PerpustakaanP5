<?php

declare(strict_types=1);

require_once __DIR__ . '/ItemPerpustakaan.php';

class Majalah extends ItemPerpustakaan
{
    private string $edisi;

    public function __construct(string $kode, string $judul, string $edisi)
    {
        parent::__construct($kode, $judul);
        $this->edisi = $edisi;
    }

    public function getEdisi(): string
    {
        return $this->edisi;
    }

    public function getJenis(): string
    {
        return 'Majalah';
    }

    public function getBatasPinjamHari(): int
    {
        return 3;
    }

    // Denda majalah: Rp500 per hari
    public function hitungDenda(int $hariTerlambat): int
    {
        return $hariTerlambat * 500;
    }

    // Overriding: info majalah ditambah edisi
    public function getInfo(): string
    {
        return parent::getInfo() . " (edisi: {$this->edisi})";
    }
}
