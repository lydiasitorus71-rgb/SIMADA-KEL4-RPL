@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto animate-fade-in-up">
    <div class="flex items-center mb-6 space-x-4">
        <a href="{{ route('pengguna.index') }}" class="text-solarized-base1 hover:text-solarized-blue transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-3xl font-extrabold text-solarized-base01">Tambah Pengguna Baru</h1>
    </div>

    <div class="solar-card rounded overflow-hidden">
        <div class="px-6 py-8 sm:p-10">
            <form action="{{ route('pengguna.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 gap-y-6">
                    <div>
                        <label for="username" class="block text-base font-semibold text-solarized-base00 mb-1">Username</label>
                        <input type="text" name="username" id="username" required value="{{ old('username') }}"
                            class="block w-full px-4 py-3 bg-solarized-base3 border @error('username') border-solarized-red @else border-solarized-base1 @enderror rounded focus:ring-2 focus:ring-solarized-blue focus:border-solarized-blue transition-all outline-none text-solarized-base00 text-lg">
                        @error('username')
                            <p class="mt-2 text-sm text-solarized-red font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password" class="block text-base font-semibold text-solarized-base00 mb-1">Password</label>
                        <input type="password" name="password" id="password" required 
                            class="block w-full px-4 py-3 bg-solarized-base3 border @error('password') border-solarized-red @else border-solarized-base1 @enderror rounded focus:ring-2 focus:ring-solarized-blue focus:border-solarized-blue transition-all outline-none text-solarized-base00 text-lg">
                        @error('password')
                            <p class="mt-2 text-sm text-solarized-red font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="role" class="block text-base font-semibold text-solarized-base00 mb-1">Role</label>
                        <select name="role" id="role" required
                            class="block w-full px-4 py-3 bg-solarized-base3 border @error('role') border-solarized-red @else border-solarized-base1 @enderror rounded focus:ring-2 focus:ring-solarized-blue focus:border-solarized-blue transition-all outline-none text-solarized-base00 text-lg">
                            <option value="">-- Pilih Role --</option>
                            <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                            <option value="PA/KPA" {{ old('role') == 'PA/KPA' ? 'selected' : '' }}>PA/KPA</option>
                            <option value="PPK" {{ old('role') == 'PPK' ? 'selected' : '' }}>PPK</option>
                            <option value="Pokja" {{ old('role') == 'Pokja' ? 'selected' : '' }}>Pokja</option>
                            <option value="Pejabat Pengadaan" {{ old('role') == 'Pejabat Pengadaan' ? 'selected' : '' }}>Pejabat Pengadaan</option>
                        </select>
                        @error('role')
                            <p class="mt-2 text-sm text-solarized-red font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="status_aktif" class="block text-base font-semibold text-solarized-base00 mb-1">Status</label>
                        <select name="status_aktif" id="status_aktif" required
                            class="block w-full px-4 py-3 bg-solarized-base3 border @error('status_aktif') border-solarized-red @else border-solarized-base1 @enderror rounded focus:ring-2 focus:ring-solarized-blue focus:border-solarized-blue transition-all outline-none text-solarized-base00 text-lg">
                            <option value="1" {{ old('status_aktif', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ old('status_aktif') == '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status_aktif')
                            <p class="mt-2 text-sm text-solarized-red font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="personil_id" class="block text-base font-semibold text-solarized-base00 mb-1">Tautkan ke Personil (Opsional)</label>
                        <select name="personil_id" id="personil_id"
                            class="block w-full px-4 py-3 bg-solarized-base3 border @error('personil_id') border-solarized-red @else border-solarized-base1 @enderror rounded focus:ring-2 focus:ring-solarized-blue focus:border-solarized-blue transition-all outline-none text-solarized-base00 text-lg">
                            <option value="">-- Tidak Ditautkan --</option>
                            @foreach($personils as $personil)
                                <option value="{{ $personil->personil_id }}" {{ old('personil_id') == $personil->personil_id ? 'selected' : '' }}>
                                    {{ $personil->nama_lengkap }} ({{ $personil->nip ?? 'Tanpa NIP' }})
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-solarized-base1">Wajib bagi role Pokja / Pejabat Pengadaan agar dapat melihat paket penugasan mereka.</p>
                        @error('personil_id')
                            <p class="mt-2 text-sm text-solarized-red font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-10 pt-6 border-t border-solarized-base1 flex items-center justify-end space-x-4">
                    <a href="{{ route('pengguna.index') }}" class="text-base font-medium text-solarized-base1 hover:text-solarized-base00 transition-colors">Batal</a>
                    <button type="submit" class="btn-primary px-8 py-3 rounded shadow-soft font-bold text-base tracking-wide">
                        Simpan Pengguna
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
