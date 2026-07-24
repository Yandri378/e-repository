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
                    <a href="{{ route('repository.bebas-pustaka', $document) }}" class="btn primary">Kartu Bebas Pustaka</a>
                @endif
                @if ($document->file_project)
                    <a href="{{ route('repository.project.download', $document) }}" class="btn secondary">Download Project</a>
                @endif
            </div>
        </article>
    @empty
        <div class="empty-state">Belum ada dokumen yang Anda upload.</div>
    @endforelse
</section>
