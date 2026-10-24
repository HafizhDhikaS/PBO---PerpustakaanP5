# Sistem Perpustakaan Sederhana (PHP OOP)

## Masalah

Perpustakaan punya beberapa jenis koleksi (buku, majalah, skripsi) dengan aturan yang berbeda. Lama pinjam dan dendanya tidak sama. Kalau semua aturan itu ditulis dengan banyak if, kodenya cepat berantakan. Aplikasi ini mencatat peminjaman dan pengembalian, lalu menghitung denda sesuai jenis koleksinya.

## Desain

| Class | Fungsi |
|---|---|
| ItemPerpustakaan (abstract) | Induk semua koleksi: kode, judul, status tersedia |
| Buku | Pinjam 7 hari, denda Rp1.000/hari |
| Majalah | Pinjam 3 hari, denda Rp500/hari |
| Skripsi | Pinjam 2 hari, denda Rp5.000/hari |
| Anggota | Data anggota (id, nama) |
| Peminjaman | Catatan satu transaksi pinjam |
| Perpustakaan | Pengelola koleksi, anggota, pinjam, dan kembali |

Hubungan antarkelas:
- Buku, Majalah, dan Skripsi mewarisi ItemPerpustakaan.
- Peminjaman memakai objek Anggota dan ItemPerpustakaan.
- Perpustakaan membuat dan menyimpan objek Peminjaman.

## Alasan pilihan relasi

- **Inheritance (Buku, Majalah, Skripsi extends ItemPerpustakaan):** ketiganya sama-sama koleksi perpustakaan, jadi data dan perilaku umum cukup ditulis sekali di induk.
- **Abstract class:** ItemPerpustakaan tidak masuk akal kalau dibuat langsung, karena tidak ada koleksi yang cuma "item". Method abstract memaksa tiap subclass mengisi aturannya sendiri (jenis, batas pinjam, denda).
- **Overriding dan polymorphism:** getInfo() ditimpa tiap subclass, dan hitungDenda() dipanggil lewat tipe ItemPerpustakaan. Hasilnya menyesuaikan objek aslinya, jadi tidak perlu if untuk mengecek jenis koleksi. Kalau nanti ada koleksi baru (misalnya DVD), cukup buat subclass baru.
- **Asosiasi (Peminjaman ke Anggota dan item):** anggota dan koleksi tetap ada walaupun catatan peminjamannya sudah selesai, jadi Peminjaman hanya memakai mereka.
- **Composition (Perpustakaan dan Peminjaman):** catatan peminjaman tidak berarti di luar perpustakaan, jadi objeknya dibuat dan disimpan langsung oleh Perpustakaan.
- **Tidak memakai interface:** abstract class sudah cukup untuk kebutuhan ini.

