<x-app-layout>
    <div class="space-y-6">
        {{-- Header & Filter --}}
        <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#F0F8A4] rounded-xl flex items-center justify-center text-[#36656B]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-[#36656B]">Pembayaran & Tagihan</h1>
                    <p class="text-xs text-gray-400">Rincian perhitungan dan sistem saldo berjalan</p>
                </div>
            </div>

            <form method="GET" action="{{ route('pembayaran.index') }}" class="flex items-center gap-2">
                <input type="month" name="bulan" value="{{ $bulan }}"
                    class="px-4 py-2 bg-[#F0F8A4]/40 border border-[#DAD887] text-gray-800 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#36656B] transition-all"
                    onchange="this.form.submit()">
            </form>
        </div>

        @if(session('success'))
            <div class="bg-[#75B06F]/20 text-[#36656B] text-sm font-semibold px-4 py-3 rounded-xl border border-[#75B06F]/30 relative">
                {{ session('success') }}
                <button onclick="this.parentElement.remove()" class="absolute top-3 right-3 text-lg leading-none hover:text-red-600">&times;</button>
            </div>
        @endif

        {{-- Desktop Table --}}
        <div class="bg-white rounded-2xl p-6 border border-[#DAD887]/50 shadow-sm">
            <h2 class="text-lg font-bold text-[#36656B] mb-4">Pembayaran Bulanan</h2>

            <div class="hidden md:block bg-white rounded-2xl border border-[#DAD887]/50 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-[#36656B] text-white text-[10px] uppercase tracking-wider">
                            <tr>
                                <th class="px-3 py-3">Warga / Dusun</th>
                                <th class="px-3 py-3 text-center">Pemakaian</th>
                                <th class="px-3 py-3 text-right">Tarif/m³</th>
                                <th class="px-3 py-3 text-right">Dana Meter</th>
                                <th class="px-3 py-3 text-right">Tagihan</th>
                                <th class="px-3 py-3 text-right">Saldo Lalu</th>
                                <th class="px-3 py-3 text-right font-bold">Total Tagihan</th>
                                <th class="px-3 py-3 text-center">Dibayar</th>
                                <th class="px-3 py-3 text-right">Sisa Saldo</th>
                                <th class="px-3 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#DAD887]/30">
                            @forelse($wargas as $warga)
                                @if($warga->pencatatan)
                                    @php
                                        $pemakaian = $warga->pencatatan->pemakaian_detail;
                                        $harga = $warga->pencatatan->harga_per_meter;
                                        $dana = $warga->pencatatan->dana_meter;
                                        $tagihan = $warga->pencatatan->tagihan_bulan_ini;
                                        $saldoAwal = $warga->pencatatan->saldo_awal;
                                        $totalHarus = $warga->pencatatan->total_harus_dibayar;
                                        $dibayar = $warga->pencatatan->dibayar;
                                        $sisaSaldo = $totalHarus - $dibayar;
                                    @endphp
                                    <tr class="hover:bg-[#F0F8A4]/20 transition-colors">
                                        <td class="px-3 py-3">
                                            <div class="font-semibold text-gray-900">{{ $warga->nama }}</div>
                                            <div class="text-[10px] text-gray-500 flex items-center gap-1 mt-0.5">
                                                <span class="px-1 py-0.5 rounded bg-gray-100 text-gray-600 font-mono">
                                                    {{ $warga->dusun === 'sragan' ? 'Sragan' : 'Luar' }}
                                                </span>
                                                @if($warga->dusun === 'sragan')
                                                    <span>RT{{ sprintf('%02d', $warga->rt) }}/RW{{ sprintf('%02d', $warga->rw) }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        
                                        <td class="px-3 py-3 text-center font-mono text-gray-700">
                                            {{ number_format($pemakaian, 0, ',', '.') }} <span class="text-[10px] text-gray-400">m³</span>
                                        </td>

                                        <td class="px-3 py-3 text-right font-mono text-gray-600 text-xs">
                                            Rp {{ number_format($harga, 0, ',', '.') }}
                                        </td>

                                        <td class="px-3 py-3 text-right font-mono text-gray-600 text-xs">
                                            Rp {{ number_format($dana, 0, ',', '.') }}
                                        </td>

                                        <td class="px-3 py-3 text-right font-mono font-semibold text-[#36656B] text-xs">
                                            Rp {{ number_format($tagihan, 0, ',', '.') }}
                                        </td>

                                        <td class="px-3 py-3 text-right font-mono text-xs {{ $saldoAwal > 0 ? 'text-red-500' : ($saldoAwal < 0 ? 'text-[#75B06F]' : 'text-gray-400') }}">
                                            Rp {{ number_format(abs($saldoAwal), 0, ',', '.') }}
                                        </td>

                                        <td class="px-3 py-3 text-right font-mono font-bold text-[#36656B] text-xs">
                                            Rp {{ number_format($totalHarus, 0, ',', '.') }}
                                        </td>

                                        <td class="px-3 py-3 text-center font-mono font-semibold text-[#75B06F] text-xs">
                                            Rp {{ number_format($dibayar, 0, ',', '.') }}
                                        </td>

                                        <td class="px-3 py-3 text-right font-mono font-bold text-xs {{ $sisaSaldo > 0 ? 'text-red-600' : ($sisaSaldo < 0 ? 'text-[#75B06F]' : 'text-gray-400') }}">
                                            Rp {{ number_format(abs($sisaSaldo), 0, ',', '.') }}
                                            @if($sisaSaldo > 0)
                                                <span class="block text-[9px] text-red-500">(Kurang)</span>
                                            @elseif($sisaSaldo < 0)
                                                <span class="block text-[9px] text-[#75B06F]">(Lebih)</span>
                                            @else
                                                <span class="block text-[9px] text-gray-400">(Lunas)</span>
                                            @endif
                                        </td>

                                        <td class="px-3 py-3 text-center">
                                            <button onclick="document.getElementById('modal-{{ $warga->pencatatan->id }}').showModal()" 
                                                class="inline-flex items-center gap-1 bg-[#36656B] hover:bg-[#2a4f54] text-white text-[10px] font-semibold px-2 py-1.5 rounded-lg transition">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Bayar
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Modal Pembayaran --}}
                                    <dialog id="modal-{{ $warga->pencatatan->id }}" class="rounded-2xl p-0 shadow-2xl backdrop:bg-black/50 w-full max-w-md">
                                        <div class="bg-white p-6">
                                            <h3 class="text-lg font-bold text-[#36656B] mb-4">Input Pembayaran</h3>
                                            
                                            <div class="bg-[#F0F8A4]/30 rounded-xl p-4 mb-6 space-y-2 text-sm">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Warga:</span>
                                                    <span class="font-semibold">{{ $warga->nama }}</span>
                                                </div>
                                                
                                                <div class="border-t border-[#DAD887]/50 pt-2 mt-2 space-y-1 text-xs text-gray-600">
                                                    <div class="flex justify-between">
                                                        <span>Pemakaian:</span>
                                                        <span class="font-mono">{{ number_format($pemakaian) }} m³</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span>Tarif per m³:</span>
                                                        <span class="font-mono">Rp {{ number_format($harga) }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span>Dana Meter:</span>
                                                        <span class="font-mono">Rp {{ number_format($dana) }}</span>
                                                    </div>
                                                    <div class="flex justify-between font-semibold text-[#36656B] pt-1 border-t border-[#DAD887]/50">
                                                        <span>Subtotal Tagihan:</span>
                                                        <span class="font-mono">Rp {{ number_format($tagihan) }}</span>
                                                    </div>
                                                </div>

                                                <div class="flex justify-between pt-2">
                                                    <span class="text-gray-600">Saldo Bulan Lalu:</span>
                                                    <span class="font-mono {{ $saldoAwal > 0 ? 'text-red-500' : 'text-[#75B06F]' }}">
                                                        Rp {{ number_format(abs($saldoAwal)) }} {{ $saldoAwal < 0 ? '(Kredit)' : '' }}
                                                    </span>
                                                </div>
                                                <div class="border-t border-[#DAD887] pt-2 flex justify-between text-base">
                                                    <span class="font-bold text-[#36656B]">Total Harus Dibayar:</span>
                                                    <span class="font-bold font-mono text-[#36656B]">Rp {{ number_format($totalHarus) }}</span>
                                                </div>
                                            </div>

                                            <form action="{{ route('pembayaran.update', $warga->pencatatan->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="mb-4">
                                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Jumlah Dibayarkan (Rp)</label>
                                                    <input type="number" name="dibayar" value="{{ $dibayar ?: $totalHarus }}" min="0" required
                                                        class="w-full px-4 py-3 bg-white border border-[#DAD887] text-gray-800 rounded-xl text-lg font-mono font-semibold focus:outline-none focus:ring-2 focus:ring-[#36656B] transition-all">
                                                    <p class="text-[10px] text-gray-400 mt-1">*Jika dibayar lebih, sisa akan otomatis jadi kredit bulan depan.</p>
                                                </div>
                                                <div class="flex gap-3">
                                                    <button type="button" onclick="document.getElementById('modal-{{ $warga->pencatatan->id }}').close()"
                                                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl text-sm transition">
                                                        Batal
                                                    </button>
                                                    <button type="submit"
                                                        class="flex-1 bg-[#36656B] hover:bg-[#2a4f54] text-white font-semibold py-2.5 rounded-xl text-sm transition shadow-sm">
                                                        Simpan Pembayaran
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </dialog>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-8 text-gray-400">Belum ada data warga.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

       {{-- Mobile Card List --}}
        <div class="md:hidden space-y-3">
            @forelse($wargas as $warga)
                @if($warga->pencatatan)
                    @php
                        $pemakaian = $warga->pencatatan->pemakaian_detail;
                        $tagihan = $warga->pencatatan->tagihan_bulan_ini;
                        $saldoAwal = $warga->pencatatan->saldo_awal;
                        $totalHarus = $warga->pencatatan->total_harus_dibayar;
                        $dibayar = $warga->pencatatan->dibayar;
                        $sisaSaldo = $totalHarus - $dibayar;
                    @endphp
                    <div class="bg-[#F0F8A4]/10 rounded-2xl border border-[#DAD887]/50 shadow-sm overflow-hidden">
                        
                        {{-- Header: Nama + Badge Dusun --}}
                        <div class="flex items-start justify-between gap-2 p-4 pb-2">
                            <div class="min-w-0 flex-1">
                                <span class="font-semibold text-gray-900 text-base block truncate">{{ $warga->nama }}</span>
                                <div class="flex items-center gap-1 mt-0.5">
                                    <span class="px-1.5 py-0.5 rounded bg-gray-100 text-gray-600 text-[10px] font-mono">
                                        {{ $warga->dusun === 'sragan' ? 'Sragan' : 'Luar' }}
                                    </span>
                                    @if($warga->dusun === 'sragan')
                                        <span class="text-[10px] text-gray-400">RT{{ sprintf('%02d', $warga->rt) }}/RW{{ sprintf('%02d', $warga->rw) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Total Tagihan (Fokus Utama) --}}
                        <div class="px-4 py-2">
                            <div class="flex items-baseline justify-between">
                                <span class="text-[10px] text-gray-400 font-semibold uppercase">Total Tagihan</span>
                                <span class="font-mono text-lg font-bold text-[#36656B]">Rp {{ number_format($totalHarus, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Info Pendukung (1 baris ringkas) --}}
                        <div class="flex items-center gap-2 px-4 py-1.5 text-[11px] text-gray-500">
                            <span class="font-mono">{{ number_format($pemakaian) }} m³</span>
                            @if($saldoAwal != 0)
                                <span class="text-gray-300">•</span>
                                <span>Saldo: <span class="font-mono font-semibold {{ $saldoAwal > 0 ? 'text-red-500' : 'text-[#75B06F]' }}">Rp {{ number_format(abs($saldoAwal), 0, ',', '.') }}</span></span>
                            @endif
                        </div>

                        {{-- Status Pembayaran --}}
                        <div class="mx-4 mt-2 mb-3 p-2.5 rounded-xl bg-[#F0F8A4]/20 border border-[#DAD887]/30">
                            <div class="flex items-center justify-between text-xs">
                                <div>
                                    <span class="text-gray-500 block text-[10px] uppercase font-semibold">Dibayar</span>
                                    <span class="font-mono font-semibold text-[#75B06F]">Rp {{ number_format($dibayar, 0, ',', '.') }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-gray-500 block text-[10px] uppercase font-semibold">Sisa</span>
                                    <span class="font-mono font-bold {{ $sisaSaldo > 0 ? 'text-red-600' : ($sisaSaldo < 0 ? 'text-[#75B06F]' : 'text-gray-400') }}">
                                        Rp {{ number_format(abs($sisaSaldo), 0, ',', '.') }}
                                        @if($sisaSaldo > 0)
                                            <span class="text-[9px] text-red-500">(Kurang)</span>
                                        @elseif($sisaSaldo < 0)
                                            <span class="text-[9px] text-[#75B06F]">(Lebih)</span>
                                        @else
                                            <span class="text-[9px] text-gray-400">(Lunas)</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Action Button --}}
                        <div class="p-4 pt-0">
                            <button onclick="document.getElementById('modal-mobile-{{ $warga->pencatatan->id }}').showModal()" 
                                class="w-full inline-flex items-center justify-center gap-1.5 bg-[#36656B] hover:bg-[#2a4f54] text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Bayar
                            </button>
                        </div>
                    </div>

                    {{-- Modal Pembayaran Mobile (tetap sama, tidak diubah) --}}
                    <dialog id="modal-mobile-{{ $warga->pencatatan->id }}" class="rounded-2xl p-0 shadow-2xl backdrop:bg-black/50 w-full max-w-md">
                        <div class="bg-white p-6">
                            <h3 class="text-lg font-bold text-[#36656B] mb-4">Input Pembayaran</h3>
                            
                            <div class="bg-[#F0F8A4]/30 rounded-xl p-4 mb-6 space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Warga:</span>
                                    <span class="font-semibold">{{ $warga->nama }}</span>
                                </div>
                                
                                <div class="border-t border-[#DAD887]/40 pt-2 mt-2 space-y-1 text-xs text-gray-600">
                                    <div class="flex justify-between">
                                        <span>Pemakaian:</span>
                                        <span class="font-mono">{{ number_format($pemakaian) }} m³</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Tarif per m³:</span>
                                        <span class="font-mono">Rp {{ number_format($warga->pencatatan->harga_per_meter) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Dana Meter:</span>
                                        <span class="font-mono">Rp {{ number_format($warga->pencatatan->dana_meter) }}</span>
                                    </div>
                                    <div class="flex justify-between font-semibold text-[#36656B] pt-1 border-t border-[#DAD887]/50">
                                        <span>Subtotal Tagihan:</span>
                                        <span class="font-mono">Rp {{ number_format($tagihan) }}</span>
                                    </div>
                                </div>

                                <div class="flex justify-between pt-2">
                                    <span class="text-gray-600">Saldo Bulan Lalu:</span>
                                    <span class="font-mono {{ $saldoAwal > 0 ? 'text-red-500' : 'text-[#75B06F]' }}">
                                        Rp {{ number_format(abs($saldoAwal)) }} {{ $saldoAwal < 0 ? '(Kredit)' : '' }}
                                    </span>
                                </div>
                                <div class="border-t border-[#DAD887] pt-2 flex justify-between text-base">
                                    <span class="font-bold text-[#36656B]">Total Harus Dibayar:</span>
                                    <span class="font-bold font-mono text-[#36656B]">Rp {{ number_format($totalHarus) }}</span>
                                </div>
                            </div>

                            <form action="{{ route('pembayaran.update', $warga->pencatatan->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="mb-4">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Jumlah Dibayarkan (Rp)</label>
                                    <input type="number" name="dibayar" value="{{ $dibayar ?: $totalHarus }}" min="0" required
                                        class="w-full px-4 py-3 bg-white border border-[#DAD887] text-gray-800 rounded-xl text-lg font-mono font-semibold focus:outline-none focus:ring-2 focus:ring-[#36656B] transition-all">
                                    <p class="text-[10px] text-gray-400 mt-1">*Jika dibayar lebih, sisa akan otomatis jadi kredit bulan depan.</p>
                                </div>
                                <div class="flex gap-3">
                                    <button type="button" onclick="document.getElementById('modal-mobile-{{ $warga->pencatatan->id }}').close()"
                                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl text-sm transition">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="flex-1 bg-[#36656B] hover:bg-[#2a4f54] text-white font-semibold py-2.5 rounded-xl text-sm transition shadow-sm">
                                        Simpan Pembayaran
                                    </button>
                                </div>
                            </form>
                        </div>
                    </dialog>
                @endif
            @empty
                <div class="text-center py-8 text-gray-400 text-sm bg-gray-50 border border-dashed rounded-xl">
                    Belum ada data warga.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
