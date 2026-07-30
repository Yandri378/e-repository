@extends('layouts.admin')

@section('title', 'Import Data dari Excel')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-blue-50 via-white to-white">
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 sm:py-10 lg:px-8 space-y-8">

    {{-- ============ HERO ============ --}}
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-700 via-blue-600 to-emerald-600 px-7 py-9 shadow-xl shadow-blue-900/20 sm:px-10 sm:py-11">
        <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-white/10 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-20 left-10 h-48 w-48 rounded-full bg-emerald-300/20 blur-3xl"></div>

        <div class="relative flex items-center gap-4">
            <div class="flex h-13 w-13 shrink-0 items-center justify-center rounded-2xl bg-white/15 ring-1 ring-white/30 backdrop-blur-sm">
                <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                    <path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2z"/>
                    <path d="M9 13l2 2 4-4"/>
                </svg>
            </div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-blue-100">Admin · Import Data</p>
                <h1 class="mt-1 text-xl font-bold text-white sm:text-2xl md:text-3xl">Import Data dari Excel</h1>
                <p class="mt-1 text-sm text-blue-100/80">Upload file .xlsx untuk menambahkan data Skripsi, Magang, PKM, atau Penelitian secara massal.</p>
            </div>
        </div>
    </section>

    {{-- Flash Messages --}}
    @if (session('import_success') !== null)
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-500 text-white shrink-0">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <div>
                    <p class="font-bold text-emerald-800">Import selesai!</p>
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

    {{-- Baris gagal --}}
    @if (count(session('import_errors', [])) > 0)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 overflow-hidden shadow-sm">
            <div class="px-5 py-4 bg-amber-100/60 border-b border-amber-200">
                <p class="font-bold text-amber-800 flex items-center gap-2">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    {{ count(session('import_errors')) }} baris gagal diimpor
                </p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-amber-100">
                    <thead class="bg-amber-50">
                        <tr class="text-xs font-semibold text-amber-700 uppercase tracking-wide">
                            <th class="px-4 py-3 text-left">Baris</th>
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Judul</th>
                            <th class="px-4 py-3 text-left">Alasan Gagal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-100 bg-white">
                        @foreach (session('import_errors') as $err)
                            <tr>
                                <td class="px-4 py-3 font-mono text-amber-700">#{{ $err['row'] }}</td>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- ============ FORM UPLOAD ============ --}}
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

                    <button type="submit" id="submitBtn"
                            class="w-full flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-emerald-500 px-6 py-3.5 text-sm font-bold text-white shadow-md shadow-blue-500/30 transition-all duration-200 hover:scale-[1.01] hover:shadow-lg active:scale-[0.99]">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2z"/><path d="M9 13l2 2 4-4"/></svg>
                        Import Data Excel
                    </button>
                </form>
            </div>
        </div>

        {{-- ============ PANDUAN & TEMPLATE ============ --}}
        <div class="space-y-5">

            {{-- Download Template --}}
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Drag & drop
    const dropZone  = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileName  = document.getElementById('fileName');
    const fileNameText = document.getElementById('fileNameText');

    fileInput.addEventListener('change', function () {
        if (this.files.length) {
            fileNameText.textContent = this.files[0].name;
            fileName.classList.remove('hidden');
            fileName.classList.add('flex');
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        }
    });

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

    // Radio kategori styling
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
            window.location.href = url.toString();
        });
    });

    // Submit loading state
    document.getElementById('importForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.innerHTML = '<svg class="animate-spin" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke-opacity="1"/></svg> Memproses...';
        btn.disabled = true;
    });
});
</script>
@endsection
