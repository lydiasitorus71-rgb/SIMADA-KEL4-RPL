@extends('layouts.app')

@section('content')
<div class="px-4 py-6 sm:px-0 animate-fade-in-up">
    <div class="solar-card rounded overflow-hidden">
        <div class="px-6 py-8 sm:p-10 text-center">
            <h1 class="text-4xl font-extrabold text-solarized-base01 mb-4">
                Selamat Datang, {{ auth()->user()->username }}!
            </h1>
            <p class="text-xl text-solarized-base00 mb-8">
                Anda login sebagai <span class="font-bold text-solarized-blue">{{ auth()->user()->role }}</span>.
            </p>
            
            <div class="inline-flex justify-center items-center w-24 h-24 rounded-full bg-solarized-base2 mb-6">
                <svg class="w-12 h-12 text-solarized-base01" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            
            <p class="text-base text-solarized-base1 max-w-2xl mx-auto mb-8">
                Gunakan menu navigasi di atas untuk mengelola data sesuai dengan hak akses Anda dalam ekosistem e-Tendering SIMADA.
            </p>
            @if(in_array(auth()->user()->role, ['Admin', 'PPK', 'PA/KPA']))
            <div class="mt-8 mb-12">
                <h2 class="text-2xl font-bold text-solarized-base01 mb-6 text-left border-b border-solarized-base2 pb-2">Ringkasan Eksekusi SIMADA (Internal)</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-solarized-base3 border border-solarized-base2 p-6 rounded shadow-soft flex flex-col items-center justify-center">
                        <span class="text-sm font-semibold text-solarized-base1 uppercase tracking-wider mb-2">Total Paket</span>
                        <span class="text-4xl font-extrabold text-solarized-blue">{{ $totalPaket }}</span>
                    </div>
                    <div class="bg-solarized-base3 border border-solarized-base2 p-6 rounded shadow-soft flex flex-col items-center justify-center">
                        <span class="text-sm font-semibold text-solarized-base1 uppercase tracking-wider mb-2">Total Pagu (Internal)</span>
                        <span class="text-3xl font-extrabold text-solarized-green">Rp {{ number_format($totalPaguLokal, 0, ',', '.') }}</span>
                    </div>
                    <div class="bg-solarized-base3 border border-solarized-base2 p-6 rounded shadow-soft flex flex-col items-center justify-center">
                        <span class="text-sm font-semibold text-solarized-base1 uppercase tracking-wider mb-2">Total Personil</span>
                        <span class="text-4xl font-extrabold text-solarized-violet">{{ $totalPersonil }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-solarized-base2">
                <h2 class="text-2xl font-bold text-solarized-base01 mb-4 text-left">Analisis Anggaran Nasional (Sumber: API Inaproc)</h2>
                <div class="w-full" style="height: 400px;">
                    <canvas id="opdChart"></canvas>
                </div>
            </div>
            @else
            <div class="mt-8 mb-12">
                <h2 class="text-2xl font-bold text-solarized-base01 mb-6 text-left border-b border-solarized-base2 pb-2">Daftar Penugasan Aktif Anda</h2>
                <div class="overflow-x-auto rounded-lg border border-solarized-base2 shadow-sm text-left">
                    <table class="min-w-full divide-y divide-solarized-base2">
                        <thead class="bg-solarized-base2/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-solarized-base01 uppercase tracking-wider">Nama Paket</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-solarized-base01 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-solarized-base01 uppercase tracking-wider">Timeline Penugasan</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-solarized-base01 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-solarized-base3 divide-y divide-solarized-base2">
                            @forelse($assignedPakets as $paket)
                                <tr class="hover:bg-solarized-base2/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-solarized-base01">{{ $paket->nama_paket }}</div>
                                        <div class="text-xs text-solarized-base1 mt-1">{{ $paket->opd_pemilik }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-solarized-cyan/10 text-solarized-cyan border border-solarized-cyan/20">
                                            {{ $paket->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-solarized-base00">
                                        {{ \Carbon\Carbon::parse($paket->personils->first()->pivot->tanggal_mulai_tugas)->format('d M Y') }} 
                                        s/d 
                                        {{ \Carbon\Carbon::parse($paket->personils->first()->pivot->tanggal_selesai_tugas)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('paket-pengadaan.show', $paket->paket_id) }}" class="inline-flex items-center px-3 py-1.5 border border-solarized-blue text-solarized-blue bg-solarized-base3 hover:bg-solarized-base2 rounded transition-colors">
                                            Detail Paket
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-solarized-base1">
                                        Anda belum memiliki penugasan aktif saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', async function() {
        try {
            const token = 'TOKEN-SIMADA-KEBUMEN-2026';
            const headers = { 'Authorization': 'Bearer ' + token, 'Accept': 'application/json' };
            
            const [paguRes, realisasiRes] = await Promise.all([
                fetch('/api/v1/rup/pagu-opd', { headers }),
                fetch('/api/v1/tender/realisasi-kontrak', { headers })
            ]);

            const paguDataRaw = await paguRes.json();
            const realisasiDataRaw = await realisasiRes.json();

            const opds = paguDataRaw.map(item => item.opd);
            const paguValues = paguDataRaw.map(item => item.pagu);
            
            const realisasiValues = opds.map(opd => {
                const match = realisasiDataRaw.find(item => item.opd === opd);
                return match ? match.realisasi : 0;
            });

            const ctx = document.getElementById('opdChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: opds,
                    datasets: [
                        {
                            label: 'Rencana Anggaran (Pagu)',
                            data: paguValues,
                            backgroundColor: '#268bd2',
                            borderColor: '#1f77b4',
                            borderWidth: 1
                        },
                        {
                            label: 'Realisasi Anggaran (Kontrak)',
                            data: realisasiValues,
                            backgroundColor: '#2aa198',
                            borderColor: '#21867c',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000000000).toFixed(1) + ' M';
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error fetching dashboard data:', error);
        }
    });
</script>
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fadeInUp 0.4s ease-out forwards; }
</style>
@endpush
@endsection
