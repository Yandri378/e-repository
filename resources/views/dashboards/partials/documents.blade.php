<section class="section list-stack document-history">
    <div class="section-heading">
        <div>
            <p class="eyebrow">Riwayat Upload</p>
            <h2>Dokumen Anda</h2>
        </div>
    </div>
    @forelse ($documents as $document)
        <article class="document-row">
            <div>
                <span class="badge">{{ strtoupper($document->status) }}</span>
                <h2>{{ $document->judul }}</h2>
                <p>{{ $document->kategori }} | {{ $document->tahun }}</p>
                @if ($document->dosen_pembimbing_id)
                    <p>ACC dosen: {{ $document->dosen_approved_at ? 'Sudah ACC' : ($document->status === 'ditolak' ? 'Ditolak' : 'Menunggu ACC') }}</p>
                @endif
            </div>

            <div class="inline-actions">
                @if ($document->file_dokumen)
                    <button class="btn secondary preview-btn" data-meta-url="{{ route('repository.meta', $document) }}">Preview</button>
                    <a href="{{ route('repository.file.download', $document) }}" class="btn secondary">Download PDF</a>
                @endif
                @if ($document->file_project)
                    <a href="{{ route('repository.project.download', $document) }}" class="btn secondary">Download Project</a>
                @endif
            </div>

            {{-- Kartu Bebas Pustaka Section (only for skripsi) --}}
            @if ($document->file_dokumen && in_array($document->kategori, ['skripsi', 'magang'], true))
                @php
                    $blockers   = $document->bebasPustakaBlockers();
                    $canDownload = empty($blockers);
                @endphp
                <div class="bebas-pustaka-card {{ $canDownload ? 'bebas-pustaka-card--ready' : '' }}">
                    <div class="bebas-pustaka-card__header">
                        <div class="bebas-pustaka-card__icon {{ $canDownload ? 'bebas-pustaka-card__icon--ready' : '' }}">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                            </svg>
                        </div>
                        <div>
                            <strong class="bebas-pustaka-card__title">Kartu Bebas Pustaka</strong>
                            <span class="bebas-pustaka-card__subtitle">
                                {{ $canDownload ? 'Semua syarat terpenuhi — siap diunduh' : 'Belum semua syarat terpenuhi' }}
                            </span>
                        </div>
                        @if ($canDownload)
                            <a href="{{ route('repository.bebas-pustaka', $document) }}" class="bebas-pustaka-download-btn">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="7 10 12 15 17 10"/>
                                    <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                Unduh Kartu
                            </a>
                        @else
                            <span class="bebas-pustaka-download-btn bebas-pustaka-download-btn--disabled" title="Selesaikan semua syarat terlebih dahulu">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                                Terkunci
                            </span>
                        @endif
                    </div>

                    <div class="bebas-pustaka-checklist">
                        {{-- 1. Tidak ada pinjaman buku --}}
                        <div class="bebas-checklist-item {{ !$document->has_active_loans ? 'bebas-checklist-item--done' : 'bebas-checklist-item--blocked' }}">
                            <span class="bebas-checklist-status">
                                @if (!$document->has_active_loans)
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                @else
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                @endif
                            </span>
                            <span class="bebas-checklist-text">
                                Tidak ada pinjaman buku perpustakaan
                                @if ($document->has_active_loans)
                                    <small>— Harap kembalikan buku yang dipinjam ke perpustakaan</small>
                                @endif
                            </span>
                        </div>

                        {{-- 2. ACC dosen --}}
                        <div class="bebas-checklist-item {{ $document->dosen_approved_at ? 'bebas-checklist-item--done' : '' }}">
                            <span class="bebas-checklist-status">
                                @if ($document->dosen_approved_at)
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                @else
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                @endif
                            </span>
                            <span class="bebas-checklist-text">
                                Dokumen sudah di-ACC dosen pembimbing
                                @if (!$document->dosen_approved_at)
                                    <small>— Menunggu persetujuan dosen pembimbing</small>
                                @else
                                    <small>— {{ $document->dosen_approved_at->format('d M Y') }}</small>
                                @endif
                            </span>
                        </div>

                        {{-- 3. Soft copy PDF lengkap --}}
                        <div class="bebas-checklist-item {{ $document->pdf_kelengkapan_confirmed ? 'bebas-checklist-item--done' : '' }}">
                            <span class="bebas-checklist-status">
                                @if ($document->pdf_kelengkapan_confirmed)
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                @else
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                @endif
                            </span>
                            <span class="bebas-checklist-text">
                                Soft copy PDF lengkap (pengesahan + persetujuan + orisinalitas)
                                @if (!$document->pdf_kelengkapan_confirmed)
                                    <small>— Menunggu konfirmasi admin perpustakaan bahwa PDF sudah memuat scan halaman pengesahan, halaman persetujuan, dan pernyataan orisinalitas</small>
                                @endif
                            </span>
                        </div>

                        {{-- 4. Hard copy diserahkan --}}
                        <div class="bebas-checklist-item {{ $document->hard_copy_submitted ? 'bebas-checklist-item--done' : '' }}">
                            <span class="bebas-checklist-status">
                                @if ($document->hard_copy_submitted)
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                @else
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                @endif
                            </span>
                            <span class="bebas-checklist-text">
                                Hard copy (buku jilid) diserahkan ke perpustakaan
                                @if (!$document->hard_copy_submitted)
                                    <small>— Serahkan buku fisik skripsi langsung ke perpustakaan, lalu minta petugas mengkonfirmasi</small>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            @endif

        </article>
    @empty
        <div class="empty-state">Belum ada dokumen yang Anda upload.</div>
    @endforelse
</section>

