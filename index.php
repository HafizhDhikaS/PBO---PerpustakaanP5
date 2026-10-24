<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    header('Content-Type: text/plain; charset=utf-8');
}

require_once __DIR__ . '/src/Buku.php';
require_once __DIR__ . '/src/Majalah.php';
require_once __DIR__ . '/src/Skripsi.php';
require_once __DIR__ . '/src/Anggota.php';
require_once __DIR__ . '/src/Perpustakaan.php';

function judul(string $teks): void
{
    echo PHP_EOL . "=== $teks ===" . PHP_EOL;
}

$perpus = new Perpustakaan('Perpustakaan Kampus UCIC');

// Siapkan data
$perpus->tambahItem(new Buku('B001', 'Pemrograman Berorientasi Objek', 'Budi Santoso'));
$perpus->tambahItem(new Majalah('M001', 'Info Komputer', 'Oktober 2026'));
$perpus->tambahItem(new Skripsi('S001', 'Sistem Informasi Absensi', 'Rina Wijaya', 2024));

$perpus->daftarkanAnggota(new Anggota('A01', 'Dika'));
$perpus->daftarkanAnggota(new Anggota('A02', 'Sari'));

// Skenario 1: Overriding (getInfo versi tiap subclass beda)
judul('Skenario 1: Daftar koleksi (method overriding getInfo)');
foreach ($perpus->getKoleksi() as $item) {
    echo $item->getInfo() . PHP_EOL;
}

// Skenario 2: Polymorphism (satu pemanggilan hitungDenda, hasil beda per objek)
judul('Skenario 2: Denda terlambat 4 hari (polymorphism hitungDenda)');
foreach ($perpus->getKoleksi() as $item) {
    $denda = number_format($item->hitungDenda(4), 0, ',', '.');
    echo str_pad($item->getJenis(), 8) . ": batas {$item->getBatasPinjamHari()} hari, denda Rp$denda" . PHP_EOL;
}

// Skenario 3: Peminjaman
judul('Skenario 3: Peminjaman');
echo $perpus->pinjam('A01', 'B001', '2026-10-01') . PHP_EOL;
echo $perpus->pinjam('A02', 'M001', '2026-10-01') . PHP_EOL;
echo $perpus->pinjam('A01', 'S001', '2026-10-01') . PHP_EOL;

// Skenario 4: Validasi
judul('Skenario 4: Validasi gagal');
echo $perpus->pinjam('A02', 'B001', '2026-10-02') . PHP_EOL;
echo $perpus->pinjam('A99', 'B001', '2026-10-02') . PHP_EOL;
echo $perpus->pinjam('A02', 'X999', '2026-10-02') . PHP_EOL;

// Skenario 5: Pengembalian (tepat waktu dan terlambat)
judul('Skenario 5: Pengembalian');
echo $perpus->kembalikan('A01', 'B001', '2026-10-06') . PHP_EOL; // batas 7 hari, tepat waktu
echo $perpus->kembalikan('A02', 'M001', '2026-10-07') . PHP_EOL; // batas 3 hari, telat 3 hari
echo $perpus->kembalikan('A01', 'S001', '2026-10-06') . PHP_EOL; // batas 2 hari, telat 3 hari
echo $perpus->kembalikan('A01', 'B001', '2026-10-06') . PHP_EOL; // sudah dikembalikan

judul('Total denda');
echo 'Rp' . number_format($perpus->totalDenda(), 0, ',', '.') . PHP_EOL;
