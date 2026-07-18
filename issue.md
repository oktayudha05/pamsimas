# 📱 Issue: Optimasi UI/UX untuk Perangkat Mobile

## Latar Belakang

Aplikasi Tirta Anugerah saat ini sudah berjalan dengan baik di desktop/laptop.
Namun ketika dibuka di HP (handphone), beberapa halaman terlihat tidak rapi:
- Tabel terlalu lebar sehingga harus geser ke kanan
- Tombol terlalu kecil dan susah dipencet
- Form input dan layout kurang nyaman di layar kecil
- Halaman pencatatan meteran sangat sulit dipakai di HP karena tabel banyak kolom

Tujuan issue ini: **membuat tampilan aplikasi nyaman dipakai di HP**, terutama oleh petugas yang mungkin input data langsung dari lapangan menggunakan HP.

---

## Halaman yang Perlu Diperbaiki

| No | Halaman | File | Masalah Utama |
|----|---------|------|---------------|
| 1 | Navigasi | `navigation.blade.php` | Menu hamburger sudah ada, tapi area tap kecil |
| 2 | Dashboard | `dashboard.blade.php` | Kartu statistik sudah responsive, OK |
| 3 | Daftar Rumah (Warga) | `wargas/index.blade.php` | Layout 2 kolom (form + tabel) jelek di HP |
| 4 | Pencatatan Meteran | `pencatatans/index.blade.php` | Tabel 6 kolom sangat sulit di HP |
| 5 | Rekap Laporan | `rekap.blade.php` | Belum dicek, perlu audit juga |
| 6 | Daftar Petugas | `petugases/index.blade.php` | Perlu audit juga |

---

## Tugas-Tugas (Checklist)

### 🔧 1. Perbaiki Navigasi (navigation.blade.php)

**Apa yang perlu diubah:**
- Tombol hamburger (ikon ☰) sudah ada tapi area kliknya kecil → perbesar jadi minimal `p-3`
- Di menu mobile (dropdown), tambahkan ikon kecil di sebelah kiri setiap link agar lebih mudah dibaca
- Tambahkan `min-height: 44px` atau `py-3` pada setiap link menu mobile agar mudah dipencet jari

**Contoh perubahan:**
```html
{{-- Sebelum --}}
<a href="..." class="block px-3 py-2 rounded-lg text-sm font-medium ...">Dashboard</a>

{{-- Sesudah --}}
<a href="..." class="flex items-center gap-2 px-4 py-3 rounded-lg text-sm font-medium ...">
    <svg ...><!-- ikon --></svg>
    Dashboard
</a>
```

---

### 🔧 2. Perbaiki Halaman Daftar Rumah (wargas/index.blade.php)

**Masalah:**
Layout `grid-cols-1 lg:grid-cols-3` membuat form tambah warga dan tabel tampil berdampingan di layar besar.
Di HP sudah jadi 1 kolom, **tapi tabelnya masih punya 4 kolom** (Nama, RT/RW, Meteran, Aksi) yang bisa overflow.

**Yang perlu diubah:**

**A. Tabel di HP → ubah jadi Card List**

Di layar mobile, sembunyikan tabel normal dan ganti dengan tampilan kartu per-warga.

Caranya: gunakan class `hidden md:table` pada tabel, lalu buat tampilan kartu khusus mobile dengan class `md:hidden`.

```html
{{-- Tabel: hanya tampil di layar md ke atas --}}
<div class="hidden md:block overflow-x-auto rounded-xl border border-[#DAD887]/30">
    <table class="bento-table">
        ...isi tabel seperti sekarang...
    </table>
</div>

{{-- Kartu: hanya tampil di layar mobile --}}
<div class="md:hidden space-y-3">
    @forelse($wargas as $warga)
    <div class="bg-white border border-[#DAD887]/40 rounded-xl p-4">
        <div class="flex items-center justify-between mb-2">
            <span class="font-semibold text-gray-900">{{ $warga->nama }}</span>
            <span class="bg-[#F0F8A4] text-[#36656B] text-xs font-bold px-2 py-0.5 rounded-md">
                RT {{ sprintf('%02d', $warga->rt) }}/RW {{ sprintf('%02d', $warga->rw) }}
            </span>
        </div>
        <p class="text-xs text-gray-400 font-mono mb-3">{{ $warga->nomor_meteran }}</p>
        <div class="flex gap-2">
            <a href="{{ route('wargas.edit', $warga) }}"
               class="flex-1 text-center bg-[#DAD887]/60 hover:bg-[#DAD887] text-[#36656B] text-xs font-semibold py-2 rounded-lg transition">
                Edit
            </a>
            <form action="{{ route('wargas.destroy', $warga) }}" method="POST"
                  onsubmit="return confirm('Hapus data warga {{ $warga->nama }}?')"
                  class="flex-1">
                @csrf @method('DELETE')
                <button type="submit"
                        class="w-full bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold py-2 rounded-lg transition">
                    Hapus
                </button>
            </form>
        </div>
    </div>
    @empty
    <p class="text-center text-gray-400 text-sm py-8">Belum ada data warga terdaftar.</p>
    @endforelse
</div>
```

**B. Form Tambah Warga**

Form sudah cukup bagus. Pastikan input punya `class` dengan lebar penuh (`w-full`) dan padding cukup besar agar nyaman diketik di HP.

---

### 🔧 3. Perbaiki Halaman Pencatatan Meteran (pencatatans/index.blade.php)

Ini halaman yang paling sulit di HP karena tabelnya punya **6 kolom**:
Warga/Meteran | Status | Angka Lalu | Angka Baru | Pemakaian | Aksi

**Solusi: Ubah tabel jadi tampilan kartu di HP**

Setiap baris tabel = 1 kartu yang menampilkan semua info secara vertikal.

```html
{{-- Tabel: sembunyikan di HP, tampilkan di md ke atas --}}
<div class="hidden md:block overflow-x-auto rounded-xl border border-[#DAD887]/30">
    <table class="bento-table">
        ...isi tabel seperti sekarang...
    </table>
</div>

{{-- Kartu: tampilkan hanya di HP --}}
<div class="md:hidden space-y-3">
    @forelse($wargas as $warga)
    <div class="bg-white border border-[#DAD887]/40 rounded-xl p-4">

        {{-- Header kartu: nama + status --}}
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="font-semibold text-gray-900 text-sm">{{ $warga->nama }}</p>
                <p class="text-xs text-gray-400 font-mono mt-0.5">
                    {{ $warga->nomor_meteran }} · RT {{ sprintf('%02d', $warga->rt) }}/RW {{ sprintf('%02d', $warga->rw) }}
                </p>
            </div>
            {{-- Badge status --}}
            @if($warga->pencatatan_sekarang)
                <span class="text-xs font-semibold px-2 py-0.5 rounded-lg bg-[#75B06F]/25 text-[#36656B] border border-[#75B06F]/30">✓ Sudah</span>
            @else
                <span class="text-xs font-semibold px-2 py-0.5 rounded-lg bg-red-50 text-red-700 border border-red-200">✗ Belum</span>
            @endif
        </div>

        {{-- Info angka meteran --}}
        <div class="grid grid-cols-3 gap-2 text-center mb-3">
            <div class="bg-gray-50 rounded-lg p-2">
                <p class="text-xs text-gray-400 mb-1">Angka Lalu</p>
                <p class="font-mono font-semibold text-sm text-gray-700">
                    {{ $warga->pencatatan_lalu ? number_format($warga->pencatatan_lalu->angka_meteran) : 0 }}
                </p>
            </div>
            <div class="bg-[#F0F8A4]/50 rounded-lg p-2">
                <p class="text-xs text-gray-400 mb-1">Angka Baru</p>
                <p class="font-mono font-semibold text-sm text-[#36656B]">
                    @if($warga->pencatatan_sekarang)
                        {{ number_format($warga->pencatatan_sekarang->angka_meteran) }}
                    @else
                        -
                    @endif
                </p>
            </div>
            <div class="bg-gray-50 rounded-lg p-2">
                <p class="text-xs text-gray-400 mb-1">Pemakaian</p>
                <p class="font-mono font-semibold text-sm text-[#36656B]">
                    @if($warga->pencatatan_sekarang)
                        +{{ number_format($warga->pencatatan_sekarang->pemakaian) }}m³
                    @else
                        -
                    @endif
                </p>
            </div>
        </div>

        {{-- Form input atau info pencatat --}}
        @if($warga->pencatatan_sekarang)
            <p class="text-xs text-gray-400 text-center">
                Dicatat oleh: {{ $warga->pencatatan_sekarang->user->nama ?? 'Sistem' }}
            </p>
        @else
            <form id="record-mobile-{{ $warga->id }}" action="{{ route('pencatatans.store') }}" method="POST"
                  class="flex gap-2">
                @csrf
                <input type="hidden" name="warga_id" value="{{ $warga->id }}">
                <input type="hidden" name="bulan" value="{{ $bulan }}">
                <input type="number" name="angka_meteran" min="0" placeholder="Masukkan angka meteran..."
                       class="flex-1 px-3 py-2 bg-[#F0F8A4]/30 border border-[#DAD887] text-gray-800 text-sm rounded-lg
                              focus:outline-none focus:ring-1 focus:ring-[#36656B]">
                <button type="submit"
                        class="bg-[#36656B] hover:bg-[#2a4f54] text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm">
                    Simpan
                </button>
            </form>
        @endif
    </div>
    @empty
    <p class="text-center text-gray-400 text-sm py-8">Belum ada data warga terdaftar.</p>
    @endforelse
</div>
```

---

### 🔧 4. Perbaiki Halaman Rekap Laporan (rekap.blade.php)

Perlu **audit dulu** file ini untuk mengetahui masalah spesifik.
Setelah audit, kemungkinan besar solusinya sama: sembunyikan tabel di HP, buat tampilan kartu.

**Langkah:**
1. Buka file `resources/views/rekap.blade.php`
2. Lihat apakah ada tabel dengan banyak kolom
3. Terapkan pola yang sama seperti di atas (tabel untuk desktop, kartu untuk mobile)

---

### 🔧 5. Perbaiki Halaman Daftar Petugas (petugases/index.blade.php)

Sama seperti rekap, perlu **audit dulu**.
Pola perbaikan sama: kartu list untuk mobile.

---

### 🔧 6. Perbaiki Layout Utama (app.blade.php)

Cek file `resources/views/layouts/app.blade.php`. Pastikan:
- Ada `<meta name="viewport" content="width=device-width, initial-scale=1">` di `<head>` (biasanya Laravel sudah ada)
- Padding konten utama cukup untuk HP: minimal `px-4` di mobile
- Tidak ada elemen yang memaksa lebar lebih dari 100vw

---

## Panduan Umum untuk Programmer

### Prinsip Mobile-First yang Dipakai di Project Ini

Project ini pakai **Tailwind CSS**. Ini cara kerjanya:
- Tanpa prefix = berlaku di semua ukuran layar (termasuk HP)
- `sm:` = berlaku di layar ≥ 640px
- `md:` = berlaku di layar ≥ 768px
- `lg:` = berlaku di layar ≥ 1024px

**Jadi untuk sembunyikan/tampilkan elemen:**
```html
<!-- Hanya tampil di HP (sembunyikan di md ke atas) -->
<div class="md:hidden">...</div>

<!-- Hanya tampil di tablet/desktop (sembunyikan di HP) -->
<div class="hidden md:block">...</div>
```

### Ukuran Tombol yang Baik di HP
Tombol & link harus punya area sentuh minimal **44x44px**. Gunakan `py-2.5 px-4` atau lebih besar.

### Input Form di HP
Pastikan semua `<input>` punya `w-full` agar tidak overflow, dan `text-base` atau `text-sm` (hindari `text-xs` pada input).

---

## Urutan Pengerjaan yang Disarankan

1. **Pencatatan Meteran** → paling krusial, petugas pakai ini di lapangan
2. **Daftar Rumah (Warga)** → sering dipakai
3. **Navigasi** → cepat dan mudah
4. **Rekap Laporan** → audit dulu
5. **Daftar Petugas** → audit dulu

---

## Cara Cek Hasil

Setelah mengubah file, cek tampilan dengan cara:

1. **Di browser (Chrome/Firefox):**
   - Buka halaman yang sudah diubah
   - Tekan `F12` untuk buka DevTools
   - Klik ikon HP/tablet (atau tekan `Ctrl+Shift+M`)
   - Pilih ukuran perangkat: **iPhone SE** (375px), **Samsung Galaxy** (360px)
   - Scroll dan coba semua fitur (klik tombol, isi form, dll.)

2. **Ukuran layar yang harus dicek:**
   - 375px lebar (iPhone SE / HP kecil)
   - 390px lebar (iPhone 14)
   - 430px lebar (HP besar)

3. **Yang harus dipastikan berfungsi:**
   - [ ] Tidak ada scroll horizontal (geser kiri-kanan)
   - [ ] Tombol bisa diklik dengan mudah
   - [ ] Form input bisa diisi
   - [ ] Teks tidak terpotong atau terlalu kecil
   - [ ] Menu hamburger bisa dibuka dan ditutup
