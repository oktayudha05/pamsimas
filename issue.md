# Planning: Fix Dynamic Pricing – Harga Belum Dipakai Sesuai Periode

## Deskripsi Masalah

Pengelola sudah berhasil menyimpan harga baru (misal: mulai berlaku 2026-08), namun tagihan di bulan 2026-08 masih merujuk ke harga lama. Ini terjadi karena **model `Pembayaran.php` belum diperbarui** — method `getTarifAktif` masih menggunakan logika lama yang hanya memeriksa kolom `is_active`, bukan kolom `berlaku_mulai` yang baru ditambahkan.

---

## Root Cause

Di file `app/Models/Pembayaran.php`, method `getTarifAktif` masih seperti ini:

```php
// LOGIKA LAMA (SALAH)
public static function getTarifAktif($dusun)
{
    return self::where('dusun', $dusun)
               ->where('is_active', true)  // ← tidak mempertimbangkan periode bulan
               ->first();
}
```

Meskipun semua controller sudah memanggil `getTarifAktif($dusun, $bulan)`, parameter `$bulan` **diabaikan** karena signature method tidak pernah diperbarui. Akibatnya selalu mengembalikan tarif pertama dengan `is_active = true` tanpa peduli bulannya.

---

## Daftar File yang Terpengaruh

Semua file berikut sudah memanggil `getTarifAktif($dusun, $bulan)` dengan benar, tapi hasilnya salah karena modelnya belum mendukung filter bulan:

| File | Method | Keterangan |
|---|---|---|
| `app/Models/Pembayaran.php` | `getTarifAktif` | **Sumber masalah** – perlu diperbarui |
| `app/Http/Controllers/PembayaranController.php` | `index`, `update` | Tampilan tagihan & simpan pembayaran |
| `app/Http/Controllers/PencatatanController.php` | `store` | Saat pencatat input meteran, tarif langsung dihitung |
| `app/Http/Controllers/RekapController.php` | `index`, `exportExcel` | Laporan rekap bulanan & ekspor Excel |

Semua controller tidak perlu diubah. Cukup perbaiki model saja.

---

## Yang Perlu Diperbaiki

### 1. Perbarui `app/Models/Pembayaran.php`

Tambahkan `berlaku_mulai` ke `fillable`, lalu ubah method `getTarifAktif` agar memfilter berdasarkan periode:

```php
protected $fillable = [
    'dusun',
    'harga_per_meter',
    'dana_meter',
    'berlaku_mulai',    // ← tambahkan ini jika belum ada
    'is_active',
];

// LOGIKA BARU (BENAR)
public static function getTarifAktif($dusun, $bulan = null)
{
    if (!$bulan) {
        $bulan = date('Y-m');
    }

    // Ambil tarif terbaru yang sudah berlaku pada atau sebelum bulan yang ditentukan
    return self::where('dusun', $dusun)
               ->where('berlaku_mulai', '<=', $bulan)
               ->orderBy('berlaku_mulai', 'desc')
               ->first();
}
```

Logika: "Ambil tarif terbaru yang sudah berlaku pada atau sebelum bulan yang ditentukan".

**Catatan:** Controller tidak perlu diubah karena sudah memanggil `getTarifAktif($dusun, $bulan)` dengan benar di semua tempat.

---

## Verifikasi Setelah Perbaikan

1. Buka halaman **Pembayaran**, pilih periode bulan sebelum tarif baru ditambahkan → harus menggunakan harga lama.
2. Pilih periode bulan mulai berlakunya tarif baru → harus menggunakan harga baru.
3. Buka halaman **Rekap Laporan** (tampilan tabel & unduh Excel) untuk bulan yang sama → angka tarif, tagihan, dan total harus sesuai dengan tarif baru.
4. Input pencatatan meteran baru untuk warga di bulan berlakunya tarif baru → titip/tagihan yang tersimpan di database harus menggunakan harga baru.
