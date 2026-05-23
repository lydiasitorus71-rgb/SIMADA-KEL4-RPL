@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto animate-fade-in-up">
    <div class="flex items-center mb-6 space-x-4">
        <a href="{{ route('personil.index') }}" class="text-solarized-base1 hover:text-solarized-blue transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-3xl font-extrabold text-solarized-base01">Edit Personil</h1>
    </div>

    <div class="solar-card rounded overflow-hidden">
        <div class="px-6 py-8 sm:p-10">
            <form action="{{ route('personil.update', $personil->personil_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-y-6">
                    <div>
                        <label for="nama_lengkap" class="block text-base font-semibold text-solarized-base00 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" required value="{{ old('nama_lengkap', $personil->nama_lengkap) }}"
                            class="block w-full px-4 py-3 bg-solarized-base3 border @error('nama_lengkap') border-solarized-red @else border-solarized-base1 @enderror rounded focus:ring-2 focus:ring-solarized-blue focus:border-solarized-blue transition-all outline-none text-solarized-base00 text-lg">
                        @error('nama_lengkap')
                            <p class="mt-2 text-sm text-solarized-red font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="nip" class="block text-base font-semibold text-solarized-base00 mb-1">NIP</label>
                        <input type="text" name="nip" id="nip" value="{{ old('nip', $personil->nip) }}"
                            class="block w-full px-4 py-3 bg-solarized-base3 border @error('nip') border-solarized-red @else border-solarized-base1 @enderror rounded focus:ring-2 focus:ring-solarized-blue focus:border-solarized-blue transition-all outline-none text-solarized-base00 text-lg">
                        @error('nip')
                            <p class="mt-2 text-sm text-solarized-red font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="detail_skp" class="block text-base font-semibold text-solarized-base00 mb-1">Detail SKP</label>
                        <textarea name="detail_skp" id="detail_skp" rows="4"
                            class="block w-full px-4 py-3 bg-solarized-base3 border @error('detail_skp') border-solarized-red @else border-solarized-base1 @enderror rounded focus:ring-2 focus:ring-solarized-blue focus:border-solarized-blue transition-all outline-none text-solarized-base00 text-lg">{{ old('detail_skp', $personil->detail_skp) }}</textarea>
                        @error('detail_skp')
                            <p class="mt-2 text-sm text-solarized-red font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="skp_limit" class="block text-base font-semibold text-solarized-base00 mb-1">Limit SKP</label>
                        <input type="number" name="skp_limit" id="skp_limit" value="{{ old('skp_limit', $personil->skp_limit) }}" min="1"
                            class="block w-full px-4 py-3 bg-solarized-base3 border @error('skp_limit') border-solarized-red @else border-solarized-base1 @enderror rounded focus:ring-2 focus:ring-solarized-blue focus:border-solarized-blue transition-all outline-none text-solarized-base00 text-lg">
                        <p class="mt-1 text-sm text-solarized-base1">Maksimal jumlah paket yang dapat dikerjakan personil ini.</p>
                        @error('skp_limit')
                            <p class="mt-2 text-sm text-solarized-red font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-10 pt-6 border-t border-solarized-base1 flex items-center justify-end space-x-4">
                    <a href="{{ route('personil.index') }}" class="text-base font-medium text-solarized-base1 hover:text-solarized-base00 transition-colors">Batal</a>
                    <button type="submit" class="btn-primary px-8 py-3 rounded shadow-soft font-bold text-base tracking-wide">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fadeInUp 0.4s ease-out forwards; }
</style>
@endpush
@endsection
