@extends('layouts.app')

@section('title', $document->judul)

@section('content')
<section class="page-hero compact reveal">
    <p class="eyebrow">Viewer Repository</p>
    <h1>{{ $document->judul }}</h1>
    <button id="page-fullscreen-btn" class="viewer-page-fullscreen" aria-label="Fullscreen">⤢</button>
    <p>{{ $document->nama }} | {{ $document->tahun }} | {{ strtoupper($document->kategori) }}</p>
</section>

<section class="section">
    <div class="viewer-shell reveal" id="viewer-shell">
        <div class="viewer-frame" oncontextmenu="return false">
            <div class="viewer-iframe-wrap" style="position:relative;">
                <iframe id="viewer-iframe" src="{{ $fileUrl }}" title="Viewer {{ $document->judul }}" style="width:100%;height:80vh;border:0;"></iframe>
                <div id="viewer-overlay" style="position:absolute;inset:0;" aria-hidden="true"></div>
            </div>
            @if(! empty($watermark))
                <div class="viewer-watermark">{{ $watermark }}</div>
            @endif
        </div>
    </div>
    <script>
        (function () {
            const overlay = document.getElementById('viewer-overlay');
            const iframe = document.getElementById('viewer-iframe');

            // Prevent right-click/context menu on overlay
            overlay.addEventListener('contextmenu', function (e) { e.preventDefault(); });

            // Prevent dragstart and selection
            overlay.addEventListener('dragstart', function (e) { e.preventDefault(); });
            document.addEventListener('selectstart', function (e) { e.preventDefault(); });

            // Allow scrolling via wheel: forward wheel events to iframe's contentWindow
            overlay.addEventListener('wheel', function (e) {
                try {
                    const win = iframe.contentWindow;
                    if (win && typeof win.scrollBy === 'function') {
                        win.scrollBy({ top: e.deltaY, left: 0, behavior: 'auto' });
                        e.preventDefault();
                    }
                } catch (err) {
                    // cross-origin or other issues: fallback to letting page scroll
                }
            }, { passive: false });

            // Block common save/print/view-source shortcuts globally
            document.addEventListener('keydown', function (e) {
                if (e.ctrlKey || e.metaKey) {
                    const blocked = ['s', 'p', 'u', 'S', 'P', 'U'];
                    if (blocked.includes(e.key)) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                }
            });
        })();
    </script>
    <p class="viewer-note">Dokumen hanya disediakan untuk dilihat dan di-scroll pada halaman ini.</p>
</section>
@endsection
