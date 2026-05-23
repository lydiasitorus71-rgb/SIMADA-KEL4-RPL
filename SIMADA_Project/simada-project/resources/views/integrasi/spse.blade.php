@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    .dataTables_wrapper { padding: 1.5rem; color: #657b83; }
    .dataTables_wrapper .dataTables_length select, .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #93a1a1; border-radius: 0.25rem; padding: 0.5rem; outline: none; transition: border-color 0.2s; background: #fdf6e3; color: #657b83;
    }
    .dataTables_wrapper .dataTables_length select:focus, .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #268bd2;
    }
    table.dataTable thead th { border-bottom: 2px solid #eee8d5; color: #586e75; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.05em; padding-bottom: 0.75rem; }
    table.dataTable tbody td { border-bottom: 1px solid #eee8d5; color: #657b83; padding: 1rem 0.5rem; }
    table.dataTable.no-footer { border-bottom: 1px solid #eee8d5; }
</style>

<div class="px-4 py-6 sm:px-0 animate-fade-in-up">
    <div class="solar-card rounded overflow-hidden">
        <div class="px-6 py-5 border-b border-solarized-base2 flex justify-between items-center bg-solarized-base3">
            <div>
                <h3 class="text-2xl leading-6 font-bold text-solarized-base01">
                    Integrasi Paket SPSE (API Inaproc)
                </h3>
                <p class="mt-2 max-w-2xl text-base text-solarized-base1">
                    Tarik data paket pengadaan terbaru dari SPSE Nasional ke dalam SIMADA.
                </p>
            </div>
            <div class="inline-flex items-center space-x-2 bg-solarized-base2 px-4 py-2 rounded text-sm text-solarized-base00 font-medium">
                <span class="w-2 h-2 rounded-full bg-solarized-green animate-pulse"></span>
                <span>API Connected</span>
            </div>
        </div>
        
        <div class="p-6">
            @if(session('success'))
                <div class="mb-6 bg-solarized-green/10 border-l-4 border-solarized-green p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-solarized-green" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-solarized-green">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-solarized-red/10 border-l-4 border-solarized-red p-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-solarized-red" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-solarized-red">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="overflow-x-auto rounded-lg border border-solarized-base2 shadow-sm bg-solarized-base3">
                <table id="spseTable" class="w-full text-left">
                    <thead class="bg-solarized-base2/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-solarized-base01 uppercase tracking-wider">
                                ID Paket SPSE
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-solarized-base01 uppercase tracking-wider">
                                Nama Paket
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-solarized-base01 uppercase tracking-wider">
                                Metode
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-solarized-base01 uppercase tracking-wider">
                                OPD Pemilik
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-solarized-base01 uppercase tracking-wider">
                                Waktu Pelaksanaan
                            </th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-solarized-base01 uppercase tracking-wider">
                                Pagu
                            </th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-solarized-base01 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-solarized-base3 divide-y divide-solarized-base2">
                        @forelse($paketSpse as $paket)
                            @php
                                $isImported = \App\Models\PaketPengadaan::where('id_referensi_eksternal', $paket['id_paket'])->exists();
                            @endphp
                            <tr class="hover:bg-solarized-base2/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-solarized-base01">
                                    {{ $paket['id_paket'] }}
                                </td>
                                <td class="px-6 py-4 text-sm text-solarized-base00">
                                    {{ $paket['nama_paket'] }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-solarized-base00">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-solarized-cyan/10 text-solarized-cyan border border-solarized-cyan/20">
                                        {{ $paket['metode_pengadaan'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-solarized-base00">
                                    {{ $paket['opd_pemilik'] ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-solarized-base00">
                                    {{ $paket['waktu_pelaksanaan'] ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-solarized-green">
                                    Rp {{ number_format($paket['pagu'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @if($isImported)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded text-sm font-medium bg-solarized-base2 text-solarized-base1 cursor-not-allowed">
                                            <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            Telah Diimport
                                        </span>
                                    @else
                                        <form action="{{ route('integrasi-spse.store') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="id_paket" value="{{ $paket['id_paket'] }}">
                                            <input type="hidden" name="nama_paket" value="{{ $paket['nama_paket'] }}">
                                            <input type="hidden" name="metode_pengadaan" value="{{ $paket['metode_pengadaan'] }}">
                                            <input type="hidden" name="pagu" value="{{ $paket['pagu'] }}">
                                            <input type="hidden" name="opd_pemilik" value="{{ $paket['opd_pemilik'] }}">
                                            <input type="hidden" name="waktu_pelaksanaan" value="{{ $paket['waktu_pelaksanaan'] }}">
                                            
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm leading-4 font-bold rounded text-solarized-base3 bg-solarized-blue hover:bg-[#1f77b4] focus:outline-none transition-colors">
                                                <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                Import ke SIMADA
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-solarized-base1">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-10 w-10 text-solarized-base1 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                        Tidak ada data paket SPSE yang tersedia saat ini.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#spseTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        },
        "dom": '<"flex flex-col sm:flex-row justify-between items-center mb-4 gap-4"lf>rt<"flex flex-col sm:flex-row justify-between items-center mt-4 gap-4"ip>',
    });
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
