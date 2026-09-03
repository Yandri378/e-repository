<section class="rounded-2xl border border-blue-100 bg-white/90 p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900/90 backdrop-blur-sm">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-md shadow-amber-500/30 text-white">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-base font-semibold text-slate-800 dark:text-slate-100">ACC Dokumen Bimbingan Mahasiswa</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400">Persetujuan skripsi dan magang mahasiswa bimbingan Anda sebelum verifikasi admin</p>
            </div>
        </div>
        <a href="{{ route('dosen.approvals.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
            <span>Lihat Semua Bimbingan</span>
            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
        </a>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-xs font-semibold text-emerald-700 dark:text-emerald-300">
            {{ session('status') }}
        </div>
    @endif

    <div class="divide-y divide-slate-100 dark:divide-slate-800">
        @forelse ($approvalDocuments as $document)
            @php
                $isApproved = !empty($document->dosen_approved_at);
            @endphp
            <article class="py-5 first:pt-0 last:pb-0 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="space-y-2 min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold {{ $isApproved ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300' }}">
                            {{ $isApproved ? 'SUDAH DI-ACC' : 'MENUNGGU ACC DOSEN' }}
                        </span>
                        <span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-bold uppercase text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">
                            {{ strtoupper($document->kategori) }}
                        </span>
                        <span class="text-xs text-slate-400">Tahun {{ $document->tahun }}</span>
                    </div>

                    <h3 class="text-base font-bold text-slate-900 dark:text-white leading-snug">
                        {{ $document->judul }}
                    </h3>

                    <p class="text-xs text-slate-600 dark:text-slate-400">
                        <strong class="text-slate-800 dark:text-slate-200">{{ $document->nama }}</strong>
                        &middot; NIM: {{ $document->nim ?: '-' }}
                        &middot; {{ $document->programStudi?->nama ?: 'Prodi belum dipilih' }}
                    </p>

                    @if ($document->catatan_dosen)
                        <div class="rounded-lg bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/40 p-2.5 text-xs text-amber-800 dark:text-amber-300">
                            <strong>Catatan Dosen:</strong> {{ $document->catatan_dosen }}
                        </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-wrap items-center gap-2 shrink-0">
                    @if ($document->file_dokumen)
                        <a href="{{ route('admin.documents.download', $document) }}" target="_blank"
                           class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:border-blue-300 hover:text-blue-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700 transition">
                            <svg class="h-3.5 w-3.5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                            <span>Lihat PDF</span>
                        </a>
                    @endif

                    @if ($document->file_project)
                        <a href="{{ route('repository.project.download', $document) }}"
                           class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:border-indigo-300 hover:text-indigo-700 hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700 transition">
                            <svg class="h-3.5 w-3.5 text-indigo-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
                            <span>Project ZIP</span>
                        </a>
                    @endif

                    @if (! $isApproved && $document->status === 'pending')
                        {{-- Approve Button --}}
                        <form method="POST" action="{{ route('dosen.approvals.approve', $document) }}"
                              onsubmit="return confirm('Apakah Anda yakin ingin memberikan ACC pada dokumen {{ $document->nama }}?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 px-4 py-2 text-xs font-bold text-white shadow-md shadow-emerald-600/20 hover:scale-[1.02] hover:shadow-lg transition">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                <span>ACC Dokumen</span>
                            </button>
                        </form>

                        {{-- Reject Button / Modal trigger --}}
                        <button type="button" onclick="toggleRejectBox('reject-box-{{ $document->id }}')"
                                class="inline-flex items-center gap-1.5 rounded-xl border border-rose-200 bg-rose-50 px-3.5 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100 dark:border-rose-800/40 dark:bg-rose-950/20 dark:text-rose-300 transition">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            <span>Tolak/Revisi</span>
                        </button>
                    @endif
                </div>

                {{-- Collapsible Reject Form --}}
                @if (! $isApproved && $document->status === 'pending')
                    <div id="reject-box-{{ $document->id }}" class="hidden w-full pt-3">
                        <form method="POST" action="{{ route('dosen.approvals.reject', $document) }}" class="rounded-xl border border-rose-200 bg-rose-50/70 p-3.5 dark:border-rose-900 dark:bg-rose-950/30 flex flex-col sm:flex-row gap-2">
                            @csrf
                            @method('PATCH')
                            <input type="text" name="catatan_dosen" required placeholder="Tulis alasan penolakan / catatan revisi untuk mahasiswa..."
                                   class="flex-1 rounded-lg border border-rose-300 bg-white px-3 py-1.5 text-xs text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-rose-500 dark:border-rose-800 dark:bg-slate-900 dark:text-white">
                            <div class="flex items-center gap-2">
                                <button type="submit" class="rounded-lg bg-rose-600 px-4 py-1.5 text-xs font-bold text-white shadow hover:bg-rose-700 transition shrink-0">
                                    Kirim Penolakan
                                </button>
                                <button type="button" onclick="toggleRejectBox('reject-box-{{ $document->id }}')" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 shrink-0">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </article>
        @empty
            <div class="py-10 text-center">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-800 dark:text-slate-200">Tidak ada dokumen yang menunggu ACC</h3>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Semua dokumen mahasiswa bimbingan telah di-ACC atau belum ada mahasiswa yang mengunggah skripsi/magang.</p>
            </div>
        @endforelse
    </div>
</section>

<script>
function toggleRejectBox(id) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.toggle('hidden');
    }
}
</script>
