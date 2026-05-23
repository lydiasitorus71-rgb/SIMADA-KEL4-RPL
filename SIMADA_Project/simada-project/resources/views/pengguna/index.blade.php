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
            <h1 class="text-3xl font-extrabold text-solarized-base01">Daftar Pengguna</h1>
            <p class="mt-1 text-base text-solarized-base1">Kelola data pengguna sistem e-Tendering.</p>
        </div>
        <a href="{{ route('pengguna.create') }}" class="btn-primary px-5 py-2.5 rounded shadow-soft font-medium inline-flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            <span>Tambah Pengguna</span>
        </a>
    </div>

    <!-- Success messages are now handled by SweetAlert2 -->

    <div class="solar-card rounded overflow-hidden">
        <table id="penggunaTable" class="w-full text-left">
            <thead class="bg-solarized-base2 border-b border-solarized-base1">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold text-solarized-base01 uppercase tracking-wider">Username</th>
                    <th class="px-6 py-4 text-sm font-semibold text-solarized-base01 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-4 text-sm font-semibold text-solarized-base01 uppercase tracking-wider">Personil Terkait</th>
                    <th class="px-6 py-4 text-sm font-semibold text-solarized-base01 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-sm font-semibold text-solarized-base01 uppercase tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-solarized-base2">
                @forelse($penggunas as $user)
                <tr class="hover:bg-solarized-base2 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-base font-semibold text-solarized-base00">{{ $user->username }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-solarized-base3 text-solarized-blue border border-solarized-blue">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-solarized-base00 font-medium">
                        {{ $user->personil ? $user->personil->nama_lengkap : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($user->status_aktif)
                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-solarized-base3 text-solarized-green border border-solarized-green">Aktif</span>
                        @else
                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-solarized-base3 text-solarized-red border border-solarized-red">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <a href="{{ route('pengguna.edit', $user->user_id) }}" class="text-solarized-blue hover:text-solarized-base00 transition-colors mr-3">Edit</a>
                        <button onclick="deletePengguna({{ $user->user_id }})" class="text-solarized-red hover:text-solarized-orange transition-colors">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-solarized-base1">Belum ada data pengguna.</td>
                </tr>
                @endforelse
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
    $('#penggunaTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        },
        "dom": '<"flex flex-col sm:flex-row justify-between items-center mb-4 gap-4"lf>rt<"flex flex-col sm:flex-row justify-between items-center mt-4 gap-4"ip>',
    });

    @if(session('success'))
    Swal.fire({
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        icon: 'success',
        confirmButtonColor: '#268bd2',
        background: '#fdf6e3',
        color: '#657b83',
        timer: 3000,
        timerProgressBar: true
    });
    @endif
});

function deletePengguna(id) {
    Swal.fire({
        title: 'Hapus Pengguna?',
        text: "Apakah Anda yakin ingin menghapus pengguna ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc322f',
        cancelButtonColor: '#93a1a1',
        background: '#fdf6e3',
        color: '#657b83',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.action = `{{ url('pengguna') }}/${id}`;
            form.method = 'POST';
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    })
}
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
