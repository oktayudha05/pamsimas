# Panduan Implementasi Fitur Pencarian (Search)

Dokumen ini berisi panduan untuk menambahkan fitur pencarian (search) pada 4 modul utama: **Daftar Rumah (Warga)**, **Manajemen Akun**, **Pembayaran**, dan **Pencatatan Air**.

---

## 1. Konsep Umum Pencarian

Pencarian dilakukan secara **Server-Side** (melalui query database di Controller) menggunakan parameter `search` dari query string URL (contoh: `?search=budi`). 

Cara kerja:
- Input pencarian dikirim via form `<form method="GET">`.
- Controller menerima parameter `$request->query('search')` atau `$request->input('search')`.
- Controller memfilter data menggunakan query builder `where` atau `when()`.
- Halaman memunculkan kembali teks pencarian di input box sebagai nilai default (`value="{{ $search }}"`).
- Menambahkan tombol/tautan "Reset" untuk menghapus pencarian saat ini.

---

## 2. Implementasi Backend (Controller)

### A. Modul Daftar Rumah (`WargaController.php`)
Ubah method `index` untuk menerima input pencarian berdasarkan **Nama Warga** atau **Nomor Meteran**:
```php
public function index(Request $request)
{
    $search = $request->query('search');

    $wargas = Warga::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('nomor_meteran', 'like', "%{$search}%");
        })
        ->orderBy('dusun')
        ->orderBy('rt')
        ->orderBy('rw')
        ->orderBy('nama')
        ->get();

    return view('wargas.index', compact('wargas', 'search'));
}
```

### B. Modul Manajemen Akun (`AkunController.php`)
Ubah method `index` untuk mencari berdasarkan **Nama** atau **Username**:
```php
public function index(Request $request)
{
    $search = $request->query('search');

    $akuns = User::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('username', 'like', "%{$search}%");
        })
        ->orderBy('nama')
        ->get();

    return view('akuns.index', compact('akuns', 'search'));
}
```

### C. Modul Pembayaran (`PembayaranController.php`)
Ubah method `index` untuk mencari warga berdasarkan **Nama** atau **Nomor Meteran**:
```php
public function index(Request $request)
{
    $bulan = $request->input('bulan', date('Y-m'));
    $search = $request->input('search');

    $wargas = Warga::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('nomor_meteran', 'like', "%{$search}%");
        })
        ->orderBy('dusun')
        ->orderBy('rt')
        ->orderBy('rw')
        ->get();

    // Loop foreach ($wargas as $warga) tetap dipertahankan seperti semula...

    return view('pembayarans.index', compact('wargas', 'bulan', 'search'));
}
```

### D. Modul Pencatatan Air (`PencatatanController.php`)
Ubah method `index` untuk mencari warga berdasarkan **Nama** atau **Nomor Meteran**:
```php
public function index(Request $request)
{
    $bulan = $request->input('bulan', date('Y-m'));
    $search = $request->input('search');

    $wargas = Warga::when($search, function ($query, $search) {
            return $query->where('nama', 'like', "%{$search}%")
                         ->orWhere('nomor_meteran', 'like', "%{$search}%");
        })
        ->orderBy('rt')
        ->orderBy('rw')
        ->orderBy('nama')
        ->get();

    // Loop foreach ($wargas as $warga) tetap dipertahankan seperti semula...

    return view('pencatatans.index', compact('wargas', 'bulan', 'search'));
}
```

---

## 3. Implementasi Frontend (Blade Views)

Setiap halaman index untuk masing-masing modul perlu memiliki komponen pencarian. Agar ramah perangkat mobile dan desktop:
- **Desktop**: Input pencarian diletakkan sejajar di samping kanan/kiri tombol aksi lainnya.
- **Mobile**: Input pencarian mengambil lebar penuh (`w-full`) di bawah judul atau di baris baru agar tidak memakan ruang horizontal.

### Template Komponen Form Pencarian (Tailwind CSS)
Gunakan potongan kode berikut di dalam layout Blade (contoh untuk `wargas/index.blade.php`, `akuns/index.blade.php`, `pembayarans/index.blade.php`, dan `pencatatans/index.blade.php`):

```html
<!-- Form Pencarian (Desktop & Mobile Responsive) -->
<form method="GET" action="{{ url()->current() }}" class="w-full md:w-80">
    <!-- Mempertahankan parameter filter lain (seperti bulan) jika ada -->
    @if(request()->has('bulan'))
        <input type="hidden" name="bulan" value="{{ request('bulan') }}">
    @endif

    <div class="relative flex items-center">
        <!-- Input Text -->
        <input type="text" 
               name="search" 
               value="{{ $search ?? '' }}" 
               placeholder="Cari nama atau no. meteran..." 
               class="w-full px-4 py-2 pr-10 text-sm bg-gray-50 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#36656B] focus:border-transparent transition">
        
        <!-- Tombol submit / Icon cari -->
        <button type="submit" class="absolute right-3 text-gray-400 hover:text-[#36656B]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </button>

        <!-- Tombol Reset (Hanya muncul jika sedang mencari) -->
        @if(!empty($search))
            <a href="{{ url()->current() }}{{ request()->has('bulan') ? '?bulan='.request('bulan') : '' }}" 
               class="absolute right-10 text-xs text-red-500 hover:underline mr-1">
                Reset
            </a>
        @endif
    </div>
</form>
```

### Penempatan Layout pada Masing-Masing Halaman

1. **Daftar Rumah (`wargas/index.blade.php`)**
   - Letakkan di bagian atas tabel data warga.
   - Pada desktop: Gabungkan dengan tombol "Tambah Warga".
   - Pada mobile: Form pencarian bertumpuk secara vertikal di atas tabel data warga.

2. **Manajemen Akun (`akuns/index.blade.php`)**
   - Letakkan di bagian header modul manajemen akun.
   - Pada desktop: Sejajar dengan tombol "Tambah Akun".
   - Pada mobile: Berada di atas daftar akun.

3. **Pembayaran (`pembayarans/index.blade.php`)**
   - Gabungkan dengan form filter bulan.
   - Pastikan tag `<form>` menyertakan input tersembunyi (hidden input) untuk `bulan` agar filter bulan tidak hilang ketika menekan tombol cari, begitupun sebaliknya.

4. **Pencatatan Air (`pencatatans/index.blade.php`)**
   - Gabungkan dengan form filter bulan.
   - Tambahkan input pencarian sehingga petugas pencatat air bisa dengan cepat memfilter nama warga tertentu yang meteran airnya ingin diinput.
