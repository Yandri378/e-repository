<section class="section list-stack document-history">
    <div class="section-heading">
        <div>
            <p class="eyebrow">ACC Dosen Pembimbing</p>
            <h2>Dokumen Mahasiswa Bimbingan</h2>
        </div>
        <a href="{{ route('dosen.approvals.index') }}" class="text-link">Lihat semua</a>
    </div>
    @forelse ($approvalDocuments as $document)
        <article class="document-row">
            <div>
                <span class="badge">{{ $document->dosen_approved_at ? 'SUDAH ACC' : 'MENUNGGU ACC' }}</span>
                <h2>{{ $document->judul }}</h2>
                <p>{{ $document->nama }} | {{ $document->nim ?: '-' }} | {{ strtoupper($document->kategori) }} | {{ $document->tahun }}</p>
            </div>
            <div class="inline-actions">
                @if ($document->file_dokumen)
                    <a class="btn secondary" href="{{ route('repository.file.download', $document) }}">Download PDF</a>
                @endif
                @if ($document->file_project)
                    <a class="btn secondary" href="{{ route('repository.project.download', $document) }}">Download Project</a>
                @endif
                @if (! $document->dosen_approved_at && $document->status === 'pending')
                    <form method="POST" action="{{ route('dosen.approvals.approve', $document) }}">
                        @csrf
                        @method('PATCH')
                        <button class="btn primary" type="submit">ACC</button>
                    </form>
                    <form method="POST" action="{{ route('dosen.approvals.reject', $document) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="catatan_dosen" value="Dokumen perlu diperbaiki.">
                        <button class="btn secondary" type="submit">Tolak</button>
                    </form>
                @endif
            </div>
        </article>
    @empty
        <div class="empty-state">Belum ada dokumen mahasiswa yang menunggu ACC Anda.</div>
    @endforelse
</section>
