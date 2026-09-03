@php
    $openCategories = collect($categories)->filter(fn ($label, $key) => $uploadStatuses[$key] ?? false);
@endphp

{{-- Ticker Banner --}}
<div id="session-ticker-container" class="mb-6 overflow-hidden rounded-2xl border {{ $openCategories->isNotEmpty() ? 'border-emerald-200 bg-emerald-500/10 text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300' : 'border-blue-200 bg-blue-500/10 text-blue-800 dark:border-blue-800 dark:bg-blue-950/30 dark:text-blue-300' }} p-3 shadow-sm backdrop-blur-sm">
    <div class="flex items-center gap-3">
        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl {{ $openCategories->isNotEmpty() ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/30' : 'bg-blue-500 text-white shadow-md shadow-blue-500/30' }}">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
        </span>
        <div class="overflow-hidden flex-1">
            <p id="session-ticker-text" class="text-xs sm:text-sm font-semibold truncate">
                @if ($openCategories->isNotEmpty())
                    📢 Sesi <strong class="text-emerald-700 dark:text-emerald-300">{{ $openCategories->values()->join(', ') }}</strong> sedang dibuka oleh Admin. Anda dapat langsung mengunggah dokumen sekarang!
                @else
                    ℹ️ Semua sesi upload saat ini sedang ditutup. Tombol upload akan aktif otomatis saat admin membuka sesi di dashboard admin.
                @endif
            </p>
        </div>
    </div>
</div>

{{-- Main Workspace Grid --}}
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    {{-- Left: Upload Action Cards (2 Cols on lg) --}}
    <div class="lg:col-span-2 space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Pilihan Form Upload</h3>
            <a href="{{ route('guides.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                Panduan & Template
            </a>
        </div>

        <div class="grid grid-cols-1 gap-4">
            @foreach ($categories as $key => $label)
                @php $isOpen = $uploadStatuses[$key] ?? false; @endphp
                <article class="role-session-card flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border p-5 transition-all duration-300 {{ $isOpen ? 'border-emerald-200 bg-gradient-to-r from-emerald-50/50 via-white to-white shadow-sm hover:shadow-md hover:border-emerald-300 dark:border-emerald-800/60 dark:from-emerald-950/20 dark:via-slate-900 dark:to-slate-900' : 'border-slate-200 bg-slate-50/70 dark:border-slate-800 dark:bg-slate-900/50 opacity-90' }}"
                         data-kategori="{{ $key }}"
                         data-upload-url="{{ route('repository.create', $key) }}">
                    <div class="space-y-1.5 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="role-status-badge inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-bold {{ $isOpen ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-400' }}">
                                <span class="role-status-dot h-2 w-2 rounded-full {{ $isOpen ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400' }}"></span>
                                <span class="role-status-text">{{ $isOpen ? 'Dibuka Admin' : 'Ditutup' }}</span>
                            </span>
                            <h4 class="text-base font-bold text-slate-900 dark:text-white">{{ $label }}</h4>
                        </div>
                        <p class="role-status-desc text-xs text-slate-500 dark:text-slate-400">
                            {{ $isOpen ? 'Sesi upload sedang dibuka. Klik tombol upload untuk mengunggah dokumen Anda.' : 'Menunggu admin membuka sesi upload di dashboard admin.' }}
                        </p>
                    </div>

                    <div class="role-action-container flex items-center gap-2 shrink-0">
                        @if ($isOpen)
                            <a href="{{ route('repository.create', $key) }}"
                               class="role-upload-btn inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-2.5 text-xs font-bold text-white shadow-md shadow-blue-500/20 transition-all duration-200 hover:scale-[1.02] hover:shadow-lg active:scale-[0.98]">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                <span>Upload {{ $label }}</span>
                            </a>
                            <a href="#my-documents" class="hidden sm:inline-flex items-center gap-1 rounded-xl border border-slate-200 px-3 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800 transition">
                                Dokumen Saya
                            </a>
                        @else
                            <button type="button" disabled
                                    class="role-upload-btn inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-100 px-5 py-2.5 text-xs font-semibold text-slate-400 cursor-not-allowed dark:border-slate-800 dark:bg-slate-800/80 dark:text-slate-500">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                <span>Sesi Ditutup</span>
                            </button>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>

    {{-- Right: Summary Panel (1 Col on lg) --}}
    <div class="space-y-4">
        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Ringkasan Akun</h3>

        <div class="rounded-2xl border border-blue-100 bg-gradient-to-br from-blue-50/50 via-white to-white p-5 shadow-sm dark:border-slate-800 dark:from-slate-900/60 dark:via-slate-900 dark:to-slate-950 space-y-4">
            <div>
                <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $documents->count() }}</p>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Dokumen diunggah oleh akun Anda</p>
            </div>

            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                <div class="rounded-xl bg-emerald-500/10 p-3 dark:bg-emerald-500/10">
                    <p id="summary-open-count" class="text-lg font-bold text-emerald-600 dark:text-emerald-400">{{ $openCategories->count() }}</p>
                    <p class="text-[11px] text-slate-600 dark:text-slate-400">Sesi Dibuka</p>
                </div>
                <div class="rounded-xl bg-slate-100 p-3 dark:bg-slate-800/60">
                    <p id="summary-closed-count" class="text-lg font-bold text-slate-600 dark:text-slate-400">{{ count($categories) - $openCategories->count() }}</p>
                    <p class="text-[11px] text-slate-600 dark:text-slate-400">Sesi Ditutup</p>
                </div>
            </div>

            @php $wa = \App\Models\RepositorySetting::where('key','admin_whatsapp')->value('value'); @endphp
            @if($wa)
                @php $waClean = preg_replace('/[^0-9]/', '', $wa); @endphp
                <div class="pt-2">
                    <a href="https://wa.me/{{ $waClean }}" target="_blank"
                       class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white shadow-md shadow-emerald-600/20 hover:bg-emerald-700 transition">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.698c.969.541 1.956.88 2.796.88 3.179 0 5.767-2.587 5.767-5.766.001-3.187-2.575-5.767-5.767-5.767zm3.391 8.232c-.143.402-.835.772-1.161.821-.326.049-.751.07-2.213-.538-1.745-.726-2.87-2.485-2.957-2.602-.087-.116-.708-.941-.708-1.794s.449-1.272.609-1.446c.16-.175.348-.218.464-.218.116 0 .232.002.333.007.107.006.25-.041.391.297.144.348.493 1.202.536 1.29.043.087.073.189.014.305-.058.116-.087.189-.174.29-.087.102-.183.228-.261.306-.087.087-.179.182-.077.356.101.174.449.741.964 1.201.662.591 1.221.774 1.395.861.174.087.276.073.377-.044.102-.116.435-.508.551-.682.116-.174.232-.145.391-.087.16.058 1.015.479 1.189.566.174.087.29.131.333.203.043.073.043.421-.1 0.823z"/></svg>
                        Hubungi Admin Repository
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Dynamic Real-time Sync Script --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const sessionCards = document.querySelectorAll('.role-session-card[data-kategori]');
    if (!sessionCards.length) return;

    const categoriesMap = @json($categories);

    async function syncRoleWorkspaceStatuses() {
        try {
            const res = await fetch('/api/upload-statuses', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) return;
            const statuses = await res.json();

            let openCount = 0;
            let openCategoryNames = [];

            sessionCards.forEach(card => {
                const kategori = card.getAttribute('data-kategori');
                const uploadUrl = card.getAttribute('data-upload-url');
                const isOpen = Boolean(statuses[kategori]);
                const label = categoriesMap[kategori] || kategori.toUpperCase();

                if (isOpen) {
                    openCount++;
                    openCategoryNames.push(label);
                }

                // Update card styles
                if (isOpen) {
                    card.className = 'role-session-card flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border p-5 transition-all duration-300 border-emerald-200 bg-gradient-to-r from-emerald-50/50 via-white to-white shadow-sm hover:shadow-md hover:border-emerald-300 dark:border-emerald-800/60 dark:from-emerald-950/20 dark:via-slate-900 dark:to-slate-900';
                } else {
                    card.className = 'role-session-card flex flex-col sm:flex-row sm:items-center justify-between gap-4 rounded-2xl border p-5 transition-all duration-300 border-slate-200 bg-slate-50/70 dark:border-slate-800 dark:bg-slate-900/50 opacity-90';
                }

                // Update badge
                const badge = card.querySelector('.role-status-badge');
                if (badge) {
                    badge.className = 'role-status-badge inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-bold ' +
                        (isOpen ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : 'bg-slate-200 text-slate-700 dark:bg-slate-800 dark:text-slate-400');
                    const dot = badge.querySelector('.role-status-dot');
                    if (dot) dot.className = 'role-status-dot h-2 w-2 rounded-full ' + (isOpen ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400');
                    const text = badge.querySelector('.role-status-text');
                    if (text) text.textContent = isOpen ? 'Dibuka Admin' : 'Ditutup';
                }

                // Update description
                const desc = card.querySelector('.role-status-desc');
                if (desc) {
                    desc.textContent = isOpen
                        ? 'Sesi upload sedang dibuka. Klik tombol upload untuk mengunggah dokumen Anda.'
                        : 'Menunggu admin membuka sesi upload di dashboard admin.';
                }

                // Update action button
                const container = card.querySelector('.role-action-container');
                if (container) {
                    if (isOpen) {
                        container.innerHTML = `
                            <a href="${uploadUrl}" class="role-upload-btn inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-2.5 text-xs font-bold text-white shadow-md shadow-blue-500/20 transition-all duration-200 hover:scale-[1.02] hover:shadow-lg active:scale-[0.98]">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                <span>Upload ${label}</span>
                            </a>
                            <a href="#my-documents" class="hidden sm:inline-flex items-center gap-1 rounded-xl border border-slate-200 px-3 py-2.5 text-xs font-semibold text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800 transition">
                                Dokumen Saya
                            </a>
                        `;
                    } else {
                        container.innerHTML = `
                            <button type="button" disabled class="role-upload-btn inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-slate-100 px-5 py-2.5 text-xs font-semibold text-slate-400 cursor-not-allowed dark:border-slate-800 dark:bg-slate-800/80 dark:text-slate-500">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                <span>Sesi Ditutup</span>
                            </button>
                        `;
                    }
                }
            });

            // Update Summary counts
            const totalCategories = Object.keys(categoriesMap).length;
            const openCountEl = document.getElementById('summary-open-count');
            const closedCountEl = document.getElementById('summary-closed-count');
            if (openCountEl) openCountEl.textContent = openCount;
            if (closedCountEl) closedCountEl.textContent = totalCategories - openCount;

            // Update Ticker banner
            const tickerContainer = document.getElementById('session-ticker-container');
            const tickerText = document.getElementById('session-ticker-text');
            if (tickerContainer && tickerText) {
                if (openCount > 0) {
                    tickerContainer.className = 'mb-6 overflow-hidden rounded-2xl border border-emerald-200 bg-emerald-500/10 text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-300 p-3 shadow-sm backdrop-blur-sm';
                    tickerText.innerHTML = `📢 Sesi <strong class="text-emerald-700 dark:text-emerald-300">${openCategoryNames.join(', ')}</strong> sedang dibuka oleh Admin. Anda dapat langsung mengunggah dokumen sekarang!`;
                } else {
                    tickerContainer.className = 'mb-6 overflow-hidden rounded-2xl border border-blue-200 bg-blue-500/10 text-blue-800 dark:border-blue-800 dark:bg-blue-950/30 dark:text-blue-300 p-3 shadow-sm backdrop-blur-sm';
                    tickerText.innerHTML = `ℹ️ Semua sesi upload saat ini sedang ditutup. Tombol upload akan aktif otomatis saat admin membuka sesi di dashboard admin.`;
                }
            }
        } catch (e) {
            // Silently ignore
        }
    }

    // Auto sync every 3 seconds
    setInterval(syncRoleWorkspaceStatuses, 3000);
});
</script>

{{-- Target anchor for smooth scroll --}}
<div id="my-documents" class="scroll-mt-24"></div>
