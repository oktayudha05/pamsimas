# 📋 Issue: Website Pencatatan Air Dusun

## Latar Belakang
Saat ini pencatatan penggunaan air di dusun masih dilakukan **secara manual pakai kertas**. Tiap bulan, petugas keliling ke rumah warga satu per satu, baca angka di meteran air, lalu catat di kertas.

## Tujuan
Bikin website sistem informasi berbasis web supaya:
1. Petugas bisa **input angka meteran** langsung lewat HP/laptop.
2. Data warga terorganisir dengan detail **RT/RW**.
3. Sistem otomatis menghitung **pemakaian (selisih)** per bulan.
4. Pengelola bisa **melihat rekap** histori pencatatan secara digital.

## Tech Stack (Modern & Gratis)
| Komponen | Teknologi | Alasan |
|----------|-----------|--------|
| **Backend** | **Laravel** | Framework PHP terkuat, fitur lengkap. |
| **Database** | **MySQL** | Relasional, stabil untuk data transaksi. |
| **Frontend** | **Blade Template**| Native Laravel, cepat & simpel. |
| **Styling** | **Tailwind CSS** | Utility-first, UI modern dengan cepat. |
| **Hosting** | **InfinityFree** | Shared hosting gratis selamanya. |

---

## Struktur Database (Laravel Migration)

### 1. Tabel `users`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BigInt | Primary Key |
| nama | String | Nama petugas/pengelola |
| username | String | Untuk login |
| role | Enum | `pengelola` atau `petugas` |
| password | String | Hash bcrypt |

### 2. Tabel `wargas`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BigInt | Primary Key |
| nama | String | Nama kepala keluarga |
| rt | Integer | Nomor RT |
| rw | Integer | Nomor RW |
| nomor_meteran| String | ID meteran fisik |

### 3. Tabel `pencatatans`
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BigInt | Primary Key |
| warga_id | ForeignId| Relasi ke `wargas` |
| user_id | ForeignId| Relasi ke `users` (petugas) |
| bulan | String | Format `YYYY-MM` |
| angka_meteran| Integer | Angka meteran saat ini |
| pemakaian | Integer | Hasil hitung (saat ini - lalu) |

---

## Color Palette
primary : #36656B
secondary : #75B06F
tiary : #DAD887
accent : #F0F8A4
---

## Alur Kerja Website
1. **Setup Awal:** Pengelola membuat akun petugas melalui panel Admin.
2. **Input Data Warga:** Petugas mendata warga (nama, RT, RW, no meteran).
3. **Input Bulanan:** Petugas login → pilih warga → isi angka meteran → sistem otomatis hitung `pemakaian`.
4. **Rekapitulasi:** Pengelola melihat tabel laporan seluruh warga dengan filter bulan.

---

## Roadmap Pengembangan

### Milestone 1: Setup Core
- [ ] Instalasi Laravel.
- [ ] Migrasi database (`users`, `wargas`, `pencatatans`).
- [ ] Setup Authentication (Login/Logout username).

### Milestone 2: Fitur Data Warga
- [ ] Buat `WargaController`.
- [ ] Halaman Tambah Warga (input RT/RW dan nomor meteran).
- [ ] Halaman Daftar Warga.

### Milestone 3: Fitur Pencatatan
- [ ] Logic perhitungan `pemakaian` (Angka sekarang - Angka lalu).
- [ ] Form input meteran bulanan.
- [ ] Validasi anti-double input per bulan.

### Milestone 4: Rekap & Dashboard
- [ ] Dashboard Petugas.
- [ ] Halaman Rekap (Tabel dinamis dengan filter bulan).

### Milestone 5: Deployment
- [ ] Setup `.env` untuk InfinityFree.
- [ ] Upload file via FileZilla & Import database SQL.

---

## Catatan Penting
1. **Pemisahan RT & RW:** Tipe data `integer` untuk mempermudah filter/sorting nanti.
2. **Server-Side Logic:** Perhitungan pemakaian wajib dilakukan di Controller agar data konsisten.
3. **Database:** Gunakan MySQL di hosting (bukan Google Sheets) untuk performa yang lebih cepat.