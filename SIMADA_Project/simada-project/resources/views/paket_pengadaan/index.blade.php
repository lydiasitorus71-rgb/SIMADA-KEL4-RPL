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
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-solarized-base01">Daftar Paket Pengadaan</h1>
            <p class="mt-1 text-base text-solarized-base1">Kelola semua tender dan paket pengadaan aktif.</p>
        </div>
        @if(auth()->check() && in_array(auth()->user()->role, ['Admin', 'PPK']))
        <a href="{{ route('paket-pengadaan.create') }}" class="btn-primary px-5 py-2.5 rounded shadow-soft font-medium inline-flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            <span>Tambah Paket</span>
        </a>
        @endif
    </div>

    <div class="solar-card rounded overflow-hidden">
        <table id="paketTable" class="w-full text-left">
            <thead class="bg-solarized-base2 border-b border-solarized-base1">
                <tr>
                    <th class="px-6 py-4">Nama Paket</th>
                    <th class="px-6 py-4">Metode</th>
                    <th class="px-6 py-4">OPD</th>
                    <th class="px-6 py-4">Pagu</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-solarized-base2">
                @foreach($pakets as $paket)
                <tr class="hover:bg-solarized-base2 transition-colors duration-200">
                    <td class="px-6 py-4">
                        <div class="text-base font-semibold text-solarized-base01">{{ $paket->nama_paket }}</div>
                        <div class="text-sm text-solarized-base1 mt-0.5">ID: #{{ $paket->paket_id }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1.5 inline-flex text-sm font-medium rounded bg-solarized-base3 text-solarized-green border border-solarized-green">
                            {{ $paket->metode_pengadaan ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-base text-solarized-base00">{{ $paket->opd_pemilik ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-base font-semibold text-solarized-blue">Rp {{ number_format($paket->pagu_anggaran, 2, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium flex justify-center space-x-2">
                        <a href="{{ route('paket-pengadaan.show', $paket->paket_id) }}" class="inline-flex items-center px-4 py-2 border border-solarized-blue text-solarized-blue bg-solarized-base3 hover:bg-solarized-base2 rounded transition-colors">
                            Detail
                        </a>
                        @if(auth()->check() && in_array(auth()->user()->role, ['Admin', 'PPK']))
                        <button onclick="deletePaket({{ $paket->paket_id }})" class="inline-flex items-center px-4 py-2 border border-solarized-red text-solarized-red bg-solarized-base3 hover:bg-solarized-base2 rounded transition-colors">
                            Hapus
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#paketTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        },
        "dom": '<"flex justify-between items-center mb-4"lf>rt<"flex justify-between items-center mt-4"ip>',
    });
    
    @if(session('success'))
    Swal.fire({
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonColor: '#4f46e5',
        timer: 3000,
        timerProgressBar: true
    });
    @endif
});

function deletePaket(id) {
    Swal.fire({
        title: 'Hapus Paket Pengadaan?',
        text: "Semua data penugasan personil pada paket ini juga akan terhapus. Anda yakin?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc322f',
        cancelButtonColor: '#93a1a1',
        background: '#fdf6e3',
        color: '#657b83',
        confirmButtonText: 'Ya, Hapus!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await fetch(`{{ url('paket-pengadaan') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const res = await response.json();
                if (response.ok) {
                    Swal.fire('Terhapus!', res.message, 'success').then(() => window.location.reload());
                } else {
                    Swal.fire('Gagal!', res.message || 'Terjadi kesalahan.', 'error');
                }
            } catch (error) {
                Swal.fire('Gagal!', 'Tidak dapat menghubungi server.', 'error');
            }
        }
    })
}
</script>
<style>
    /* Add subtle fade-in animation */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fadeInUp 0.4s ease-out forwards; }
</style>
@endpush
@endsection
