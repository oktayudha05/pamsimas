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
                    <h1 class="text-xl font-bold text-[#36656B]">Keuangan & Tagihan</h1>
                    <p class="text-xs text-gray-400">Rincian perhitungan dan sistem saldo berjalan</p>
                </div>
            </div>

            <form method="GET" action="{{ route('keuangan.index') }}" class="flex items-center gap-2">
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

        {{-- Tabel Keuangan --}}
        <div class="bg-white rounded-2xl border border-[#DAD887]/50 shadow-sm overflow-hidden">
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
                                            
                                            {{-- Rincian Perhitungan --}}
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

                                        <form action="{{ route('keuangan.update', $warga->pencatatan->id) }}" method="POST">
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
    </div>
</x-app-layout>