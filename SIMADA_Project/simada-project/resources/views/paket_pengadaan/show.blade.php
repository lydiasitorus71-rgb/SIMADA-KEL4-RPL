@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-fade-in-up">
    <!-- Package Details -->
    <div class="lg:col-span-2 space-y-8">
        <div class="solar-card rounded overflow-hidden">
            <div class="px-6 py-5 border-b border-solarized-base2 flex justify-between items-center bg-solarized-base3">
                <h3 class="text-xl font-bold text-solarized-base01">Detail Paket Pengadaan</h3>
                <a href="{{ route('paket-pengadaan.index') }}" class="text-base font-medium text-solarized-blue hover:text-solarized-base01 transition-colors inline-flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Kembali</span>
                </a>
            </div>
            <div class="px-6 py-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-8">
                    <div class="sm:col-span-2">
                        <dt class="text-base font-medium text-solarized-base1 mb-1">Nama Paket</dt>
                        <dd class="text-lg font-semibold text-solarized-base01">{{ $paket->nama_paket }}</dd>
                    </div>
                    <div>
                        <dt class="text-base font-medium text-solarized-base1 mb-1">Metode & Status</dt>
                        <dd class="flex space-x-2">
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded bg-solarized-base2 text-solarized-base01 border border-solarized-base1">{{ $paket->metode_pengadaan }}</span>
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded {{ $paket->status == 'Penetapan Pemenang' ? 'bg-solarized-green text-solarized-base3' : ($paket->status == 'Review' ? 'bg-solarized-yellow text-solarized-base3' : 'bg-solarized-base1 text-solarized-base3') }}">{{ $paket->status }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-base font-medium text-solarized-base1 mb-1">OPD Pemilik</dt>
                        <dd class="text-lg text-solarized-base01 font-medium">{{ $paket->opd_pemilik }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-base font-medium text-solarized-base1 mb-1">Pagu Anggaran</dt>
                        <dd class="text-2xl font-bold text-solarized-blue">Rp {{ number_format($paket->pagu_anggaran, 2, ',', '.') }}</dd>
                    </div>
                </dl>

                @if(in_array(auth()->user()->role, ['Pokja', 'Pejabat Pengadaan']) && $paket->status === 'Persiapan')
                <div class="mt-6 pt-6 border-t border-solarized-base2">
                    <form action="{{ route('paket-pengadaan.ajukan-review', $paket->paket_id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="rekomendasi_pemenang" class="block text-sm font-semibold text-solarized-base00 mb-1">Rekomendasi Pemenang</label>
                                <input type="text" id="rekomendasi_pemenang" name="rekomendasi_pemenang" required 
                                    class="block w-full px-3 py-2 bg-solarized-base3 border border-solarized-base1 rounded focus:ring-2 focus:ring-solarized-blue outline-none text-solarized-base00">
                            </div>
                            <div>
                                <label for="harga_penawaran" class="block text-sm font-semibold text-solarized-base00 mb-1">Harga Penawaran (Rp)</label>
                                <input type="number" id="harga_penawaran" name="harga_penawaran" required min="0" step="0.01"
                                    class="block w-full px-3 py-2 bg-solarized-base3 border border-solarized-base1 rounded focus:ring-2 focus:ring-solarized-blue outline-none text-solarized-base00">
                            </div>
                        </div>
                        <button type="submit" class="w-full sm:w-auto btn-primary px-4 py-2 rounded font-medium shadow-soft inline-flex items-center justify-center space-x-2 bg-solarized-orange hover:bg-[#a63d11]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            <span>Ajukan Rekomendasi Review</span>
                        </button>
                    </form>
                </div>
                @endif

                <div class="mt-8 pt-6 border-t border-solarized-base2 flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                    @if(in_array(auth()->user()->role, ['Admin', 'PPK']))
                        @if($paket->status === 'Penetapan Pemenang' && $paket->realisasi_kontrak)
                            <a href="{{ route('paket-pengadaan.cetak', ['id' => $paket->paket_id, 'jenis' => 'penetapan']) }}" target="_blank" class="btn-primary text-center px-4 py-2 rounded font-medium shadow-soft inline-flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span>Unduh BA Penetapan Pemenang (PDF)</span>
                            </a>
                            <a href="{{ route('paket-pengadaan.cetak', ['id' => $paket->paket_id, 'jenis' => 'pengumuman']) }}" target="_blank" class="bg-solarized-base2 hover:bg-solarized-base1 text-solarized-base01 text-center px-4 py-2 rounded font-medium border border-solarized-base1 shadow-soft inline-flex items-center justify-center space-x-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <span>Unduh BA Pengumuman Pemenang (PDF)</span>
                            </a>
                        @else
                            <div class="w-full text-center p-3 bg-solarized-base2 rounded border border-solarized-base1 text-solarized-base00 italic">
                                Dokumen Berita Acara belum dapat diunduh (Menunggu penetapan pemenang oleh PPK).
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Assigned Personil List -->
        <div class="solar-card rounded overflow-hidden">
            <div class="px-6 py-5 border-b border-solarized-base2 bg-solarized-base3">
                <h3 class="text-xl font-bold text-solarized-base01">Personil Ditugaskan</h3>
            </div>
            <div class="p-0">
                <ul id="assigned-list" class="divide-y divide-solarized-base2">
                    @forelse($paket->personils as $personil)
                    <li class="px-6 py-5 hover:bg-solarized-base2 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded bg-solarized-blue flex items-center justify-center text-solarized-base3 font-bold text-lg">
                                    {{ substr($personil->nama_lengkap, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-lg font-semibold text-solarized-base01">{{ $personil->nama_lengkap }}</p>
                                    <div class="text-sm text-solarized-base1 mt-0.5 flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-solarized-base1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>{{ \Carbon\Carbon::parse($personil->pivot->tanggal_mulai_tugas)->format('d M Y') }} - {{ \Carbon\Carbon::parse($personil->pivot->tanggal_selesai_tugas)->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            @if(auth()->check() && in_array(auth()->user()->role, ['Admin', 'PPK']) && $paket->status !== 'Penetapan Pemenang')
                            <div class="flex space-x-3">
                                <button type="button" class="p-2 text-solarized-blue hover:bg-solarized-base2 rounded transition-colors focus:outline-none" onclick="openEditModal({{ $personil->personil_id }}, '{{ $personil->pivot->tanggal_mulai_tugas }}', '{{ $personil->pivot->tanggal_selesai_tugas }}')" title="Edit Dates">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button type="button" class="p-2 text-solarized-red hover:bg-solarized-base2 rounded transition-colors focus:outline-none" onclick="removePersonil({{ $personil->personil_id }})" title="Remove">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                            @endif
                        </div>
                    </li>
                    @empty
                    <li class="px-6 py-8 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded bg-solarized-base2 mb-4">
                            <svg class="w-8 h-8 text-solarized-base1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <p class="text-solarized-base00 font-medium">Belum ada personil yang ditugaskan.</p>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Assignment Form & Winner Form (Admin/PPK Only) -->
    @if(auth()->check() && in_array(auth()->user()->role, ['Admin', 'PPK']))
    <div class="lg:col-span-1 space-y-8">
        
        @if($paket->status === 'Review')
        <div class="solar-card rounded overflow-hidden">
            <div class="px-6 py-6 border-b border-solarized-base2 bg-solarized-green">
                <h3 class="text-lg font-bold text-solarized-base3 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Form Penetapan Pemenang</span>
                </h3>
            </div>
            <div class="p-6 bg-solarized-base3">
                <form action="{{ route('paket-pengadaan.tetapkan-pemenang', $paket->paket_id) }}" method="POST">
                    @csrf
                    <div class="space-y-5">
                        <div>
                            <label for="pemenang_tender" class="block text-base font-semibold text-solarized-base00 mb-1">Nama Pemenang Tender</label>
                            <input type="text" id="pemenang_tender" name="pemenang_tender" required 
                                value="{{ old('pemenang_tender', $paket->rekomendasi_pemenang) }}"
                                class="block w-full px-4 py-2.5 bg-solarized-base3 border border-solarized-base1 rounded focus:ring-2 focus:ring-solarized-green outline-none text-solarized-base00">
                        </div>
                        
                        <div>
                            <label for="realisasi_kontrak" class="block text-base font-semibold text-solarized-base00 mb-1">Nilai Realisasi Kontrak (Rp)</label>
                            <input type="number" id="realisasi_kontrak" name="realisasi_kontrak" required min="0" step="0.01" 
                                value="{{ old('realisasi_kontrak', $paket->harga_penawaran) }}"
                                class="block w-full px-4 py-2.5 bg-solarized-base3 border border-solarized-base1 rounded focus:ring-2 focus:ring-solarized-green outline-none text-solarized-base00">
                        </div>

                        <button type="submit" class="w-full bg-solarized-green hover:bg-[#738400] text-solarized-base3 py-3 px-4 rounded shadow-soft font-bold tracking-wide mt-2 transition-colors">
                            Tetapkan Pemenang
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif

        @if($paket->status === 'Penetapan Pemenang')
        <div class="solar-card rounded overflow-hidden">
            <div class="px-6 py-6 border-b border-solarized-base2 bg-solarized-base3">
                <h3 class="text-lg font-bold text-solarized-base01">Pemenang Ditetapkan</h3>
            </div>
            <div class="p-6 bg-solarized-base3">
                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-solarized-base1">Pemenang Tender</dt>
                        <dd class="text-lg font-bold text-solarized-base01">{{ $paket->pemenang_tender }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-solarized-base1">Nilai Kontrak</dt>
                        <dd class="text-lg font-bold text-solarized-green">Rp {{ number_format($paket->realisasi_kontrak, 2, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
        @endif

        <div class="solar-card rounded overflow-hidden sticky top-24">
            <div class="px-6 py-6 border-b border-solarized-base2 bg-solarized-blue">
                <h3 class="text-lg font-bold text-solarized-base3 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    <span>Tugaskan Personil</span>
                </h3>
            </div>
            <div class="p-6 bg-solarized-base3">
                @if($paket->status === 'Penetapan Pemenang')
                    <div class="w-full text-center p-4 bg-solarized-base2 rounded border border-solarized-base1 text-solarized-base00">
                        <span class="block font-bold mb-2">🔒 Susunan Pokja/Pejabat Pengadaan telah dikunci (Final)</span>
                        <p class="text-sm">Anda tidak dapat lagi mengubah susunan panitia karena paket ini sudah memiliki pemenang yang ditetapkan.</p>
                    </div>
                @else
                    <form id="assign-form">
                        <div class="space-y-5">
                            <div>
                                <label for="personil_id" class="block text-base font-semibold text-solarized-base00 mb-1">Pilih Personil</label>
                                <select id="personil_id" name="personil_id" required class="mt-1 block w-full rounded border-solarized-base1 bg-solarized-base3 text-solarized-base00">
                                    <option value="">-- Pilih --</option>
                                    @foreach($personils as $p)
                                        <option value="{{ $p->personil_id }}" {{ $p->sisaSkp <= 0 ? 'disabled' : '' }}>
                                            {{ $p->nama_lengkap }} (Sisa SKP: {{ max(0, $p->sisaSkp) }} Paket)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="tanggal_mulai_tugas" class="block text-base font-semibold text-solarized-base00 mb-1">Tanggal Mulai</label>
                                <input type="date" id="tanggal_mulai_tugas" name="tanggal_mulai_tugas" required 
                                    class="block w-full px-4 py-2.5 bg-solarized-base3 border border-solarized-base1 rounded focus:ring-2 focus:ring-solarized-blue focus:border-solarized-blue outline-none text-solarized-base00">
                            </div>

                            <div>
                                <label for="tanggal_selesai_tugas" class="block text-base font-semibold text-solarized-base00 mb-1">Tanggal Selesai</label>
                                <input type="date" id="tanggal_selesai_tugas" name="tanggal_selesai_tugas" required 
                                    class="block w-full px-4 py-2.5 bg-solarized-base3 border border-solarized-base1 rounded focus:ring-2 focus:ring-solarized-blue focus:border-solarized-blue outline-none text-solarized-base00">
                            </div>

                            <button type="submit" class="w-full btn-primary py-3 px-4 rounded shadow-soft font-bold tracking-wide mt-2">
                                Tambah Penugasan
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Edit Modal -->
<div id="editModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-solarized-base01/70 transition-opacity" aria-hidden="true" onclick="closeEditModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom solar-card rounded text-left overflow-hidden shadow-soft transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full animate-fade-in-up">
            <div class="px-6 py-5 border-b border-solarized-base2 bg-solarized-base2 flex justify-between items-center">
                <h3 class="text-lg font-bold text-solarized-base01" id="modal-title">Edit Tanggal Penugasan</h3>
                <button type="button" onclick="closeEditModal()" class="text-solarized-base1 hover:text-solarized-base01 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 bg-solarized-base3">
                <form id="edit-form">
                    <input type="hidden" id="edit_personil_id">
                    <div class="space-y-5">
                        <div>
                            <label for="edit_tanggal_mulai_tugas" class="block text-base font-semibold text-solarized-base00 mb-1">Tanggal Mulai</label>
                            <input type="date" id="edit_tanggal_mulai_tugas" name="tanggal_mulai_tugas" required 
                                class="block w-full px-4 py-2.5 bg-solarized-base3 border border-solarized-base1 rounded focus:ring-2 focus:ring-solarized-blue outline-none text-solarized-base00">
                        </div>
                        <div>
                            <label for="edit_tanggal_selesai_tugas" class="block text-base font-semibold text-solarized-base00 mb-1">Tanggal Selesai</label>
                            <input type="date" id="edit_tanggal_selesai_tugas" name="tanggal_selesai_tugas" required 
                                class="block w-full px-4 py-2.5 bg-solarized-base3 border border-solarized-base1 rounded focus:ring-2 focus:ring-solarized-blue outline-none text-solarized-base00">
                        </div>
                    </div>
                    <div class="mt-8 flex space-x-3">
                        <button type="button" onclick="closeEditModal()" class="w-1/2 py-2.5 bg-solarized-base3 border border-solarized-base1 text-solarized-base00 rounded font-semibold hover:bg-solarized-base2 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="w-1/2 btn-primary py-2.5 rounded font-semibold shadow-soft">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if(document.getElementById('personil_id')) {
        new TomSelect("#personil_id", {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });
    }

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });

    const assignForm = document.getElementById('assign-form');
    if(assignForm) {
        assignForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(assignForm);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch("{{ url('paket-pengadaan/' . $paket->paket_id . '/assign-personil') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                handleResponse(response, result, 'Berhasil menugaskan personil.');
            } catch (error) {
                Toast.fire({ icon: 'error', title: 'Gagal menghubungi server.' });
            }
        });
    }

    const editForm = document.getElementById('edit-form');
    if(editForm) {
        editForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            const personil_id = document.getElementById('edit_personil_id').value;
            const data = {
                tanggal_mulai_tugas: document.getElementById('edit_tanggal_mulai_tugas').value,
                tanggal_selesai_tugas: document.getElementById('edit_tanggal_selesai_tugas').value,
            };

            try {
                const response = await fetch("{{ url('paket-pengadaan/' . $paket->paket_id . '/personil/') }}/" + personil_id, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                handleResponse(response, result, 'Tanggal berhasil diupdate.');
            } catch (error) {
                Toast.fire({ icon: 'error', title: 'Gagal menghubungi server.' });
            }
        });
    }

    function handleResponse(response, result, successMessage) {
        if (response.ok) {
            Toast.fire({ icon: 'success', title: result.message || successMessage }).then(() => window.location.reload());
        } else if (response.status === 422) {
            let errorMsg = result.message;
            if (result.errors && result.errors.schedule) {
                errorMsg = result.errors.schedule[0];
            }
            Toast.fire({ icon: 'error', title: 'Validasi Gagal', text: errorMsg, timer: 5000 });
        } else {
            Toast.fire({ icon: 'error', title: result.message || 'Terjadi kesalahan server.' });
        }
    }
});

function openEditModal(personil_id, mulai, selesai) {
    document.getElementById('edit_personil_id').value = personil_id;
    document.getElementById('edit_tanggal_mulai_tugas').value = mulai;
    document.getElementById('edit_tanggal_selesai_tugas').value = selesai;
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

function removePersonil(personil_id) {
    Swal.fire({
        title: 'Hapus Penugasan?',
        text: "Anda yakin ingin menghapus personil ini dari paket?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc322f',
        cancelButtonColor: '#93a1a1',
        confirmButtonText: 'Ya, Hapus!',
        background: '#fdf6e3',
        color: '#657b83'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const response = await fetch("{{ url('paket-pengadaan/' . $paket->paket_id . '/personil/') }}/" + personil_id, {
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
@endpush
@endsection
