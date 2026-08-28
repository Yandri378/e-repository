@extends('layouts.admin')

@section('title', 'Upload Manual Dokumen')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-blue-50 via-white to-white">
        <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 sm:py-10 lg:px-8 space-y-8">

            {{-- ============ HERO ============ --}}
            <section
                class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 px-7 py-9 shadow-xl shadow-blue-900/20 sm:px-10 sm:py-11">
                <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-white/10 blur-2xl">
                </div>
                <div
                    class="pointer-events-none absolute -bottom-20 left-10 h-48 w-48 rounded-full bg-indigo-300/20 blur-3xl">
                </div>

                <div class="relative flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/15 ring-1 ring-white/30 backdrop-blur-sm">
                            <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="white" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="17 8 12 3 7 8" />
                                <line x1="12" y1="3" x2="12" y2="15" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-blue-100">Admin · Upload Manual
                            </p>
                            <h1 class="mt-1 text-xl font-bold text-white sm:text-2xl md:text-3xl">Upload Manual Dokumen</h1>
                            <p class="mt-1 text-sm text-blue-100/80">Input data Skripsi Mahasiswa, Penelitian Dosen, atau
                                PKM Dosen secara manual beserta file PDF & Project.</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.documents.index') }}"
                        class="hidden sm:inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2.5 text-xs font-bold text-white backdrop-blur-sm ring-1 ring-white/30 transition hover:bg-white/25">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5M12 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                </div>
            </section>

            {{-- Flash Error Messages --}}
            @if (session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 p-5 shadow-sm flex items-start gap-3">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-500 text-white shrink-0 shadow-md shadow-red-500/30">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-red-800 text-sm">Upload Gagal!</p>
                        <p class="text-sm text-red-700 mt-0.5">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 p-5 shadow-sm">
                    <div class="flex items-center gap-2 text-red-700 font-bold text-sm mb-2">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        Terdapat kesalahan pada input form:
                    </div>
                    <ul class="space-y-1 pl-6 list-disc text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ============ KATEGORI TABS ============ --}}
            <div class="flex flex-wrap items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50/80 p-2 shadow-sm">
                <a href="{{ route('admin.documents.create', ['kategori' => 'skripsi']) }}"
                    class="flex-1 min-w-[140px] inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-xs font-bold transition-all sm:text-sm {{ $kategori === 'skripsi' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-600/25 ring-2 ring-blue-600' : 'bg-white text-slate-700 hover:bg-slate-100 hover:text-blue-700 border border-slate-200' }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                        <path d="M6 12v5c3 3 9 3 12 0v-5" />
                    </svg>
                    <span>Skripsi Mahasiswa</span>
                </a>

                <a href="{{ route('admin.documents.create', ['kategori' => 'penelitian']) }}"
                    class="flex-1 min-w-[140px] inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-xs font-bold transition-all sm:text-sm {{ $kategori === 'penelitian' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-600/25 ring-2 ring-blue-600' : 'bg-white text-slate-700 hover:bg-slate-100 hover:text-blue-700 border border-slate-200' }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                    </svg>
                    <span>Penelitian Dosen</span>
                </a>

                <a href="{{ route('admin.documents.create', ['kategori' => 'pkm']) }}"
                    class="flex-1 min-w-[140px] inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-xs font-bold transition-all sm:text-sm {{ $kategori === 'pkm' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-600/25 ring-2 ring-blue-600' : 'bg-white text-slate-700 hover:bg-slate-100 hover:text-blue-700 border border-slate-200' }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="8.5" cy="7" r="4" />
                        <polyline points="17 11 19 13 23 9" />
                    </svg>
                    <span>PKM Dosen</span>
                </a>
            </div>

            {{-- ============ FORM MANUAL UPLOAD ============ --}}
            <form id="adminUploadForm" method="POST" action="{{ route('admin.documents.store') }}"
                enctype="multipart/form-data"
                class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8 space-y-6">
                @csrf
                <input type="hidden" name="kategori" value="{{ $kategori }}">

                <div class="border-b border-slate-100 pb-4">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span
                            class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-blue-700 text-xs font-bold">1</span>
                        Informasi Metadata {{ strtoupper($kategori) }}
                    </h2>
                    <p class="mt-1 text-xs text-slate-500">Lengkapi data identitas dan detail dokumen yang akan diunggah.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    {{-- Nama --}}
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Nama {{ $kategori === 'skripsi' ? 'Mahasiswa' : 'Dosen' }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" value="{{ old('nama') }}" required
                            placeholder="Masukkan nama lengkap"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>

                    {{-- NIM / NIDN --}}
                    @if ($kategori === 'skripsi')
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                                NIM <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nim" value="{{ old('nim') }}" required placeholder="Nomor Induk Mahasiswa"
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                    @else
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                                NIDN <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nidn" value="{{ old('nidn') }}" required
                                placeholder="Nomor Induk Dosen Nasional"
                                class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                    @endif


                    {{-- Program Studi --}}
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Program Studi <span class="text-red-500">*</span>
                        </label>
                        <select name="program_studi_id" required
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach ($programStudi as $prodi)
                                <option value="{{ $prodi->id }}" @selected(old('program_studi_id') == $prodi->id)>
                                    {{ $prodi->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jenis Dokumen --}}
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Jenis Dokumen
                        </label>
                        <select name="jenis_dokumen_id"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="">-- Pilih Jenis Dokumen --</option>
                            @foreach ($jenisDokumen as $jenis)
                                <option value="{{ $jenis->id }}" @selected(old('jenis_dokumen_id') == $jenis->id)>
                                    {{ $jenis->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    {{-- Tahun --}}
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Tahun <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" required
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>

                    {{-- Bulan --}}
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Bulan
                        </label>
                        <select name="bulan"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="">-- Pilih Bulan --</option>
                            @php
                                $months = [
                                    1 => 'Januari',
                                    2 => 'Februari',
                                    3 => 'Maret',
                                    4 => 'April',
                                    5 => 'Mei',
                                    6 => 'Juni',
                                    7 => 'Juli',
                                    8 => 'Agustus',
                                    9 => 'September',
                                    10 => 'Oktober',
                                    11 => 'November',
                                    12 => 'Desember'
                                ];
                            @endphp
                            @foreach ($months as $num => $monthName)
                                <option value="{{ $num }}" @selected(old('bulan', date('n')) == $num)>{{ $monthName }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jumlah Halaman --}}
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Jumlah Halaman
                        </label>
                        <input type="number" name="jumlah_halaman" value="{{ old('jumlah_halaman') }}" min="1"
                            placeholder="Contoh: 120"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>

                    {{-- Status Penelitian --}}
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Status Penelitian
                        </label>
                        <select name="status_penelitian"
                            class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="">-- Pilih Status --</option>
                            <option value="berjalan" @selected(old('status_penelitian') === 'berjalan')>Berjalan</option>
                            <option value="selesai" @selected(old('status_penelitian', 'selesai') === 'selesai')>Selesai
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Judul --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        Judul Dokumen <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" value="{{ old('judul') }}" required placeholder="Masukkan judul lengkap"
                        class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                </div>

                {{-- Abstrak --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                        Abstrak
                    </label>
                    <textarea name="abstrak" rows="4" placeholder="Tuliskan ringkasan atau abstrak..."
                        class="w-full rounded-xl border border-slate-300 p-4 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">{{ old('abstrak') }}</textarea>
                </div>

                @if ($kategori !== 'skripsi')
                    {{-- Detail Tambahan untuk Penelitian / PKM --}}
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-600 mb-1.5">
                            Detail / Keterangan Tambahan
                        </label>
                        <textarea name="detail" rows="3" placeholder="Tuliskan informasi detail penelitian/PKM jika ada..."
                            class="w-full rounded-xl border border-slate-300 p-4 text-sm font-medium text-slate-800 transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">{{ old('detail') }}</textarea>
                    </div>
                @endif

                {{-- ============ SECTION FILE UPLOADS ============ --}}
                <div class="border-t border-slate-200 pt-6 space-y-6">
                    <div class="border-b border-slate-100 pb-4">
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span
                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700 text-xs font-bold">2</span>
                            Upload File Berkas
                        </h2>
                        <p class="mt-1 text-xs text-slate-500">Pilih file PDF dokumen utama dan file RAR/ZIP project
                            (opsional).</p>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        {{-- 1. File Dokumen PDF --}}
                        <div
                            class="rounded-2xl border-2 border-dashed border-blue-200 bg-blue-50/40 p-5 transition hover:border-blue-400">
                            <div class="flex items-center gap-3 mb-3">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-500 text-white shadow-md shadow-red-500/30">
                                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                        <line x1="16" y1="13" x2="8" y2="13" />
                                        <line x1="16" y1="17" x2="8" y2="17" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">File Dokumen (PDF) <span
                                            class="text-red-500">*</span></h3>
                                    <p class="text-xs text-slate-500">Format PDF, maksimal 10MB</p>
                                </div>
                            </div>
                            <input type="file" id="file_dokumen_input" name="file_dokumen" accept=".pdf,application/pdf"
                                required
                                class="block w-full text-xs text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-xs file:font-bold file:text-white hover:file:bg-blue-700 cursor-pointer">
                        </div>

                        {{-- 2. File Project RAR / ZIP --}}
                        <div
                            class="rounded-2xl border-2 border-dashed border-indigo-200 bg-indigo-50/40 p-5 transition hover:border-indigo-400">
                            <div class="flex items-center gap-3 mb-3">
                                <div
                                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-500 text-white shadow-md shadow-amber-500/30">
                                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" />
                                        <polyline points="3.27 6.96 12 12.01 20.73 6.96" />
                                        <line x1="12" y1="22.08" x2="12" y2="12" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">File Project (RAR / ZIP)</h3>
                                    <p class="text-xs text-slate-500">Format .zip atau .rar, maksimal 800MB</p>
                                </div>
                            </div>
                            <input type="file" id="file_project_input" name="file_project"
                                accept=".zip,.rar,application/zip,application/x-zip-compressed,application/x-rar-compressed,application/vnd.rar"
                                class="block w-full text-xs text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-xs file:font-bold file:text-white hover:file:bg-indigo-700 cursor-pointer">
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-6">
                    <a href="{{ route('admin.documents.index') }}"
                        class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        Batal
                    </a>
                    <button type="submit" id="adminSubmitBtn"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/30 transition hover:from-blue-700 hover:to-indigo-700 hover:scale-[1.01]">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        <span id="adminSubmitBtnText">Simpan & Upload Data {{ strtoupper($kategori) }}</span>
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- Upload progress overlay --}}
    <div id="upload-overlay"
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="mx-4 w-full max-w-sm rounded-3xl bg-white p-8 shadow-2xl text-center">
            <div class="flex justify-center mb-4">
                <div class="relative flex h-20 w-20 items-center justify-center rounded-full bg-blue-100">
                    <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <div class="absolute inset-0 rounded-full border-4 border-blue-200 animate-ping opacity-30"></div>
                </div>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Sedang Mengunggah Data...</h3>
            <p class="mt-2 text-sm text-slate-500" id="upload-overlay-info">Mohon tunggu, jangan tutup halaman ini.</p>
            <div class="mt-5 h-3 w-full rounded-full bg-slate-100 overflow-hidden">
                <div id="upload-progress-bar" class="h-full rounded-full bg-gradient-to-r from-blue-500 to-indigo-500"
                    style="width: 0%;"></div>
            </div>
            <p class="mt-2 text-sm text-slate-500" id="upload-progress-label">0%</p>
            <p class="mt-3 text-xs text-slate-400">File sedang diproses oleh server...</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('adminUploadForm');
            const docInput = document.getElementById('file_dokumen_input');
            const projInput = document.getElementById('file_project_input');
            const submitBtn = document.getElementById('adminSubmitBtn');
            const submitText = document.getElementById('adminSubmitBtnText');
            const overlay = document.getElementById('upload-overlay');
            const overlayInfo = document.getElementById('upload-overlay-info');

            if (!form) return;

            form.addEventListener('submit', function (e) {
                const maxDocSize = 10 * 1024 * 1024;  // 10 MB
                const maxProjSize = 800 * 1024 * 1024;  // 800 MB

                // — Validasi ukuran PDF —
                if (docInput && docInput.files.length > 0) {
                    if (docInput.files[0].size > maxDocSize) {
                        e.preventDefault();
                        showError('Ukuran file PDF (' + (docInput.files[0].size / (1024 * 1024)).toFixed(2) + ' MB) melebihi batas 10 MB.');
                        return false;
                    }
                }

                // — Validasi ukuran Project —
                if (projInput && projInput.files.length > 0) {
                    if (projInput.files[0].size > maxProjSize) {
                        e.preventDefault();
                        showError('Ukuran file Project (' + (projInput.files[0].size / (1024 * 1024)).toFixed(2) + ' MB) melebihi batas 800 MB.');
                        return false;
                    }
                }

                e.preventDefault();

                // — Tampilkan overlay upload —
                var info = 'Mengunggah file, mohon tunggu...';
                if (docInput && docInput.files.length > 0) {
                    info = 'Mengunggah: ' + docInput.files[0].name;
                    if (projInput && projInput.files.length > 0) {
                        info += ' + ' + projInput.files[0].name;
                    }
                }
                if (overlayInfo) overlayInfo.textContent = info;
                if (overlay) overlay.classList.remove('hidden');

                const progressBar = document.getElementById('upload-progress-bar');
                const progressLabel = document.getElementById('upload-progress-label');
                if (progressBar) progressBar.style.width = '0%';
                if (progressLabel) progressLabel.textContent = '0%';

                // — Kunci tombol submit —
                if (submitBtn && submitText) {
                    submitBtn.style.pointerEvents = 'none';
                    submitBtn.style.opacity = '0.6';
                    submitText.textContent = 'Mengunggah...';
                }

                const formData = new FormData(form);
                const xhr = new XMLHttpRequest();
                xhr.open('POST', form.action);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                const csrfToken = document.querySelector('input[name="_token"]')?.value;
                if (csrfToken) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                }

                xhr.upload.onprogress = function (event) {
                    if (!event.lengthComputable) return;
                    const percent = Math.round((event.loaded / event.total) * 100);
                    if (progressBar) progressBar.style.width = percent + '%';
                    if (progressLabel) progressLabel.textContent = percent + '%';
                    if (overlayInfo) overlayInfo.textContent = info + ' — ' + percent + '%';
                };

                xhr.onload = function () {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        window.location.href = xhr.responseURL || form.action;
                        return;
                    }
                    handleUploadError(uploadErrorMessage(xhr));
                };

                xhr.onerror = function () {
                    handleUploadError('Gagal mengunggah. Periksa koneksi dan coba lagi.');
                };

                xhr.onabort = function () {
                    handleUploadError('Unggah dibatalkan.');
                };

                xhr.send(formData);
            });

            function handleUploadError(message) {
                const progressBar = document.getElementById('upload-progress-bar');
                const progressLabel = document.getElementById('upload-progress-label');
                if (progressBar) progressBar.style.width = '0%';
                if (progressLabel) progressLabel.textContent = '0%';
                if (overlay) overlay.classList.add('hidden');
                if (submitBtn && submitText) {
                    submitBtn.style.pointerEvents = '';
                    submitBtn.style.opacity = '';
                    submitText.textContent = 'Simpan & Upload Data ' + '{{ strtoupper($kategori) }}';
                }
                showError(message);
            }

            function uploadErrorMessage(xhr) {
                if (xhr.status === 413) {
                    return 'Ukuran file terlalu besar untuk konfigurasi hosting. Naikkan upload_max_filesize dan post_max_size di server.';
                }

                try {
                    const payload = JSON.parse(xhr.responseText || '{}');
                    if (payload.message) {
                        return payload.message;
                    }

                    if (payload.errors) {
                        const firstKey = Object.keys(payload.errors)[0];
                        const firstError = firstKey ? payload.errors[firstKey][0] : null;
                        if (firstError) {
                            return firstError;
                        }
                    }
                } catch (error) {
                    // Fall back to the generic message below.
                }

                return 'Terjadi kesalahan saat mengunggah. Silakan coba lagi.';
            }

            function showError(msg) {
                // Buat banner error kecil di atas form
                var old = document.getElementById('js-upload-error');
                if (old) old.remove();
                var div = document.createElement('div');
                div.id = 'js-upload-error';
                div.className = 'mb-4 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700 text-sm font-medium';
                div.innerHTML = '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" class="mt-0.5 shrink-0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg><span>' + msg + '</span>';
                form.insertBefore(div, form.firstChild);
                window.scrollTo({ top: div.getBoundingClientRect().top + window.scrollY - 100, behavior: 'smooth' });
            }
        });
    </script>
@endsection
