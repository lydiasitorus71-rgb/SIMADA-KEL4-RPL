@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto animate-fade-in-up">
    <div class="flex items-center mb-6 space-x-4">
        <a href="{{ route('paket-pengadaan.index') }}" class="text-solarized-base1 hover:text-solarized-blue transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-3xl font-extrabold text-solarized-base01">Tambah Paket Baru</h1>
    </div>

    <div class="solar-card rounded overflow-hidden">
        <div class="px-6 py-8 sm:p-10">
            <form id="formTambahPaket" action="{{ route('paket-pengadaan.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="nama_paket" class="block text-base font-semibold text-solarized-base00 mb-1">Nama Paket</label>
                        <input type="text" name="nama_paket" id="nama_paket" required value="{{ old('nama_paket') }}"
                            class="block w-full px-4 py-3 bg-solarized-base3 border @error('nama_paket') border-solarized-red @else border-solarized-base1 @enderror rounded focus:ring-2 focus:ring-solarized-blue focus:border-solarized-blue transition-all outline-none placeholder-solarized-base1 text-solarized-base00 text-lg" 
                            placeholder="Contoh: Pengadaan Komputer Server">
                        @error('nama_paket')
                            <p class="mt-2 text-sm text-solarized-red font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="sm:col-span-2">
                        <label for="metode_pengadaan" class="block text-base font-semibold text-solarized-base00 mb-1">Metode Pengadaan</label>
                        <select name="metode_pengadaan" id="metode_pengadaan" 
                            class="block w-full px-4 py-3 bg-solarized-base3 border @error('metode_pengadaan') border-solarized-red @else border-solarized-base1 @enderror rounded focus:ring-2 focus:ring-solarized-blue focus:border-solarized-blue transition-all outline-none text-solarized-base00 text-lg">
                            <option value="E-Purchasing" {{ old('metode_pengadaan') == 'E-Purchasing' ? 'selected' : '' }}>E-Purchasing</option>
                            <option value="Tender Cepat" {{ old('metode_pengadaan') == 'Tender Cepat' ? 'selected' : '' }}>Tender Cepat</option>
                            <option value="Tender" {{ old('metode_pengadaan') == 'Tender' ? 'selected' : '' }}>Tender</option>
                            <option value="Penunjukan Langsung" {{ old('metode_pengadaan') == 'Penunjukan Langsung' ? 'selected' : '' }}>Penunjukan Langsung</option>
                        </select>
                        @error('metode_pengadaan')
                            <p class="mt-2 text-sm text-solarized-red font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="opd_pemilik" class="block text-base font-semibold text-solarized-base00 mb-1">OPD Pemilik</label>
                        <input type="text" name="opd_pemilik" id="opd_pemilik" value="{{ old('opd_pemilik') }}"
                            class="block w-full px-4 py-3 bg-solarized-base3 border @error('opd_pemilik') border-solarized-red @else border-solarized-base1 @enderror rounded focus:ring-2 focus:ring-solarized-blue focus:border-solarized-blue transition-all outline-none text-solarized-base00 text-lg" 
                            placeholder="Dinas Kominfo">
                        @error('opd_pemilik')
                            <p class="mt-2 text-sm text-solarized-red font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="pagu_anggaran" class="block text-base font-semibold text-solarized-base00 mb-1">Pagu Anggaran (Rp)</label>
                        <input type="number" step="0.01" name="pagu_anggaran" id="pagu_anggaran" value="{{ old('pagu_anggaran') }}"
                            class="block w-full px-4 py-3 bg-solarized-base3 border @error('pagu_anggaran') border-solarized-red @else border-solarized-base1 @enderror rounded focus:ring-2 focus:ring-solarized-blue focus:border-solarized-blue transition-all outline-none text-solarized-base00 text-lg" 
                            placeholder="150000000">
                        @error('pagu_anggaran')
                            <p class="mt-2 text-sm text-solarized-red font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="waktu_pelaksanaan" class="block text-base font-semibold text-solarized-base00 mb-1">Target Timeline (Waktu Pelaksanaan)</label>
                        <input type="text" name="waktu_pelaksanaan" id="waktu_pelaksanaan" value="{{ old('waktu_pelaksanaan') }}"
                            class="block w-full px-4 py-3 bg-solarized-base3 border @error('waktu_pelaksanaan') border-solarized-red @else border-solarized-base1 @enderror rounded focus:ring-2 focus:ring-solarized-blue focus:border-solarized-blue transition-all outline-none placeholder-solarized-base1 text-solarized-base00 text-lg" 
                            placeholder="Contoh: 30 Hari Kalender atau 1 Jan - 30 Jan 2026">
                        @error('waktu_pelaksanaan')
                            <p class="mt-2 text-sm text-solarized-red font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-10 pt-6 border-t border-solarized-base1 flex items-center justify-end space-x-4">
                    <a href="{{ route('paket-pengadaan.index') }}" class="text-base font-medium text-solarized-base1 hover:text-solarized-base00 transition-colors">Batal</a>
                    <button type="submit" class="btn-primary px-8 py-3 rounded shadow-soft font-bold text-base tracking-wide">
                        Simpan Paket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('formTambahPaket').addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Pastikan data paket pengadaan sudah benar!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#268bd2',
            cancelButtonColor: '#93a1a1',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal',
            background: '#fdf6e3',
            color: '#657b83'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
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
