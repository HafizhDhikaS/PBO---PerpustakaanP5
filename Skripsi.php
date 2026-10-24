<?php

declare(strict_types=1);

require_once __DIR__ . '/ItemPerpustakaan.php';

class Skripsi extends ItemPerpustakaan
{
    private string $penulis;
    private int $tahun;

    public function __construct(string $kode, string $judul, string $penulis, int $tahun)
    {
        parent::__construct($kode, $judul);
        $this->penulis = $penulis;
        $this->tahun = $tahun;
    }

    public function getJenis(): string
    {
        return 'Skripsi';
    }

    public function getBatasPinjamHari(): int
    {
        return 2;
    }

    // Denda skripsi paling mahal karena hanya ada satu salinan: Rp5.000 per hari
    public function hitungDenda(int $hariTerlambat): int
    {
        return $hariTerlambat * 5000;
    }

    // Overriding: info skripsi ditambah penulis dan tahun
    public function getInfo(): string
    {
        return parent::getInfo() . " (penulis: {$this->penulis}, tahun: {$this->tahun})";
    }
}
