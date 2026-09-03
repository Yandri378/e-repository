@extends('layouts.admin')

@section('title', 'Import & Upload Data')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-50 via-white to-white">
<div class="mx-auto max-w-5xl px-4 py-8 sm:px-6 sm:py-10 lg:px-8 space-y-8">

    {{-- ============ HERO ============ --}}
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-700 via-blue-600 to-emerald-600 px-7 py-9 shadow-xl shadow-blue-900/20 sm:px-10 sm:py-11">
        <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-white/10 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-20 left-10 h-48 w-48 rounded-full bg-emerald-300/20 blur-3xl"></div>

        <div class="relative flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="flex h-13 w-13 shrink-0 items-center justify-center rounded-2xl bg-white/15 ring-1 ring-white/30 backdrop-blur-sm">
                    <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                        <path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2z"/>
                        <path d="M9 13l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-blue-100">Admin · Import & Upload Data</p>
                    <h1 class="mt-1 text-xl font-bold text-white sm:text-2xl md:text-3xl">Import & Upload Data</h1>
                    <p class="mt-1 text-sm text-blue-100/80">Upload file Excel, input manual, atau upload folder ZIP untuk menambahkan data secara massal.</p>
                </div>
            </div>
            <a href="{{ route('admin.documents.index') }}"
               class="hidden sm:inline-flex items-center gap-2 rounded-xl bg-white/15 px-4 py-2.5 text-xs font-bold text-white backdrop-blur-sm ring-1 ring-white/30 transition hover:bg-white/25">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali
            </a>
        </div>
    </section>



    @if (session('import_success') !== null)
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-500 text-white shrink-0">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div>
                    <p class="font-bold text-emerald-800">Import Selesai & Berhasil!</p>
                    <p class="text-sm text-emerald-700">
                        <strong>{{ session('import_success') }} baris</strong> berhasil diimpor sebagai <strong>{{ strtoupper(session('import_kategori', '')) }}</strong>.
                        @if (count(session('import_errors', [])) > 0)
                            <span class="text-amber-700"> {{ count(session('import_errors')) }} baris gagal (lihat di bawah).</span>
                        @endif
                    </p>
                </div>
            </div>
            @if (session('import_success') > 0)
                <a href="{{ route('admin.data.mahasiswa') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 hover:underline">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    Lihat Data Mahasiswa
                </a>
                <a href="{{ route('admin.data.dosen') }}" class="ml-4 inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 hover:underline">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    Lihat Data Dosen
                </a>
            @endif
        </div>
    @endif

    @if (session('import_error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 p-5 shadow-sm flex items-start gap-3">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" class="text-red-500 mt-0.5 shrink-0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <p class="text-sm text-red-700 font-medium">{{ session('import_error') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 p-5 shadow-sm">
            <p class="text-sm font-bold text-red-700 mb-2">Validasi gagal:</p>
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm text-red-600 flex items-center gap-2">
                        <span class="h-1.5 w-1.5 rounded-full bg-red-500 shrink-0"></span>{{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="clientImportSuccess"
         class="hidden rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-500 text-white shrink-0">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <p id="clientImportSuccessText" class="text-sm font-semibold text-emerald-800"></p>
        </div>
    </div>

    {{-- Baris gagal --}}
    @if (count(session('import_errors', [])) > 0)
        <div class="rounded-2xl border border-blue-200 bg-blue-50/70 overflow-hidden shadow-sm">
            <div class="px-5 py-4 bg-blue-100/60 border-b border-blue-200">
                <p class="font-bold text-blue-900 flex items-center gap-2">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    {{ count(session('import_errors')) }} baris gagal diimpor
                </p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-blue-100">
                    <thead class="bg-blue-50">
                        <tr class="text-xs font-semibold text-blue-800 uppercase tracking-wide">
                            <th class="px-4 py-3 text-left">Baris</th>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Judul</th>
                            <th class="px-4 py-3 text-left">Alasan Gagal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-blue-100 bg-white">
                        @foreach (session('import_errors') as $err)
                            <tr>
                                <td class="px-4 py-3 font-mono text-blue-800">#{{ $err['row'] }}</td>
                                <td class="px-4 py-3 text-slate-700">{{ $err['nama'] }}</td>
                                <td class="px-4 py-3 text-slate-600 max-w-xs truncate">{{ $err['judul'] }}</td>
                                <td class="px-4 py-3">
                                    <ul class="space-y-0.5">
                                        @foreach ($err['errors'] as $msg)
                                            <li class="text-red-600">{{ $msg }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- ============ TAB SWITCHER ============ --}}
    <div class="flex items-center gap-1.5 rounded-2xl bg-slate-100 p-1.5 shadow-inner" id="tabBar">
        <button type="button" data-tab="excel"
                class="tab-btn flex-1 flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-bold transition-all duration-200">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18"/></svg>
            Import Excel
        </button>
        <button type="button" data-tab="manual"
                class="tab-btn flex-1 flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-bold transition-all duration-200">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            Input Manual
        </button>
        <button type="button" data-tab="zip"
                class="tab-btn flex-1 flex items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-bold transition-all duration-200">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            Upload ZIP
        </button>
    </div>

    {{-- ============ TAB: IMPORT EXCEL ============ --}}
    <div id="tab-excel" class="tab-panel">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <div class="lg:col-span-2">
                <div class="rounded-2xl border border-blue-100 bg-white shadow-md p-6 space-y-6">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-600"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        Upload File Excel
                    </h2>

                    <form method="POST" action="{{ route('admin.documents.import.store') }}" enctype="multipart/form-data" id="importForm" class="space-y-5">
                        @csrf

                        {{-- Pilih Kategori --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori Dokumen <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2" id="kategoriGroup">
                                @php
                                    $icons = [
                                        'skripsi'    => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>',
                                        'magang'     => '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>',
                                        'pkm'        => '<circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>',
                                        'penelitian' => '<circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/>',
                                    ];
                                    $labels = ['skripsi' => 'Skripsi/TA', 'magang' => 'Magang', 'pkm' => 'PKM', 'penelitian' => 'Penelitian'];
                                @endphp
                                @foreach ($validKategori as $kat)
                                    <label class="kategori-label relative cursor-pointer">
                                        <input type="radio" name="kategori" value="{{ $kat }}"
                                               class="sr-only kategori-radio"
                                               {{ (old('kategori', $kategori) === $kat) ? 'checked' : '' }}>
                                        <div class="kategori-card flex flex-col items-center gap-1.5 rounded-xl border-2 px-3 py-3 text-center transition-all duration-200
                                            {{ (old('kategori', $kategori) === $kat) ? 'border-blue-500 bg-blue-50 text-blue-700 shadow-md' : 'border-slate-200 bg-white text-slate-600 hover:border-blue-300 hover:bg-blue-50/40' }}">
                                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                {!! $icons[$kat] !!}
                                            </svg>
                                            <span class="text-xs font-semibold">{{ $labels[$kat] }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Drag & Drop Zone --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">File Excel <span class="text-red-500">*</span></label>
                            <div id="dropZone"
                                 class="relative flex flex-col items-center justify-center gap-3 rounded-2xl border-2 border-dashed border-blue-300 bg-blue-50/40 px-6 py-10 text-center transition-all duration-200 hover:border-blue-400 hover:bg-blue-50 cursor-pointer">
                                <div id="dropIcon" class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-blue-500">
                                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-700">Drag & drop file di sini</p>
                                    <p class="text-sm text-slate-500 mt-0.5">atau <span class="text-blue-600 font-semibold cursor-pointer">klik untuk pilih file</span></p>
                                    <p class="text-xs text-slate-400 mt-1">.xlsx atau .csv &middot; Maks 10 MB</p>
                                </div>
                                <input type="file" name="file" id="fileInput" accept=".xlsx,.csv"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <div id="fileName" class="hidden mt-1 flex items-center gap-2 rounded-full bg-blue-600 px-4 py-1.5 text-sm text-white font-medium shadow-sm">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2z"/></svg>
                                    <span id="fileNameText"></span>
                                </div>
                            </div>
                        </div>

                        {{-- ZIP Lampiran PDF (Opsional) --}}
                        <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="block text-sm font-semibold text-slate-700">
                                    File ZIP Lampiran PDF <span class="text-xs font-normal text-slate-500">(Opsional)</span>
                                </label>
                                <span class="text-[11px] font-medium text-slate-400">Maks 800 MB</span>
                            </div>
                            <p class="text-xs text-slate-500">Jika ada berkas PDF dokumen, upload file .zip lampiran di sini. Nama file PDF di dalam ZIP disesuaikan dengan kolom <code>File Dokumen</code>, NIM, atau Nama Mahasiswa di Excel.</p>

                            <div class="flex flex-wrap items-center gap-3 pt-1">
                                <label for="excelZipInput"
                                       class="inline-flex items-center gap-2 rounded-xl bg-blue-400 hover:bg-blue-300 active:bg-blue-500 text-slate-950 font-extrabold text-xs px-4 py-2.5 shadow-md shadow-blue-500/20 border border-blue-500 transition-all duration-200 cursor-pointer hover:scale-105 hover:shadow-lg hover:shadow-blue-400/30 active:scale-95 shrink-0">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" class="text-slate-950">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="17 8 12 3 7 8"/>
                                        <line x1="12" y1="3" x2="12" y2="15"/>
                                    </svg>
                                    Pilih Berkas ZIP Lampiran
                                </label>
                                <input type="file" name="file_zip" id="excelZipInput" accept=".zip" class="hidden">

                                <div id="excelZipSelected" class="hidden items-center gap-2 rounded-lg bg-blue-50 border border-blue-200 px-3 py-1.5 text-xs font-medium text-blue-700">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                                    </svg>
                                    <span id="excelZipName" class="truncate max-w-[200px]"></span>
                                    <button type="button" id="removeExcelZip" class="text-blue-400 hover:text-red-500 font-bold ml-1 text-sm">&times;</button>
                                </div>
                                <span id="excelZipStatusText" class="text-xs text-slate-400 italic">Belum ada file ZIP dipilih</span>
                            </div>
                        </div>

                        {{-- Progress Bar Loading Excel --}}
                        <div id="excelProgressContainer" class="hidden space-y-2 rounded-xl bg-blue-50/80 border border-blue-200 p-4 shadow-sm">
                            <div class="flex items-center justify-between text-xs font-semibold text-blue-900">
                                <span id="excelProgressStatus" class="flex items-center gap-2">
                                    <svg class="animate-spin text-blue-600" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-opacity="1"/></svg>
                                    Mengunggah berkas Excel...
                                </span>
                                <span id="excelProgressPercent" class="font-mono font-bold text-blue-700 text-sm">0%</span>
                            </div>
                            <div class="w-full bg-blue-200/70 rounded-full h-3 overflow-hidden p-0.5 shadow-inner">
                                <div id="excelProgressBar" class="bg-gradient-to-r from-blue-600 via-indigo-500 to-emerald-500 h-2 rounded-full transition-all duration-200 ease-out" style="width: 0%"></div>
                            </div>
                        </div>

                        <button type="submit" id="submitBtn"
                                class="w-full flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-emerald-500 px-6 py-3.5 text-sm font-bold text-white shadow-md shadow-blue-500/30 transition-all duration-200 hover:scale-[1.01] hover:shadow-lg active:scale-[0.99]">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2z"/><path d="M9 13l2 2 4-4"/></svg>
                            Import Data Excel
                        </button>
                    </form>
                </div>
            </div>

            {{-- ============ SIDEBAR: PANDUAN & TEMPLATE ============ --}}
            <div class="space-y-5">

                {{-- Download Template Excel --}}
                <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
                    <h3 class="font-bold text-blue-800 flex items-center gap-2 mb-3">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Download Template Excel
                    </h3>
                    <p class="text-xs text-blue-700 mb-3 leading-relaxed">
                        Download template <strong>.xls</strong> (Excel) sesuai kategori. Bisa langsung dibuka di <strong>Microsoft Excel</strong>, Google Sheets, atau LibreOffice Calc.
                    </p>
                    <div class="space-y-2">
                        @foreach (['skripsi' => 'Skripsi/TA', 'magang' => 'Magang', 'pkm' => 'PKM', 'penelitian' => 'Penelitian'] as $kat => $label)
                            <a href="{{ route('admin.documents.import.template-excel', $kat) }}"
                               class="flex items-center gap-2 rounded-lg border border-blue-200 bg-white px-3 py-2.5 text-sm font-semibold text-blue-700 transition-all duration-200 hover:border-blue-400 hover:bg-blue-50 hover:shadow-sm">
                                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18"/></svg>
                                Template {{ $label }} (.xls)
                                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" class="ml-auto text-blue-400"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Download Template CSV --}}
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                    <h3 class="font-bold text-emerald-800 flex items-center gap-2 mb-3">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Download Template CSV
                    </h3>
                    <p class="text-xs text-emerald-700 mb-3 leading-relaxed">
                        Download template <strong>.csv</strong> sesuai kategori. Bisa langsung dibuka dan diisi di <strong>Microsoft Excel</strong>, Google Sheets, atau LibreOffice Calc.
                    </p>
                    <div class="space-y-2">
                        @foreach (['skripsi' => 'Skripsi/TA', 'magang' => 'Magang', 'pkm' => 'PKM', 'penelitian' => 'Penelitian'] as $kat => $label)
                            <a href="{{ route('admin.documents.import.template', $kat) }}"
                               class="flex items-center gap-2 rounded-lg border border-emerald-200 bg-white px-3 py-2.5 text-sm font-semibold text-emerald-700 transition-all duration-200 hover:border-emerald-400 hover:bg-emerald-50 hover:shadow-sm">
                                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18"/></svg>
                                Template {{ $label }} (.csv)
                                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" class="ml-auto text-emerald-400"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            </a>
                        @endforeach
                    </div>
                    <p class="mt-3 text-[11px] text-emerald-600">💡 Setelah mengisi data di Excel/Google Sheets, simpan sebagai <strong>CSV</strong> atau upload langsung file <strong>.xlsx</strong>.</p>
                </div>

                {{-- Format Kolom --}}
                <div class="rounded-2xl border border-blue-100 bg-white p-5 shadow-sm">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2 mb-3">
                        <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" class="text-blue-600"><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2z"/><line x1="9" y1="9" x2="15" y2="9"/><line x1="9" y1="13" x2="15" y2="13"/></svg>
                        Kolom yang Diharapkan
                    </h3>
                    <p class="text-xs text-slate-500 mb-3">Kolom <strong class="text-red-600">wajib</strong> harus diisi. Baris 1 harus berisi nama kolom (header).</p>
                    <ul class="space-y-1.5">
                        @foreach ($headers as $h)
                            @php
                                $required = in_array($h, ['Nama', 'Judul', 'Tahun']);
                            @endphp
                            <li class="flex items-center gap-2 text-xs">
                                <span class="h-1.5 w-1.5 rounded-full {{ $required ? 'bg-red-500' : 'bg-slate-300' }} shrink-0"></span>
                                <code class="rounded bg-slate-100 px-1.5 py-0.5 font-mono text-slate-700">{{ $h }}</code>
                                @if ($required)
                                    <span class="text-red-500 font-semibold text-[10px]">wajib</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Tips --}}
                <div class="rounded-2xl border border-blue-100 bg-blue-50/50 p-5">
                    <h3 class="font-bold text-blue-800 flex items-center gap-2 mb-2 text-sm">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Tips Import
                    </h3>
                    <ul class="space-y-1.5 text-xs text-blue-700">
                        <li class="flex items-start gap-1.5"><span class="mt-0.5 shrink-0">•</span>Gunakan template yang disediakan agar kolom sesuai.</li>
                        <li class="flex items-start gap-1.5"><span class="mt-0.5 shrink-0">•</span>Baris pertama HARUS berisi nama kolom (header).</li>
                        <li class="flex items-start gap-1.5"><span class="mt-0.5 shrink-0">•</span>Kolom Program Studi harus sesuai dengan nama prodi yang ada di sistem.</li>
                        <li class="flex items-start gap-1.5"><span class="mt-0.5 shrink-0">•</span>Data yang berhasil diimpor langsung berstatus <strong>Terverifikasi</strong>.</li>
                        <li class="flex items-start gap-1.5"><span class="mt-0.5 shrink-0">•</span>Baris yang gagal tidak akan mempengaruhi baris lain yang berhasil.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- ============ TAB: INPUT MANUAL ============ --}}
    <div id="tab-manual" class="tab-panel hidden">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-md overflow-hidden">
            <div class="p-8 sm:p-10 text-center">
                <div class="flex h-20 w-20 mx-auto items-center justify-center rounded-full bg-gradient-to-br from-indigo-100 to-blue-100 mb-5">
                    <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" class="text-indigo-600">
                        <path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-slate-800 mb-2">Input Data Manual</h2>
                <p class="text-sm text-slate-500 max-w-md mx-auto mb-6">
                    Masukkan data Skripsi, Magang, PKM, atau Penelitian satu per satu melalui form upload manual. Lengkap dengan file PDF dan file project.
                </p>

                {{-- Kategori Quick Links --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 max-w-lg mx-auto mb-6">
                    @php
                        $manualColors = [
                            'skripsi'    => ['bg-blue-50', 'border-blue-200', 'text-blue-700', 'hover:bg-blue-100', 'hover:border-blue-400'],
                            'magang'     => ['bg-amber-50', 'border-amber-200', 'text-amber-700', 'hover:bg-amber-100', 'hover:border-amber-400'],
                            'pkm'        => ['bg-emerald-50', 'border-emerald-200', 'text-emerald-700', 'hover:bg-emerald-100', 'hover:border-emerald-400'],
                            'penelitian' => ['bg-purple-50', 'border-purple-200', 'text-purple-700', 'hover:bg-purple-100', 'hover:border-purple-400'],
                        ];
                        $manualIcons = [
                            'skripsi'    => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>',
                            'magang'     => '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>',
                            'pkm'        => '<circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>',
                            'penelitian' => '<circle cx="11" cy="11" r="8"/><path d="M21 21l-4.3-4.3"/>',
                        ];
                    @endphp
                    @foreach (['skripsi' => 'Skripsi/TA', 'magang' => 'Magang', 'pkm' => 'PKM', 'penelitian' => 'Penelitian'] as $kat => $label)
                        <a href="{{ route('admin.documents.create', ['kategori' => $kat]) }}"
                           class="flex flex-col items-center gap-2 rounded-xl border-2 px-3 py-4 text-center transition-all duration-200 {{ implode(' ', $manualColors[$kat]) }}">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                {!! $manualIcons[$kat] !!}
                            </svg>
                            <span class="text-xs font-bold">{{ $label }}</span>
                        </a>
                    @endforeach
                </div>

                <a href="{{ route('admin.documents.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-500 px-8 py-3.5 text-sm font-bold text-white shadow-md shadow-indigo-500/30 transition-all duration-200 hover:scale-[1.01] hover:shadow-lg active:scale-[0.99]">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Buka Form Upload Manual
                </a>
            </div>
        </div>
    </div>

    {{-- ============ TAB: UPLOAD ZIP ============ --}}
    <div id="tab-zip" class="tab-panel hidden">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            <div class="lg:col-span-2">
                <div class="rounded-2xl border border-violet-100 bg-white shadow-md p-6 space-y-6">
                    <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" class="text-violet-600"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        Upload File ZIP
                    </h2>
                    <p class="text-sm text-slate-500 -mt-3">Upload folder ZIP yang berisi file-file PDF dokumen. Setiap file PDF akan otomatis menjadi 1 record data.</p>

                    {{-- Alert Success Inline ZIP --}}
                    <div id="zipSuccessAlert" class="hidden rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500 text-white shrink-0 shadow-sm">
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-emerald-800 text-base">Upload ZIP Berhasil!</p>
                                <p id="zipSuccessAlertText" class="text-sm text-emerald-700 mt-0.5"></p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.documents.import.zip') }}" enctype="multipart/form-data" id="zipForm" class="space-y-5">
                        @csrf

                        {{-- Pilih Kategori ZIP --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori Dokumen <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2" id="kategoriGroupZip">
                                @foreach ($validKategori as $kat)
                                    <label class="kategori-label-zip relative cursor-pointer">
                                        <input type="radio" name="kategori" value="{{ $kat }}"
                                               class="sr-only kategori-radio-zip"
                                               {{ ($kat === 'skripsi') ? 'checked' : '' }}>
                                        <div class="kategori-card-zip flex flex-col items-center gap-1.5 rounded-xl border-2 px-3 py-3 text-center transition-all duration-200
                                            {{ ($kat === 'skripsi') ? 'border-violet-500 bg-violet-50 text-violet-700 shadow-md' : 'border-slate-200 bg-white text-slate-600 hover:border-violet-300 hover:bg-violet-50/40' }}">
                                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                {!! $icons[$kat] !!}
                                            </svg>
                                            <span class="text-xs font-semibold">{{ $labels[$kat] }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- ZIP Drop Zone & Button --}}
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">File ZIP <span class="text-red-500">*</span></label>
                            <div id="zipDropZone"
                                 class="flex flex-col items-center justify-center gap-3 rounded-2xl border-2 border-dashed border-violet-300 bg-violet-50/50 px-6 py-9 text-center transition-all duration-200 hover:border-violet-500 hover:bg-violet-100/50">

                                {{-- Folder Icon --}}
                                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-violet-100 text-violet-600 shadow-sm border border-violet-200/60">
                                    <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                                        <line x1="12" y1="11" x2="12" y2="17"/>
                                        <polyline points="9 14 12 11 15 14"/>
                                    </svg>
                                </div>

                                <div>
                                    <p class="font-bold text-slate-800 text-base">Drag & drop file ZIP / RAR di sini</p>
                                    <p class="text-xs text-slate-500 mt-1">Format file <strong>.zip</strong> atau <strong>.rar</strong> &middot; Berisi berkas PDF &middot; Maksimal 800 MB</p>
                                </div>

                                {{-- Clear Solid Upload Button --}}
                                <button type="button" id="btnChooseZip"
                                        class="mt-2 inline-flex items-center gap-2.5 rounded-xl bg-gradient-to-r from-blue-500 via-indigo-500 to-violet-600 hover:from-blue-600 hover:to-violet-700 active:from-blue-700 active:to-violet-800 text-white font-extrabold text-sm px-7 py-3.5 shadow-lg shadow-indigo-500/25 border border-indigo-400/30 transition-all duration-200 cursor-pointer hover:scale-105 hover:shadow-xl active:scale-95">
                                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" class="text-white">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="17 8 12 3 7 8"/>
                                        <line x1="12" y1="3" x2="12" y2="15"/>
                                    </svg>
                                    <span id="btnChooseZipText">Pilih File ZIP / RAR Dari Komputer</span>
                                </button>

                                {{-- Hidden Input --}}
                                <input type="file" name="file_zip" id="zipFileInput" accept=".zip,.rar,.7z" class="hidden">

                                {{-- Selected File Badge --}}
                                <div id="zipFileName" class="hidden mt-2 items-center gap-2 rounded-xl bg-violet-700 px-4 py-2 text-sm text-white font-semibold shadow-md">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                                    </svg>
                                    <span id="zipFileNameText"></span>
                                    <button type="button" id="removeZipFileBtn" class="ml-2 text-violet-200 hover:text-white font-bold text-base">&times;</button>
                                </div>
                            </div>
                        </div>

                        {{-- Progress Bar Loading ZIP --}}
                        <div id="zipProgressContainer" class="hidden space-y-2 rounded-xl bg-violet-50/80 border border-violet-200 p-4 shadow-sm">
                            <div class="flex items-center justify-between text-xs font-semibold text-violet-900">
                                <span id="zipProgressStatus" class="flex items-center gap-2">
                                    <svg class="animate-spin text-violet-600" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-opacity="1"/></svg>
                                    Mengunggah berkas ZIP...
                                </span>
                                <span id="zipProgressPercent" class="font-mono font-bold text-violet-700 text-sm">0%</span>
                            </div>
                            <div class="w-full bg-violet-200/70 rounded-full h-3 overflow-hidden p-0.5 shadow-inner">
                                <div id="zipProgressBar" class="bg-gradient-to-r from-violet-600 via-purple-500 to-indigo-500 h-2 rounded-full transition-all duration-200 ease-out" style="width: 0%"></div>
                            </div>
                        </div>

                        <p id="zipSaveHint" class="rounded-xl border border-violet-100 bg-violet-50 px-4 py-3 text-center text-xs font-medium text-violet-700">
                            Pilih file ZIP terlebih dahulu, lalu tekan tombol simpan untuk memproses datanya.
                        </p>

                        <button type="submit" id="zipSubmitBtn" disabled
                                class="w-full flex items-center justify-center gap-2.5 rounded-xl bg-slate-200 border border-slate-300 text-slate-500 font-bold text-base px-6 py-4 shadow-inner transition-all duration-200 cursor-not-allowed opacity-80">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2">
                                <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                                <line x1="12" y1="11" x2="12" y2="17"/>
                                <polyline points="9 14 12 11 15 14"/>
                            </svg>
                            Simpan &amp; Proses Data ZIP
                        </button>
                    </form>
                </div>
            </div>

            {{-- Sidebar ZIP Info --}}
            <div class="space-y-5">
                <div class="rounded-2xl border border-violet-200 bg-violet-50 p-5">
                    <h3 class="font-bold text-violet-800 flex items-center gap-2 mb-3">
                        <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Panduan Upload ZIP
                    </h3>
                    <ul class="space-y-2 text-xs text-violet-700">
                        <li class="flex items-start gap-2">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-violet-200 text-violet-700 text-[10px] font-bold mt-0.5">1</span>
                            Siapkan folder berisi berkas dokumen PDF.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-violet-200 text-violet-700 text-[10px] font-bold mt-0.5">2</span>
                            Kompres folder tersebut menjadi file <strong>.zip</strong> atau <strong>.rar</strong>.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-violet-200 text-violet-700 text-[10px] font-bold mt-0.5">3</span>
                            Pilih <strong>kategori</strong> dokumen, lalu upload file ZIP/RAR.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-violet-200 text-violet-700 text-[10px] font-bold mt-0.5">4</span>
                            Setiap berkas PDF dalam ZIP akan menjadi <strong>1 record data</strong> dengan status <strong>Terverifikasi</strong> (langsung dapat dicari di katalog).
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-violet-200 text-violet-700 text-[10px] font-bold mt-0.5">5</span>
                            Nama berkas PDF akan digunakan sebagai <strong>nama & judul</strong> sementara. Anda bisa mengeditnya nanti.
                        </li>
                    </ul>
                </div>

                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                    <h3 class="font-bold text-amber-800 flex items-center gap-2 mb-2 text-sm">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 1-2 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Perhatian
                    </h3>
                    <ul class="space-y-1.5 text-xs text-amber-700">
                        <li class="flex items-start gap-1.5"><span class="mt-0.5 shrink-0">•</span>Berkas dokumen (<strong>.pdf</strong>) di dalam ZIP yang akan diproses.</li>
                        <li class="flex items-start gap-1.5"><span class="mt-0.5 shrink-0">•</span>Berkas selain PDF akan diabaikan secara otomatis.</li>
                        <li class="flex items-start gap-1.5"><span class="mt-0.5 shrink-0">•</span>Ukuran ZIP maksimal <strong>800 MB</strong>.</li>
                        <li class="flex items-start gap-1.5"><span class="mt-0.5 shrink-0">•</span>Data yang diupload berstatus <strong>Terverifikasi</strong> dan langsung dapat dicari di katalog.</li>
                    </ul>
                </div>                </div>
            </div>
        </div>
    </div>

</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Restore active tab from URL or sessionStorage
    const urlParams = new URLSearchParams(window.location.search);
    const urlTab = urlParams.get('tab');
    const savedSuccessTab = sessionStorage.getItem('adminImportSuccessTab');
    const targetTab = savedSuccessTab || (['excel', 'manual', 'zip'].includes(urlTab) ? urlTab : 'excel');

    // ========== TAB SWITCHING ==========
    const tabs = document.querySelectorAll('.tab-btn');
    const panels = document.querySelectorAll('.tab-panel');
    const activeClasses = ['bg-white', 'text-blue-700', 'shadow-md', 'shadow-blue-100/50'];
    const inactiveClasses = ['text-slate-500', 'hover:text-slate-700', 'hover:bg-white/50'];

    function activateTab(tabName) {
        tabs.forEach(btn => {
            const isActive = btn.dataset.tab === tabName;
            btn.classList.remove(...activeClasses, ...inactiveClasses);
            btn.classList.add(...(isActive ? activeClasses : inactiveClasses));
        });
        panels.forEach(panel => {
            panel.classList.toggle('hidden', panel.id !== 'tab-' + tabName);
        });
        // Save to URL
        const url = new URL(window.location.href);
        url.searchParams.set('tab', tabName);
        history.replaceState(null, '', url.toString());
    }

    tabs.forEach(btn => {
        btn.addEventListener('click', () => activateTab(btn.dataset.tab));
    });

    activateTab(targetTab);

    // Flash/Session storage success message handling
    const savedSuccessMessage = sessionStorage.getItem('adminImportSuccessMessage');
    if (savedSuccessMessage) {
        // Top global banner
        const successBox = document.getElementById('clientImportSuccess');
        const successText = document.getElementById('clientImportSuccessText');

        if (successBox && successText) {
            successText.textContent = savedSuccessMessage;
            successBox.classList.remove('hidden');
        }

        // Inline ZIP tab alert
        if (targetTab === 'zip') {
            const zipAlertBox = document.getElementById('zipSuccessAlert');
            const zipAlertText = document.getElementById('zipSuccessAlertText');
            if (zipAlertBox && zipAlertText) {
                zipAlertText.textContent = savedSuccessMessage;
                zipAlertBox.classList.remove('hidden');
                zipAlertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else if (successBox) {
                successBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } else if (successBox) {
            successBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        sessionStorage.removeItem('adminImportSuccessMessage');
        sessionStorage.removeItem('adminImportSuccessTab');
    }

    // ========== EXCEL: Drag & drop ==========
    const dropZone  = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileName  = document.getElementById('fileName');
    const fileNameText = document.getElementById('fileNameText');

    if (fileInput) {
        fileInput.addEventListener('change', function () {
            if (this.files.length) {
                fileNameText.textContent = this.files[0].name;
                fileName.classList.remove('hidden');
                fileName.classList.add('flex');
                dropZone.classList.add('border-blue-500', 'bg-blue-50');
            }
        });
    }

    if (dropZone) {
        ['dragenter', 'dragover'].forEach(e => {
            dropZone.addEventListener(e, ev => {
                ev.preventDefault();
                dropZone.classList.add('border-blue-500', 'bg-blue-100/60', 'scale-[1.01]');
            });
        });
        ['dragleave', 'drop'].forEach(e => {
            dropZone.addEventListener(e, ev => {
                ev.preventDefault();
                dropZone.classList.remove('border-blue-500', 'bg-blue-100/60', 'scale-[1.01]');
            });
        });
        dropZone.addEventListener('drop', function (e) {
            e.preventDefault();
            const file = e.dataTransfer.files[0];
            if (file) {
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
                fileNameText.textContent = file.name;
                fileName.classList.remove('hidden');
                fileName.classList.add('flex');
            }
        });
    }

    // ========== EXCEL: ZIP Lampiran Button Handler ==========
    const excelZipInput = document.getElementById('excelZipInput');
    const excelZipSelected = document.getElementById('excelZipSelected');
    const excelZipName = document.getElementById('excelZipName');
    const excelZipStatusText = document.getElementById('excelZipStatusText');
    const removeExcelZip = document.getElementById('removeExcelZip');

    if (excelZipInput) {
        excelZipInput.addEventListener('change', function () {
            if (this.files.length) {
                excelZipName.textContent = this.files[0].name;
                excelZipSelected.classList.remove('hidden');
                excelZipSelected.classList.add('flex');
                if (excelZipStatusText) excelZipStatusText.classList.add('hidden');
            }
        });
    }

    if (removeExcelZip) {
        removeExcelZip.addEventListener('click', function () {
            excelZipInput.value = '';
            excelZipSelected.classList.add('hidden');
            excelZipSelected.classList.remove('flex');
            if (excelZipStatusText) excelZipStatusText.classList.remove('hidden');
        });
    }

    // ========== ZIP: Button & Drag & drop ==========
    const zipDropZone  = document.getElementById('zipDropZone');
    const zipFileInput = document.getElementById('zipFileInput');
    const zipFileName  = document.getElementById('zipFileName');
    const zipFileNameText = document.getElementById('zipFileNameText');
    const btnChooseZip = document.getElementById('btnChooseZip');
    const btnChooseZipText = document.getElementById('btnChooseZipText');
    const removeZipFileBtn = document.getElementById('removeZipFileBtn');
    const zipSubmitBtn = document.getElementById('zipSubmitBtn');
    const zipSaveHint = document.getElementById('zipSaveHint');
    const zipSubmitReadyClass = 'w-full flex items-center justify-center gap-2.5 rounded-xl border border-blue-800 bg-blue-700 hover:bg-blue-800 text-white font-extrabold text-base px-6 py-4 shadow-lg shadow-blue-700/30 transition-all duration-200 hover:scale-[1.01] active:scale-[0.99] cursor-pointer opacity-100';
    const zipSubmitDisabledClass = 'w-full flex items-center justify-center gap-2.5 rounded-xl bg-slate-200 border border-slate-300 text-slate-500 font-bold text-base px-6 py-4 shadow-inner transition-all duration-200 cursor-not-allowed opacity-80';
    const zipSubmitLoadingClass = 'w-full flex items-center justify-center gap-2.5 rounded-xl border border-slate-950 bg-slate-900 text-white font-extrabold text-base px-6 py-4 shadow-lg shadow-slate-900/30 transition-all duration-200 cursor-wait opacity-100';

    // Hardcoded HTML strings for each button state — avoids all innerHTML/SVG className issues
    const ZIP_BTN_NORMAL_HTML = '<svg class="shrink-0 text-white" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/><line x1="12" y1="11" x2="12" y2="17"/><polyline points="9 14 12 11 15 14"/></svg><span class="text-white">Simpan & Proses Data ZIP</span>';
    const ZIP_BTN_LOADING_HTML = '<svg class="animate-spin shrink-0 text-white" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-opacity="1"/></svg><span class="text-white">Mengekstrak & Memproses ZIP...</span>';

    function updateZipSubmitState(isReady) {
        if (!zipSubmitBtn) return;

        zipSubmitBtn.disabled = !isReady;
        zipSubmitBtn.className = isReady ? zipSubmitReadyClass : zipSubmitDisabledClass;
        zipSubmitBtn.innerHTML = ZIP_BTN_NORMAL_HTML;

        if (btnChooseZipText) {
            btnChooseZipText.textContent = isReady ? 'Ganti File Arsip (ZIP/RAR)' : 'Pilih File ZIP / RAR Dari Komputer';
        }

        if (zipSaveHint) {
            zipSaveHint.textContent = isReady
                ? 'File arsip sudah dipilih. Tekan tombol simpan di bawah untuk memproses data.'
                : 'Pilih file ZIP/RAR terlebih dahulu, lalu tekan tombol simpan untuk memproses datanya.';
            zipSaveHint.classList.toggle('border-emerald-200', isReady);
            zipSaveHint.classList.toggle('bg-emerald-50', isReady);
            zipSaveHint.classList.toggle('text-emerald-700', isReady);
            zipSaveHint.classList.toggle('border-violet-100', !isReady);
            zipSaveHint.classList.toggle('bg-violet-50', !isReady);
            zipSaveHint.classList.toggle('text-violet-700', !isReady);
        }
    }

    function isZipFile(file) {
        if (!file || !file.name) return false;
        const fn = file.name.toLowerCase();
        return fn.endsWith('.zip') || fn.endsWith('.rar') || fn.endsWith('.7z');
    }

    if (btnChooseZip && zipFileInput) {
        btnChooseZip.addEventListener('click', function (e) {
            e.stopPropagation();
            zipFileInput.click();
        });
    }

    if (removeZipFileBtn && zipFileInput) {
        removeZipFileBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            zipFileInput.value = '';
            zipFileName.classList.add('hidden');
            zipFileName.classList.remove('flex');
            updateZipSubmitState(false);
            if (zipDropZone) {
                zipDropZone.classList.remove('border-violet-500', 'bg-violet-50');
            }
        });
    }

    if (zipFileInput) {
        zipFileInput.addEventListener('change', function () {
            if (this.files.length) {
                if (!isZipFile(this.files[0])) {
                    alert('File harus berformat .zip atau .rar.');
                    this.value = '';
                    updateZipSubmitState(false);
                    return;
                }
                zipFileNameText.textContent = this.files[0].name;
                zipFileName.classList.remove('hidden');
                zipFileName.classList.add('flex');
                zipDropZone.classList.add('border-violet-500', 'bg-violet-50');
                updateZipSubmitState(true);
            }
        });
    }

    if (zipDropZone) {
        zipDropZone.addEventListener('click', function (e) {
            if (e.target !== removeZipFileBtn && !removeZipFileBtn?.contains(e.target)) {
                zipFileInput.click();
            }
        });
        ['dragenter', 'dragover'].forEach(e => {
            zipDropZone.addEventListener(e, ev => {
                ev.preventDefault();
                zipDropZone.classList.add('border-violet-500', 'bg-violet-100/60', 'scale-[1.01]');
            });
        });
        ['dragleave', 'drop'].forEach(e => {
            zipDropZone.addEventListener(e, ev => {
                ev.preventDefault();
                zipDropZone.classList.remove('border-violet-500', 'bg-violet-100/60', 'scale-[1.01]');
            });
        });
        zipDropZone.addEventListener('drop', function (e) {
            e.preventDefault();
            const file = e.dataTransfer.files[0];
            if (file) {
                if (!isZipFile(file)) {
                    alert('File harus berformat .zip atau .rar.');
                    updateZipSubmitState(false);
                    return;
                }
                const dt = new DataTransfer();
                dt.items.add(file);
                zipFileInput.files = dt.files;
                zipFileNameText.textContent = file.name;
                zipFileName.classList.remove('hidden');
                zipFileName.classList.add('flex');
                zipDropZone.classList.add('border-violet-500', 'bg-violet-50');
                updateZipSubmitState(true);
            }
        });
    }

    updateZipSubmitState(Boolean(zipFileInput && zipFileInput.files.length));

    // ========== Radio kategori styling (Excel tab) ==========
    document.querySelectorAll('.kategori-radio').forEach(radio => {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.kategori-card').forEach(card => {
                card.classList.remove('border-blue-500', 'bg-blue-50', 'text-blue-700', 'shadow-md');
                card.classList.add('border-slate-200', 'bg-white', 'text-slate-600');
            });
            const activeCard = this.closest('.kategori-label').querySelector('.kategori-card');
            activeCard.classList.remove('border-slate-200', 'bg-white', 'text-slate-600');
            activeCard.classList.add('border-blue-500', 'bg-blue-50', 'text-blue-700', 'shadow-md');

            // Update headers list via redirect with query param
            const url = new URL(window.location.href);
            url.searchParams.set('kategori', this.value);
            url.searchParams.set('tab', 'excel');
            window.location.href = url.toString();
        });
    });

    // ========== Radio kategori styling (ZIP tab) ==========
    document.querySelectorAll('.kategori-radio-zip').forEach(radio => {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.kategori-card-zip').forEach(card => {
                card.classList.remove('border-violet-500', 'bg-violet-50', 'text-violet-700', 'shadow-md');
                card.classList.add('border-slate-200', 'bg-white', 'text-slate-600');
            });
            const activeCard = this.closest('.kategori-label-zip').querySelector('.kategori-card-zip');
            activeCard.classList.remove('border-slate-200', 'bg-white', 'text-slate-600');
            activeCard.classList.add('border-violet-500', 'bg-violet-50', 'text-violet-700', 'shadow-md');
        });
    });

    // ========== AJAX UPLOAD WITH REAL-TIME PROGRESS BAR ==========
    function attachProgressUpload(formId, btnId, containerId, barId, percentId, statusId, loadingText) {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const btn = document.getElementById(btnId);
            const container = document.getElementById(containerId);
            const bar = document.getElementById(barId);
            const percent = document.getElementById(percentId);
            const status = document.getElementById(statusId);
            const defaultButtonHtml = btn ? btn.innerHTML : '';

            if (formId === 'zipForm' && (!zipFileInput || !zipFileInput.files.length)) {
                alert('Silakan pilih file ZIP terlebih dahulu sebelum menyimpan.');
                updateZipSubmitState(false);
                return;
            }

            if (typeof form.reportValidity === 'function' && !form.reportValidity()) {
                return;
            }

            if (container) container.classList.remove('hidden');
            if (bar) bar.style.width = '0%';
            if (percent) percent.textContent = '0%';
            if (btn) {
                btn.disabled = true;
                if (formId === 'zipForm') {
                    btn.className = zipSubmitLoadingClass;
                    btn.innerHTML = ZIP_BTN_LOADING_HTML;
                } else {
                    btn.classList.add('opacity-75', 'cursor-not-allowed');
                    btn.innerHTML = '<svg class="animate-spin" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-opacity="1"/></svg> ' + loadingText;
                }
            }

            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();

            xhr.open(form.method, form.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

            xhr.upload.onprogress = function (ev) {
                if (ev.lengthComputable) {
                    const p = Math.round((ev.loaded / ev.total) * 100);
                    if (bar) bar.style.width = p + '%';
                    if (percent) percent.textContent = p + '%';
                    if (status) {
                        if (p < 100) {
                            status.innerHTML = '<svg class="animate-spin inline mr-1" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-opacity="1"/></svg> Mengunggah berkas... ' + p + '%';
                        } else {
                            status.innerHTML = '<svg class="animate-spin inline mr-1" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-opacity="1"/></svg> Upload 100%! Memproses data di server...';
                        }
                    }
                }
            };

            function restoreButton() {
                if (!btn) return;
                btn.disabled = false;
                if (formId === 'zipForm') {
                    updateZipSubmitState(Boolean(zipFileInput && zipFileInput.files.length));
                } else {
                    btn.classList.remove('opacity-75', 'cursor-not-allowed');
                    btn.innerHTML = defaultButtonHtml;
                }
            }

            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 400) {
                    let successMessage = 'Upload berhasil diproses.';
                    const contentType = xhr.getResponseHeader('Content-Type') || '';
                    if (contentType.includes('application/json')) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                successMessage = response.message;
                            }
                        } catch (error) {
                            successMessage = 'Upload ZIP berhasil diproses.';
                        }
                    } else if (formId === 'zipForm') {
                        successMessage = 'Upload ZIP berhasil diproses.';
                    }

                    sessionStorage.setItem('adminImportSuccessMessage', successMessage);
                    sessionStorage.setItem('adminImportSuccessTab', formId === 'zipForm' ? 'zip' : 'excel');

                    if (status) {
                        status.innerHTML = '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" class="inline mr-1 text-emerald-600"><polyline points="20 6 9 17 4 12"/></svg> Upload berhasil! Memuat ulang halaman...';
                    }
                    if (bar) bar.style.width = '100%';
                    if (percent) percent.textContent = '100%';

                    const targetTabName = formId === 'zipForm' ? 'zip' : 'excel';
                    const targetUrl = new URL(window.location.href);
                    targetUrl.searchParams.set('tab', targetTabName);
                    window.location.href = targetUrl.toString();
                } else {
                    let errorMessage = 'Terjadi kesalahan saat mengunggah (HTTP ' + xhr.status + '). Silakan coba lagi.';
                    const contentType = xhr.getResponseHeader('Content-Type') || '';

                    if (contentType.includes('application/json')) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            errorMessage = response.message || errorMessage;
                        } catch (error) {
                            // Keep default message when the JSON response cannot be parsed.
                        }
                    }

                    alert(errorMessage);
                    restoreButton();
                }
            };

            xhr.onerror = function () {
                alert('Gagal terhubung ke server. Periksa koneksi jaringan Anda.');
                restoreButton();
            };

            xhr.send(formData);
        });
    }

    // Attach to Excel form
    attachProgressUpload(
        'importForm',
        'submitBtn',
        'excelProgressContainer',
        'excelProgressBar',
        'excelProgressPercent',
        'excelProgressStatus',
        'Memproses Excel...'
    );

    // Attach to ZIP form
    attachProgressUpload(
        'zipForm',
        'zipSubmitBtn',
        'zipProgressContainer',
        'zipProgressBar',
        'zipProgressPercent',
        'zipProgressStatus',
        'Mengekstrak & Memproses ZIP...'
    );
});
</script>
@endsection
