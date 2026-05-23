@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto animate-fade-in-up">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('personil.index') }}" class="text-solarized-base1 hover:text-solarized-blue transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h1 class="text-3xl font-extrabold text-solarized-base01">Detail Personil</h1>
        </div>
        <a href="{{ route('personil.edit', $personil->personil_id) }}" class="btn-primary px-4 py-2 rounded shadow-soft font-medium inline-flex items-center space-x-2">
            <span>Edit Data</span>
        </a>
    </div>

    <div class="solar-card rounded overflow-hidden">
        <div class="px-6 py-8 sm:p-10">
            <dl class="grid grid-cols-1 gap-y-6">
                <div>
                    <dt class="text-base font-semibold text-solarized-base1 mb-1">Nama Lengkap</dt>
                    <dd class="text-xl font-bold text-solarized-base01">{{ $personil->nama_lengkap }}</dd>
                </div>
                
                <div>
                    <dt class="text-base font-semibold text-solarized-base1 mb-1">NIP</dt>
                    <dd class="text-lg text-solarized-base00">{{ $personil->nip ?? '-' }}</dd>
                </div>
                
                <div>
                    <dt class="text-base font-semibold text-solarized-base1 mb-1">Detail SKP</dt>
                    <dd class="text-lg text-solarized-base00 bg-solarized-base3 p-4 rounded border border-solarized-base2 mt-2">
                        {!! nl2br(e($personil->detail_skp ?? 'Tidak ada data SKP.')) !!}
                    </dd>
                </div>
            </dl>
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
