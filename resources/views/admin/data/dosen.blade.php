@extends('layouts.admin')

@section('title', 'Data Dosen')

@section('content')

{{-- ============ HERO SECTION ============ --}}
<section class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-700 via-blue-600 to-sky-400 px-6 py-10 sm:px-10 sm:py-12 shadow-lg shadow-blue-200/60 mb-8">
    {{-- decorative blobs --}}
    <div class="pointer-events-none absolute -top-16 -right-10 h-56 w-56 rounded-full bg-white/10 blur-2xl animate-pulse"></div>
    <div class="pointer-events-none absolute -bottom-20 -left-10 h-64 w-64 rounded-full bg-sky-300/20 blur-3xl"></div>

    <div class="relative z-10">
        <p class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-medium tracking-wide text-white/90 backdrop-blur-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5-10-5z"/>
                <path d="M6 12v5c0 1.66 2.69 3 6 3s6-1.34 6-3v-5"/>
            </svg>
            Data Dosen
        </p>

        <h1 class="mt-3 text-2xl sm:text-3xl font-bold text-white drop-shadow-sm">
            {{ $kategori ? 'Data '.strtoupper($kategori) : 'Data PKM dan Penelitian' }}
        </h1>
        <p class="mt-1 text-sm text-blue-100">
            Kelola dan pantau seluruh data PKM &amp; Penelitian dosen dalam satu tempat.
        </p>

        {{-- Filter Tabs --}}
        <div class="mt-6 flex flex-wrap gap-2">
            @php
                $active = request('kategori') ?? $kategori ?? null;
            @endphp
            <a href="{{ route('admin.data.dosen') }}"
               class="group inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition-all duration-200
               {{ !$active ? 'bg-white text-blue-700 shadow-md shadow-blue-900/20' : 'bg-white/10 text-white hover:bg-white/20' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/>
                    <rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/>
                </svg>
                Semua
            </a>
            <a href="{{ route('admin.data.dosen', 'pkm') }}"
               class="group inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition-all duration-200
               {{ $active === 'pkm' ? 'bg-white text-blue-700 shadow-md shadow-blue-900/20' : 'bg-white/10 text-white hover:bg-white/20' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2a10 10 0 1 0 10 10"/><path d="M12 2v10l7 7"/>
                </svg>
                PKM
            </a>
            <a href="{{ route('admin.data.dosen', 'penelitian') }}"
               class="group inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition-all duration-200
               {{ $active === 'penelitian' ? 'bg-white text-blue-700 shadow-md shadow-blue-900/20' : 'bg-white/10 text-white hover:bg-white/20' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4a7 7 0 1 0 0 14 7 7 0 0 0 0-14z"/><path d="M21 21l-4.35-4.35"/>
                </svg>
                Penelitian
            </a>
        </div>

        {{-- Search + Export --}}
        <form method="GET" class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
            <div class="relative flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute left-3.5 top-1/2 h-4.5 w-4.5 -translate-y-1/2 text-blue-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>
                </svg>
                <input
                    type="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama, NIDN, judul, tahun..."
                    class="w-full rounded-xl border-0 bg-white/95 py-2.5 pl-10 pr-4 text-sm text-gray-700 shadow-inner placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-white transition-all"
                >
            </div>
            <button type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-semibold text-blue-700 shadow-md transition-all duration-200 hover:bg-blue-50 hover:shadow-lg active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>
                </svg>
                Cari
            </button>

            @php
                $k = request('kategori') ?? $kategori ?? '';
                $base = '/admin/data-dosen/'.($k ? $k.'/' : '').'export/';
            @endphp
            <div class="flex gap-2">
                <a href="{{ route('admin.documents.import', ['kategori' => $k ?: 'penelitian']) }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-blue-700 shadow-md transition-all duration-200 hover:bg-blue-50 hover:shadow-lg active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    Import
                </a>
                <a href="{{ $base }}excel?search={{ urlencode(request('search') ?? '') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-emerald-500/90 px-4 py-2.5 text-sm font-medium text-white shadow-md transition-all duration-200 hover:bg-emerald-500 hover:shadow-lg active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2z"/>
                        <path d="M9 13h6M9 17h6"/>
                    </svg>
                    Excel
                </a>
                <a href="{{ $base }}pdf?search={{ urlencode(request('search') ?? '') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-rose-500/90 px-4 py-2.5 text-sm font-medium text-white shadow-md transition-all duration-200 hover:bg-rose-500 hover:shadow-lg active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2z"/>
                        <path d="M9 17l1.5-4L12 17l1.5-4 1.5 4"/>
                    </svg>
                    PDF
                </a>
            </div>
        </form>
    </div>
</section>

{{-- ============ TABLE SECTION ============ --}}
<section class="rounded-2xl border border-blue-100 bg-white shadow-sm shadow-blue-100/40 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-blue-100 text-sm">
            <thead class="bg-gradient-to-r from-blue-50 to-sky-50 sticky top-0 z-10">
                <tr class="text-left text-xs font-semibold uppercase tracking-wider text-blue-700">
                    <th class="px-5 py-3.5">Nama</th>
                    <th class="px-5 py-3.5">Kategori</th>
                    <th class="px-5 py-3.5">Judul</th>
                    <th class="px-5 py-3.5">NIDN</th>
                    <th class="px-5 py-3.5">Email</th>
                    <th class="px-5 py-3.5">Prodi</th>
                    <th class="px-5 py-3.5">Tahun</th>
                    <th class="px-5 py-3.5">Detail</th>
                    <th class="px-5 py-3.5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-blue-50">
                @forelse ($documents as $index => $document)
                    <tr class="group transition-colors duration-150 hover:bg-blue-50/60 animate-[fadeIn_0.3s_ease-in-out]"
                        style="animation-delay: {{ $index * 30 }}ms">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-sky-400 text-xs font-semibold text-white shadow-sm ring-2 ring-white transition-transform duration-200 group-hover:scale-105">
                                    {{ strtoupper(substr($document->nama ?? '-', 0, 2)) }}
                                </div>
                                <span class="font-medium text-gray-800">{{ $document->nama }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            @php
                                $isPkm = strtolower($document->kategori) === 'pkm';
                            @endphp
                            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold
                                {{ $isPkm ? 'bg-blue-100 text-blue-700' : 'bg-sky-100 text-sky-700' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $isPkm ? 'bg-blue-500' : 'bg-sky-500' }}"></span>
                                {{ strtoupper($document->kategori) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 max-w-xs">
                            <span class="line-clamp-2 text-gray-700">{{ $document->judul }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $document->nidn ?: '-' }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $document->email ?: '-' }}</td>
                        <td class="px-5 py-3.5 text-gray-600">{{ $document->programStudi?->nama ?: '-' }}</td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center rounded-lg bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">
                                {{ $document->tahun }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 max-w-xs">
                            <span class="line-clamp-2 text-gray-500">{{ $document->detail ?: $document->abstrak ?: '-' }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <form method="POST" action="{{ route('admin.documents.destroy', $document) }}"
                                onsubmit="return confirm(@js('Hapus data dokumen '.$document->judul.' secara permanen? File PDF dan project terkait juga akan dihapus.'))">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-red-200 bg-white px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-5 py-16">
                            <div class="flex flex-col items-center justify-center gap-3 text-center">
                                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-blue-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16v16H4z" opacity="0"/>
                                        <path d="M9 17H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v5"/>
                                        <path d="M13 17l4 4 6-6"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-500">Belum ada data dosen.</p>
                                <p class="text-xs text-gray-400">Data akan muncul di sini setelah tersedia.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($documents->hasPages())
        <div class="border-t border-blue-100 bg-blue-50/40 px-5 py-4">
            {{ $documents->links() }}
        </div>
    @endif
</section>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

@endsection
