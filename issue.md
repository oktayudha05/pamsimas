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

3.  **Yang harus dipastikan berfungsi:**
   - [ ] Tidak ada scroll horizontal (geser kiri-kanan)
   - [ ] Tombol bisa diklik dengan mudah
   - [ ] Form input bisa diisi
   - [ ] Teks tidak terpotong atau terlalu kecil
   - [ ] Menu hamburger bisa dibuka dan ditutup

---

# 🧓 Issue: Optimasi Tampilan untuk Pengguna Lanjut Usia (Elderly-Friendly)

## Latar Belakang

Pengguna aplikasi Tirta Anugerah adalah warga dusun, termasuk **orang tua/lansia** yang:
- Matanya sudah kurang jelas untuk membaca teks kecil
- Jarinya kurang presisi untuk menekan tombol kecil
- Kurang familiar dengan ikon tanpa teks
- Gampang bingung kalau tampilan terlalu padat

Tujuan issue ini: **membuat UI lebih ramah untuk orang tua** tanpa mengubah fungsi/logika aplikasi.

Prinsip utama:
- **Perbesar teks** (minimal `text-sm`, hindari `text-[10px]`)
- **Perbesar tombol** (minimal `py-3 px-5`, target sentuh 48x48px)
- **Tambah kontras** (jangan pakai `text-gray-400` di atas bg putih/terang)
- **Ikon + Teks selalu berdampingan**
- **Kurangi clutter** (kasih spasi, fokus ke informasi penting)

---

## Daftar Perubahan Per File

### 📄 1. Ubah Component `input-label.blade.php` (Label Form)

**Letak:** `resources/views/components/input-label.blade.php`

**Masalah:** Label pakai `text-xs` (12px) — terlalu kecil untuk mata tua.

**Yang diubah:** Ubah ukuran font dari `text-xs` jadi `text-sm`, dan pertebal warna dari `text-gray-500` jadi `text-gray-600` agar kontras lebih tinggi.

```diff
- <label ... class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
+ <label ... class="block text-sm font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
```

---

### 📄 2. Ubah Component `text-input.blade.php` (Input Form)

**Letak:** `resources/views/components/text-input.blade.php`

**Masalah:** Padding `py-2.5` lumayan tapi bisa lebih nyaman. `text-sm` (14px) naikkan ke `text-base` (16px).

```diff
- <input ... class="w-full px-4 py-2.5 ... text-sm ...">
+ <input ... class="w-full px-4 py-3 ... text-base ...">
```

---

### 📄 3. Ubah Component `primary-button.blade.php`

**Letak:** `resources/views/components/primary-button.blade.php`

**Masalah:** `py-2.5 px-5` dan `text-sm` — kurang besar untuk jari orang tua.

```diff
- <button ... class="... px-5 py-2.5 bg-[#36656B] ... text-sm ...">
+ <button ... class="... px-6 py-3 bg-[#36656B] ... text-base ...">
```

---

### 📄 4. Dashboard (`dashboard.blade.php`)

**Letak:** `resources/views/dashboard.blade.php`

**Masalah:**
- Teks label di kartu statistik pakai `text-[10px]` — sangat kecil
- Label "Rata-rata / Warga" pakai `text-gray-500` di bg putih — kurang kontras
- Tombol filter bulan: area klik cukup besar (sudah OK)

**Perubahan:**

| Lokasi (baris) | Sekarang | Ubah Jadi |
|---|---|---|
| 50, 63, 76, 89 (label kartu) | `text-[10px]` | `text-xs` |
| 50 (`text-[#F0F8A4]`) | OK, kontras cukup di bg `#36656B` | tetap |
| 89 (`text-gray-500`) | kurang kontras di bg putih | `text-gray-600` |
| 105, 123, 135, 148, 155 (judul section) | `text-base` | `text-lg` |
| 202, 215 (angka statistik) | OK | tetap |

**Contoh untuk label "Rata-rata / Warga" (baris 89):**
```diff
- <p class="text-gray-500 text-[10px] sm:text-xs font-semibold uppercase tracking-widest leading-tight">Rata-rata / Warga</p>
+ <p class="text-gray-600 text-xs sm:text-sm font-semibold uppercase tracking-widest leading-tight">Rata-rata / Warga</p>
```

---

### 📄 5. Navigasi Desktop (`navigation.blade.php`)

**Letak:** `resources/views/layouts/navigation.blade.php` (baris 18-54)

**Masalah:** Menu desktop hanya teks polos tanpa ikon, padding kecil (`px-3 py-1.5`), teks `text-sm`. Orang tua susah membedakan link yang mirip.

**Perubahan:**
1. Perbesar padding link: `px-3 py-1.5` → `px-4 py-2.5`
2. Tambah ikon SVG di setiap link (salin dari menu mobile yang sudah ada)
3. Perbesar teks: `text-sm` → `text-base`

**Sebelum (baris 19-23):**
```html
<a href="{{ route('dashboard') }}"
   class="px-3 py-1.5 rounded-lg text-sm font-medium transition-all duration-150
          {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
    Dashboard
</a>
```

**Sesudah:**
```html
<a href="{{ route('dashboard') }}"
   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-base font-medium transition-all duration-150
          {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>
    <span>Dashboard</span>
</a>
```

> Lakukan pola yang sama (ikon + teks + padding besar) untuk semua link:
> - Daftar Rumah (baris 25-29)
> - Manajemen Akun (baris 30-34)
> - Pembayaran (baris 36-40)
> - Pencatatan Air (baris 41-45)
> - Rekap Laporan (baris 48-53)
>
> Ikon bisa disalin dari menu mobile yang sudah ada di file yang sama (baris 109-157).

---

### 📄 6. Navigasi Mobile (`navigation.blade.php`)

**Letak:** `resources/views/layouts/navigation.blade.php` (baris 104-181)

**Masalah:** Menu mobile sudah punya ikon + teks (bagus!). Tapi ukuran masih `text-sm` dan padding `px-4 py-3`.

```diff
- class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium ..."
+ class="flex items-center gap-3 px-5 py-4 rounded-lg text-base font-medium ..."
```

> Juga perbesar ikon dari `w-5 h-5` jadi `w-6 h-6`.

---

### 📄 7. Pencatatan Air — Tabel Desktop (`pencatatans/index.blade.php`)

**Letak:** `resources/views/pencatatans/index.blade.php` (baris 45-142)

**Masalah:**
- Header tabel pakai `text-xs` (dari CSS `.bento-table thead th`) — terlalu kecil
- Tombol "Simpan" (baris 129): `text-xs px-3 py-1.5`
- Input angka meteran (baris 107): `py-1.5 text-sm`

**Perubahan:**

**A. Header tabel — override di file langsung:**
```diff
- <th>Warga / Meteran</th>
+ <th class="text-sm">Warga / Meteran</th>
```

> Lakukan untuk semua `<th>` (baris 49-55).

**B. Tombol "Simpan" (baris 128-131):**
```diff
<button type="submit" form="record-{{ $warga->id }}"
- class="inline-block bg-[#36656B] hover:bg-[#2a4f54] text-white text-xs font-semibold px-3 py-1.5 rounded-lg shadow-sm transition">
+ class="inline-block bg-[#36656B] hover:bg-[#2a4f54] text-white text-sm font-semibold px-4 py-2.5 rounded-lg shadow-sm transition">
    Simpan
</button>
```

**C. Input angka meteran (baris 106-108):**
```diff
<input type="number" name="angka_meteran" min="0" placeholder="Input angka..."
- class="w-32 px-3 py-1.5 bg-[#F0F8A4]/30 border border-[#DAD887] text-gray-800 text-sm rounded-lg focus:outline-none focus:ring-1 focus:ring-[#36656B]">
+ class="w-36 px-3 py-2.5 bg-[#F0F8A4]/30 border border-[#DAD887] text-gray-800 text-base rounded-lg focus:outline-none focus:ring-1 focus:ring-[#36656B]">
```

---

### 📄 8. Pencatatan Air — Kartu Mobile (`pencatatans/index.blade.php`)

**Letak:** `resources/views/pencatatans/index.blade.php` (baris 145-244)

**Masalah:** Label info di kartu mobile pakai `text-[10px]` (baris 185, 192, 203).

```diff
- <span class="text-[10px] text-gray-400 font-semibold uppercase block">Angka Lalu</span>
+ <span class="text-xs text-gray-500 font-semibold uppercase block">Angka Lalu</span>
```

Lakukan juga untuk:
- Baris 192: `text-[10px]` → `text-xs`, ubah `text-[#36656B]/70` jadi `text-[#36656B]`
- Baris 203: `text-[10px]` → `text-xs`, ubah `text-gray-400` jadi `text-gray-500`

Tombol "Simpan" di mobile (baris 231-234):
```diff
<button type="submit"
- class="bg-[#36656B] hover:bg-[#2a4f54] text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition w-full sm:w-auto text-center shrink-0">
+ class="bg-[#36656B] hover:bg-[#2a4f54] text-white text-base font-semibold px-5 py-3 rounded-lg shadow-sm transition w-full sm:w-auto text-center shrink-0">
    Simpan
</button>
```

---

### 📄 9. Pembayaran — Tabel Desktop (`pembayarans/index.blade.php`)

**Letak:** `resources/views/pembayarans/index.blade.php`

**Masalah:**
- Header tabel (baris 38): `text-[10px]` — sangat kecil untuk mata tua
- Body tabel: `text-xs` di banyak tempat (baris 82-114)
- Tombol "Bayar" (baris 119): `text-[10px] px-2 py-1.5` — susah dipencet
- Label di modal (baris 174): `text-xs`

**Perubahan:**

| Lokasi | Sekarang | Ubah Jadi |
|---|---|---|
| Baris 38: header `text-[10px]` | `text-[10px]` | `text-sm` |
| Baris 68: label dusun `text-[10px]` | `text-[10px]` | `text-xs` |
| Baris 78: body `text-sm` | OK | tetap |
| Baris 82-114: font `text-xs` | `text-xs` | `text-sm` |
| Baris 119: tombol Bayar | `text-[10px] px-2 py-1.5` | `text-sm px-4 py-2.5` |
| Baris 174: label modal | `text-xs` | `text-sm` |
| Baris 177: teks catatan | `text-[10px]` | `text-xs` |

**Contoh header (baris 38-50):**
```diff
- <thead class="bg-[#36656B] text-white text-[10px] uppercase tracking-wider">
+ <thead class="bg-[#36656B] text-white text-sm uppercase tracking-wider">
```

**Contoh tombol Bayar (baris 118-124):**
```diff
<button onclick="..."
- class="inline-flex items-center gap-1 bg-[#36656B] hover:bg-[#2a4f54] text-white text-[10px] font-semibold px-2 py-1.5 rounded-lg transition">
+ class="inline-flex items-center gap-1.5 bg-[#36656B] hover:bg-[#2a4f54] text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition">
    <svg class="w-3.5 h-3.5" ...>
    Bayar
</button>
```

---

### 📄 10. Pembayaran — Kartu Mobile (`pembayarans/index.blade.php`)

**Letak:** `resources/views/pembayarans/index.blade.php` (baris 203-354)

**Masalah:** Label `text-[10px]` di berbagai tempat (baris 235, 253, 257), teks `text-[11px]` (baris 241).

```diff
- <span class="text-[10px] text-gray-400 font-semibold uppercase">Total Tagihan</span>
+ <span class="text-xs text-gray-500 font-semibold uppercase">Total Tagihan</span>
```

```diff
- <div class="flex items-center gap-2 px-4 py-1.5 text-[11px] text-gray-500">
+ <div class="flex items-center gap-2 px-4 py-2 text-xs text-gray-500">
```

Tombol "Bayar" di mobile (baris 275-280):
```diff
<button onclick="..."
- class="w-full inline-flex items-center justify-center gap-1.5 bg-[#36656B] hover:bg-[#2a4f54] text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition shadow-sm">
+ class="w-full inline-flex items-center justify-center gap-1.5 bg-[#36656B] hover:bg-[#2a4f54] text-white text-base font-semibold px-5 py-3 rounded-xl transition shadow-sm">
    Bayar
</button>
```

---

### 📄 11. Rekap — Tabel Desktop (`rekap.blade.php`)

**Letak:** `resources/views/rekap.blade.php` (baris 80-156)

**Masalah:**
- Header tabel `text-[10px]` dari class `text-xs` di baris 81
- Body `<td>` pakai `text-xs` default
- Label statistik di kartu ringkasan (baris 56, 60, 64, 68): `text-[10px]`

**Perubahan:**

**A. Header tabel (baris 82-97):**
```diff
- <table class="w-full text-xs">
+ <table class="w-full text-sm">
```

**B. Body (baris 101-136): setiap `<td>` tambah font size:**
```diff
- <td class="px-3 py-2 font-medium text-gray-900 whitespace-nowrap">{{ $warga->nama }}</td>
+ <td class="px-3 py-3 font-medium text-gray-900 whitespace-nowrap text-sm">{{ $warga->nama }}</td>
```
> Ini contoh, lakukan untuk semua `<td>` — tambah `py-3` dan `text-sm` atau `text-base`.

**C. Label kartu ringkasan (baris 56):**
```diff
- <p class="text-[10px] uppercase tracking-widest opacity-80">Total Pemakaian</p>
+ <p class="text-xs uppercase tracking-widest opacity-80">Total Pemakaian</p>
```
> Lakukan untuk semua 4 kartu (baris 56, 60, 64, 68).

---

### 📄 12. Rekap — Kartu Mobile (`rekap.blade.php`)

**Letak:** `resources/views/rekap.blade.php` (baris 159-205)

**Masalah:** Grid info di kartu mobile pakai `text-xs` di body dan label, padding kecil.

```diff
- <div class="grid grid-cols-2 gap-2 text-center text-xs">
+ <div class="grid grid-cols-2 gap-3 text-center text-sm">
```

---

### 📄 13. Daftar Rumah — Tabel Desktop (`wargas/index.blade.php`)

**Letak:** `resources/views/wargas/index.blade.php`

**Masalah:**
- Tombol Edit/Hapus (baris 131, 139): `text-xs px-2.5 py-1.5` — terlalu kecil
- Badge RT/RW (baris 117): `text-xs` → masih OK, pertahankan

**Tombol Edit (baris 130-132):**
```diff
<a href="{{ route('wargas.edit', $warga) }}"
- class="inline-flex items-center gap-1 bg-[#DAD887]/60 hover:bg-[#DAD887] text-[#36656B] text-xs font-semibold px-2.5 py-1.5 rounded-lg transition">
+ class="inline-flex items-center gap-1 bg-[#DAD887]/60 hover:bg-[#DAD887] text-[#36656B] text-sm font-semibold px-3 py-2 rounded-lg transition">
    Edit
</a>
```

**Tombol Hapus (baris 138-141):**
```diff
<button type="submit"
- class="inline-flex items-center gap-1 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-semibold px-2.5 py-1.5 rounded-lg transition">
+ class="inline-flex items-center gap-1 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold px-3 py-2 rounded-lg transition">
    Hapus
</button>
```

---

### 📄 14. Daftar Rumah — Kartu Mobile (`wargas/index.blade.php`)

**Letak:** `resources/views/wargas/index.blade.php` (baris 156-208)

**Masalah:** Tombol Edit/Hapus (baris 182, 194) masih `text-xs` dan `py-2.5`.

```diff
- class="flex-1 inline-flex justify-center items-center gap-1.5 bg-[#DAD887]/60 hover:bg-[#DAD887] text-[#36656B] text-xs font-semibold py-2.5 rounded-lg transition">
+ class="flex-1 inline-flex justify-center items-center gap-1.5 bg-[#DAD887]/60 hover:bg-[#DAD887] text-[#36656B] text-sm font-semibold py-3 rounded-lg transition">
```

---

### 📄 15. Manajemen Akun — Tabel Desktop (`akuns/index.blade.php`)

**Letak:** `resources/views/akuns/index.blade.php`

**Masalah yang sama dengan Daftar Rumah:**
- Badge role (baris 105, 107): `text-[10px]` → `text-xs`
- Tombol Edit/Hapus (baris 113, 122): `text-xs` → `text-sm`, padding diperbesar

**Badge role (baris 105):**
```diff
- <span class="inline-block bg-[#DAD887] text-[#36656B] text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wider">Pengelola</span>
+ <span class="inline-block bg-[#DAD887] text-[#36656B] text-xs font-bold px-3 py-1.5 rounded-lg uppercase tracking-wider">Pengelola</span>
```

**Tombol Edit (baris 112-114):**
```diff
<a href="{{ route('akuns.edit', $akun) }}"
- class="inline-flex items-center gap-1 bg-[#DAD887]/60 hover:bg-[#DAD887] text-[#36656B] text-xs font-semibold px-3 py-1.5 rounded-lg transition">
+ class="inline-flex items-center gap-1 bg-[#DAD887]/60 hover:bg-[#DAD887] text-[#36656B] text-sm font-semibold px-4 py-2 rounded-lg transition">
    Edit
</a>
```

---

### 📄 16. Manajemen Akun — Kartu Mobile (`akuns/index.blade.php`)

**Letak:** `resources/views/akuns/index.blade.php` (baris 142-187)

**Masalah:** Badge role di mobile (baris 154, 156) juga `text-[10px]`, tombol Edit/Hapus (baris 162, 171) `text-xs`.

```diff
- <span class="inline-block bg-[#DAD887] text-[#36656B] text-[10px] font-bold px-2 py-1 rounded-lg uppercase">Pengelola</span>
+ <span class="inline-block bg-[#DAD887] text-[#36656B] text-xs font-bold px-3 py-1.5 rounded-lg uppercase">Pengelola</span>
```

```diff
- class="flex-1 inline-flex justify-center items-center gap-1.5 bg-[#DAD887]/60 hover:bg-[#DAD887] text-[#36656B] text-xs font-semibold py-2.5 rounded-lg transition">
+ class="flex-1 inline-flex justify-center items-center gap-1.5 bg-[#DAD887]/60 hover:bg-[#DAD887] text-[#36656B] text-sm font-semibold py-3 rounded-lg transition">
```

---

### 📄 17. Login (`login.blade.php`)

**Letak:** `resources/views/auth/login.blade.php`

**Masalah:**
- Label `Username` dan `Password` (baris 14, 34): `text-xs` → terlalu kecil
- Tombol "Masuk" (baris 58-61): `py-3 text-sm` → kurang besar

**Label (baris 14):**
```diff
- <label for="username" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
+ <label for="username" class="block text-sm font-semibold text-gray-600 uppercase tracking-wider mb-1.5">
```
> Lakukan juga untuk label Password (baris 34).

**Tombol Masuk (baris 58-61):**
```diff
- class="w-full bg-[#36656B] hover:bg-[#2a4f54] text-white font-semibold py-3 px-6 rounded-xl transition-all duration-150 text-sm tracking-wide shadow-sm hover:shadow-md">
+ class="w-full bg-[#36656B] hover:bg-[#2a4f54] text-white font-bold py-4 px-6 rounded-xl transition-all duration-150 text-base tracking-wide shadow-md hover:shadow-lg">
```

---

### 📄 18. Welcome/Landing (`welcome.blade.php`)

**Letak:** `resources/views/welcome.blade.php`

**Masalah minimal** karena halaman ini untuk publik dan sudah didesain besar. Hanya tombol yg perlu diperbesar.

**Tombol "Masuk ke Dashboard" (baris 65-70):**
```diff
- class="inline-flex items-center justify-center gap-2 bg-[#36656B] hover:bg-[#2a4f54] text-white font-semibold px-8 py-3.5 rounded-xl ..."
+ class="inline-flex items-center justify-center gap-2 bg-[#36656B] hover:bg-[#2a4f54] text-white font-bold px-8 py-4 rounded-xl ..."
```

**Tombol "Lihat Statistik" (baris 71-73):**
```diff
- class="inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-[#36656B] border border-[#DAD887] font-semibold px-8 py-3.5 rounded-xl ..."
+ class="inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-[#36656B] border border-[#DAD887] font-bold px-8 py-4 rounded-xl ..."
```

---

### 📄 19. CSS Global — Tambah Class `text-responsive`

**Letak:** `resources/css/app.css`

Tambahkan utility class khusus untuk memudahkan programmer mengganti font size secara global nantinya:

```css
/* Elderly-friendly: ukuran teks minimum */
.text-minimum {
    @apply text-sm leading-relaxed;
}
```

> Class ini tidak wajib dipakai, tapi berguna kalau ada teks yang terlewat dari pengecekan di atas.

---

## Ringkasan Pengecekan Akhir

Setelah semua perubahan diimplementasi, centang halaman berikut:

- [ ] **Login** — font label besar (`text-sm`), tombol Masuk besar dan jelas (`py-4 text-base`)
- [ ] **Dashboard** — tidak ada teks `text-[10px]`, semua label terbaca
- [ ] **Navigasi desktop** — ada ikon + teks, padding cukup (`px-4 py-2.5`)
- [ ] **Navigasi mobile** — padding besar (`px-5 py-4`), font `text-base`
- [ ] **Daftar Rumah** — tombol Edit/Hapus besar (`text-sm px-3 py-2`)
- [ ] **Pencatatan Air** — angka meteran mudah dibaca, tombol Simpan besar
- [ ] **Pembayaran** — tombol Bayar besar, angka rupiah jelas
- [ ] **Rekap Laporan** — tabel terbaca, label kartu ringkasan jelas
- [ ] **Manajemen Akun** — badge role terbaca, tombol Edit/Hapus besar

---

## Urutan Pengerjaan (Prioritas)

1. **Component global** (`input-label`, `text-input`, `primary-button`) — sekali ubah, efek ke semua halaman
2. **Login** — pintu masuk utama
3. **Navigasi** — dipakai di setiap halaman
4. **Pencatatan Air** — halaman operasional utama petugas
5. **Pembayaran** — halaman transaksi
6. **Dashboard** — halaman pertama setelah login
7. **Daftar Rumah & Manajemen Akun** — halaman pengelolaan data
8. **Rekap** — halaman laporan (paling jarang dipakai)

---

## Cara Cek Hasil

Setelah mengubah file:

1. **Refresh halaman** di browser
2. Bayangkan Anda adalah **kakek/nenek usia 65+**:
   - Apakah teks mudah dibaca tanpa menyipitkan mata?
   - Apakah tombol mudah ditekan dengan jari?
   - Apakah Anda bisa membedakan menu yang satu dengan yang lain?
3. Cek **kontras warna**:
   - Jangan pakai `text-gray-300` atau `text-gray-400` di atas background putih
   - Minimal `text-gray-500` atau lebih gelap
4. Test dengan **Chrome DevTools**:
   - Tab **Rendering** → aktifkan `Emulate vision deficiencies`
   - Pilih `Blurred vision` untuk simulasi mata kabur
   - Atau pilih `Protanopia`/`Deuteranopia` untuk simulasi buta warna
5. **Ukur target sentuh**:
   - Tombol minimal 44x44px (atau 48x48px lebih baik)
   - Cek dengan inspect element → lihat computed size

---

# 🔍 Issue: Fitur Pencarian (Search) di 4 Halaman Utama (Daftar Rumah, Manajemen Akun, Pembayaran, Pencatatan Air)

## Latar Belakang

Saat ini, daftar warga, akun, pencatatan air, dan pembayaran menampilkan seluruh data sekaligus (atau filter per bulan saja). Ketika data warga semakin banyak, petugas dan pengelola membutuhkan pencarian cepat berdasarkan nama, nomor meteran, atau username.

Tujuan issue ini: **Menambahkan fitur pencarian (search bar)** pada 4 halaman utama:
1. **Daftar Rumah** (`wargas/index.blade.php` & `WargaController.php`)
2. **Manajemen Akun** (`akuns/index.blade.php` & `AkunController.php`)
3. **Pembayaran** (`pembayarans/index.blade.php` & `PembayaranController.php`)
4. **Pencatatan Air** (`pencatatans/index.blade.php` & `PencatatanController.php`)

Dua versi tampilan harus disediakan:
- **Desktop (Layar Besar):** Search input terletak inline / sejajar dengan filter/tombol lain.
- **Mobile (Layar HP):** Search input memiliki lebar 100% (`w-full`) di bagian paling atas agar mudah digunakan dengan satu tangan di layar HP.

---

## 📋 Modul 1: Fitur Search di Daftar Rumah

### 1. Ubah Backend (`app/Http/Controllers/WargaController.php`)

**Metode:** `index(Request $request)`

**Deskripsi Logika:**
- Tangkap input `search` dari `$request->input('search')`.
- Tambahkan klausul `when($search, ...)` pada query Eloquent.
- Filter berdasarkan kolom: `nama`, `nomor_meteran`, atau `dusun`.
- Pass variabel `$search` ke Blade view agar nilai input tidak hilang saat halaman di-refresh.

**Contoh Kode Controller:**
```php
public function index(Request $request)
{
    $search = $request->input('search');

    $wargas = Warga::query()
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nomor_meteran', 'like', "%{$search}%")
                  ->orWhere('dusun', 'like', "%{$search}%");
            });
        })
        ->orderBy('dusun')
        ->orderBy('rt')
        ->orderBy('rw')
        ->orderBy('nama')
        ->get();

    return view('wargas.index', compact('wargas', 'search'));
}
```

---

### 2. Ubah Frontend (`resources/views/wargas/index.blade.php`)

**Letak:** Di atas tabel & card list (di dalam blok Daftar Rumah).

**Contoh Kode Blade UI (Desktop & Mobile Responsive):**
```html
<!-- Form Pencarian (Desktop & Mobile) -->
<form method="GET" action="{{ route('wargas.index') }}" class="mb-4">
    <div class="flex flex-col sm:flex-row items-center gap-2">
        <div class="relative w-full sm:w-72">
            <input type="text" name="search" value="{{ $search ?? '' }}" 
                   placeholder="Cari nama, no meteran, dusun..." 
                   class="w-full pl-9 pr-8 py-2.5 bg-[#F0F8A4]/30 border border-[#DAD887] text-gray-800 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-[#36656B]">
            <!-- Ikon Kaca Pembesar -->
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <!-- Tombol Reset / Hapus Pencarian -->
            @if(request('search'))
                <a href="{{ route('wargas.index') }}" class="absolute right-2.5 top-3 text-gray-400 hover:text-red-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            @endif
        </div>
        <button type="submit" class="w-full sm:w-auto bg-[#36656B] hover:bg-[#2a4f54] text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow-sm">
            Cari
        </button>
    </div>
</form>
```

---

## 📋 Modul 2: Fitur Search di Manajemen Akun

### 1. Ubah Backend (`app/Http/Controllers/AkunController.php`)

**Metode:** `index(Request $request)`

**Deskripsi Logika:**
- Tangkap `$request->input('search')`.
- Filter model `User` pada kolom `nama`, `username`, dan `role`.

**Contoh Kode Controller:**
```php
public function index(Request $request)
{
    $search = $request->input('search');

    $akuns = User::query()
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        })
        ->orderBy('nama')
        ->get();

    return view('akuns.index', compact('akuns', 'search'));
}
```

---

### 2. Ubah Frontend (`resources/views/akuns/index.blade.php`)

**Letak:** Di bagian atas tabel/kartu manajemen akun.

**Contoh Kode Blade UI:**
```html
<form method="GET" action="{{ route('akuns.index') }}" class="mb-4">
    <div class="flex flex-col sm:flex-row items-center gap-2">
        <div class="relative w-full sm:w-72">
            <input type="text" name="search" value="{{ $search ?? '' }}" 
                   placeholder="Cari nama, username, role..." 
                   class="w-full pl-9 pr-8 py-2.5 bg-[#F0F8A4]/30 border border-[#DAD887] text-gray-800 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-[#36656B]">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            @if(request('search'))
                <a href="{{ route('akuns.index') }}" class="absolute right-2.5 top-3 text-gray-400 hover:text-red-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            @endif
        </div>
        <button type="submit" class="w-full sm:w-auto bg-[#36656B] hover:bg-[#2a4f54] text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow-sm">
            Cari
        </button>
    </div>
</form>
```

---

## 📋 Modul 3: Fitur Search di Pembayaran Air

### 1. Ubah Backend (`app/Http/Controllers/PembayaranController.php`)

**Metode:** `index(Request $request)`

**Deskripsi Logika:**
- Ambil parameter `bulan` (default: bulan berjalan) dan `search`.
- Terapkan pencarian pada query `Warga` berdasarkan `nama` atau `nomor_meteran`.
- Pertahankan proses loop penghitungan saldo & pencatatan untuk warga hasil filter.
- Sertakan `bulan` dan `search` dalam variabel yang dikirim ke view (`compact('wargas', 'bulan', 'search')`).

**Contoh Kode Controller:**
```php
public function index(Request $request)
{
    $bulan = $request->input('bulan', date('Y-m'));
    $search = $request->input('search');

    $wargas = Warga::query()
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nomor_meteran', 'like', "%{$search}%");
            });
        })
        ->orderBy('dusun')
        ->orderBy('rt')
        ->orderBy('rw')
        ->get();

    foreach ($wargas as $warga) {
        // ... (logika existing pencatatan & tagihan tidak diubah) ...
    }

    return view('pembayarans.index', compact('wargas', 'bulan', 'search'));
}
```

---

### 2. Ubah Frontend (`resources/views/pembayarans/index.blade.php`)

**Letak:** Di filter bar bagian atas halaman pembayaran.

**Catatan:** Filter Bulan dan Search dibuat dalam 1 `<form>` yang sama agar nilai bulan tidak hilang saat mencari kata kunci.

**Contoh Kode Blade UI:**
```html
<form method="GET" action="{{ route('pembayaran.index') }}" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
    <!-- Filter Bulan -->
    <input type="month" name="bulan" value="{{ $bulan }}"
           class="w-full sm:w-auto px-4 py-2 bg-[#F0F8A4]/40 border border-[#DAD887] text-gray-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#36656B]"
           onchange="this.form.submit()">

    <!-- Input Search -->
    <div class="relative w-full sm:w-64">
        <input type="text" name="search" value="{{ $search ?? '' }}" 
               placeholder="Cari nama / no meteran..." 
               class="w-full pl-9 pr-8 py-2 bg-[#F0F8A4]/40 border border-[#DAD887] text-gray-800 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-[#36656B]">
        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        @if(request('search'))
            <a href="{{ route('pembayaran.index', ['bulan' => $bulan]) }}" class="absolute right-2.5 top-2.5 text-gray-400 hover:text-red-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        @endif
    </div>

    <button type="submit" class="w-full sm:w-auto bg-[#36656B] hover:bg-[#2a4f54] text-white text-sm font-semibold px-4 py-2 rounded-xl transition shadow-sm">
        Cari
    </button>
</form>
```

---

## 📋 Modul 4: Fitur Search di Pencatatan Air

### 1. Ubah Backend (`app/Http/Controllers/PencatatanController.php`)

**Metode:** `index(Request $request)`

**Deskripsi Logika:**
- Tangkap `bulan` dan `search`.
- Filter query `Warga` pada nama atau nomor meteran sebelum fetching data meteran bulanan.

**Contoh Kode Controller:**
```php
public function index(Request $request)
{
    $bulan = $request->input('bulan', date('Y-m'));
    $search = $request->input('search');

    $wargas = Warga::query()
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nomor_meteran', 'like', "%{$search}%");
            });
        })
        ->orderBy('rt')
        ->orderBy('rw')
        ->orderBy('nama')
        ->get();

    foreach ($wargas as $warga) {
        // ... (logika existing pencatatan_sekarang & pencatatan_lalu) ...
    }

    return view('pencatatans.index', compact('wargas', 'bulan', 'search'));
}
```

---

### 2. Ubah Frontend (`resources/views/pencatatans/index.blade.php`)

**Letak:** Di filter bar bagian atas halaman pencatatan.

**Contoh Kode Blade UI:**
```html
<form method="GET" action="{{ route('pencatatans.index') }}" class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
    <!-- Filter Bulan -->
    <input type="month" name="bulan" value="{{ $bulan }}"
           class="w-full sm:w-auto px-4 py-2 bg-[#F0F8A4]/40 border border-[#DAD887] text-gray-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#36656B]"
           onchange="this.form.submit()">

    <!-- Input Search -->
    <div class="relative w-full sm:w-64">
        <input type="text" name="search" value="{{ $search ?? '' }}" 
               placeholder="Cari nama / no meteran..." 
               class="w-full pl-9 pr-8 py-2 bg-[#F0F8A4]/40 border border-[#DAD887] text-gray-800 text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-[#36656B]">
        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        @if(request('search'))
            <a href="{{ route('pencatatans.index', ['bulan' => $bulan]) }}" class="absolute right-2.5 top-2.5 text-gray-400 hover:text-red-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        @endif
    </div>

    <button type="submit" class="w-full sm:w-auto bg-[#36656B] hover:bg-[#2a4f54] text-white text-sm font-semibold px-4 py-2 rounded-xl transition shadow-sm">
        Cari
    </button>
</form>
```

---

## 🧪 Checklist Pengujian (Testing)

Sebelum melaporkan pekerjaan selesai, verifikasi poin berikut:

- [ ] **Daftar Rumah (`/wargas`)**:
  - [ ] Ketik kata kunci (misal: "Budi") lalu tekan Enter / klik Cari → Data terfilter dengan benar.
  - [ ] Ketik nomor meteran (misal: "MTR-001") → Menampilkan data yang tepat.
  - [ ] Klik ikon (X) reset → Kata kunci terhapus dan seluruh data tampil kembali.
  - [ ] Buka DevTools mode HP (375px) → Search bar tampil full width (`w-full`) dan tidak memotong tombol.

- [ ] **Manajemen Akun (`/akuns`)**:
  - [ ] Ketik username atau role ("pengelola" / "petugas") → Hasil filter sesuai.
  - [ ] Tombol reset bekerja dengan baik.

- [ ] **Pembayaran (`/pembayaran`)**:
  - [ ] Pilih bulan tertentu, lalu ketik nama warga → Filter bulan tidak berubah.
  - [ ] Hasil pencarian dapat langsung ditransaksikan via modal pembayaran.

- [ ] **Pencatatan Air (`/pencatatans`)**:
  - [ ] Pilih bulan tertentu, lalu ketik nomor meteran → Data tampil sesuai.
  - [ ] Penginputan angka meteran dari data hasil pencarian (desktop & mobile card) berjalan tanpa error.
