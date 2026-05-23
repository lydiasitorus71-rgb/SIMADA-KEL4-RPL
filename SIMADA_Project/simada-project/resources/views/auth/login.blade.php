@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center animate-fade-in-up py-10">
    <div class="solar-card rounded overflow-hidden w-full max-w-2xl">
        <div class="px-6 md:px-10 py-10">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded bg-solarized-blue mb-4">
                    <svg class="w-8 h-8 text-solarized-base3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                </div>
                <h2 class="text-3xl font-extrabold text-solarized-base01">
                    Selamat Datang
                </h2>
                <p class="mt-2 text-base text-solarized-base1">Pilih role untuk masuk ke sistem e-Tendering</p>
            </div>

            <!-- Quick Access Cards -->
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @php
                        $roles = [
                            'Admin' => ['icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'inner' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                            'PA/KPA' => ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'inner' => ''],
                            'PPK' => ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'inner' => ''],
                            'Pokja' => ['icon' => 'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z', 'inner' => ''],
                            'Pejabat Pengadaan' => ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'inner' => ''],
                        ];
                    @endphp

                    @foreach($roles as $roleName => $svg)
                        @php
                            $roleUser = $users->where('role', $roleName)->first();
                        @endphp
                        @if($roleUser)
                            <button type="submit" name="user_id" value="{{ $roleUser->user_id }}" 
                                class="group relative flex flex-col items-center justify-center p-5 bg-solarized-base3 border border-solarized-base1 rounded hover:border-solarized-blue hover:bg-solarized-base2 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-solarized-blue focus:ring-offset-2 overflow-hidden shadow-sm hover:shadow-md h-full">
                                <div class="absolute inset-0 bg-solarized-blue opacity-0 group-hover:opacity-5 transition-opacity duration-300"></div>
                                <div class="w-14 h-14 rounded-full bg-solarized-base2 flex items-center justify-center text-solarized-blue group-hover:scale-110 transition-transform duration-300 mb-3 border border-solarized-base1 group-hover:border-solarized-blue">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $svg['icon'] }}"></path>
                                        @if($svg['inner'])
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $svg['inner'] }}"></path>
                                        @endif
                                    </svg>
                                </div>
                                <span class="text-sm md:text-base font-bold text-solarized-base00 group-hover:text-solarized-blue transition-colors text-center">{{ $roleName }}</span>
                                <span class="text-xs text-solarized-base1 mt-1 truncate w-full text-center opacity-70 group-hover:opacity-100 transition-opacity">{{ $roleUser->username }}</span>
                            </button>
                        @endif
                    @endforeach
                </div>
            </form>

            <!-- Specific User Select (Fallback) -->
            <div class="pt-6 mt-8 border-t border-solarized-base2">
                <details class="group cursor-pointer">
                    <summary class="flex items-center justify-between font-medium text-sm text-solarized-base0 outline-none hover:text-solarized-blue transition-colors p-2 -mx-2 rounded hover:bg-solarized-base2">
                        <span class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span>Atau pilih pengguna spesifik / dummy lainnya...</span>
                        </span>
                        <svg class="w-5 h-5 transition-transform duration-300 group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </summary>
                    
                    <form action="{{ route('login.post') }}" method="POST" class="mt-4 flex flex-col sm:flex-row gap-3">
                        @csrf
                        <div class="flex-grow">
                            <select name="user_id" required 
                                class="block w-full px-4 py-2.5 bg-solarized-base3 border border-solarized-base1 rounded focus:ring-2 focus:ring-solarized-blue focus:border-solarized-blue transition-all outline-none text-solarized-base00 text-sm">
                                <option value="">-- Pilih Akun --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->user_id }}">
                                        {{ $user->username }} ({{ $user->role }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn-primary px-6 py-2.5 rounded shadow-soft font-bold transition-transform hover:scale-105 flex items-center justify-center space-x-2 whitespace-nowrap">
                            <span>Masuk</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </form>
                </details>
            </div>

        </div>
        <div class="px-8 py-4 bg-solarized-base2 border-t border-solarized-base1 text-center">
            <p class="text-sm font-medium text-solarized-base1 flex items-center justify-center space-x-2">
                <svg class="w-4 h-4 text-solarized-yellow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span>Environment Testing - Password Disabled</span>
            </p>
        </div>
    </div>
</div>

@push('scripts')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    
    /* Smooth height transition for details element */
    details > summary { list-style: none; }
    details > summary::-webkit-details-marker { display: none; }
</style>
@endpush
@endsection
