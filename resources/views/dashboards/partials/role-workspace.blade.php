@php
    $openCategories = collect($categories)->filter(fn ($label, $key) => $uploadStatuses[$key] ?? false);
@endphp

@if ($openCategories->isNotEmpty())
    <section class="session-ticker" aria-label="Informasi sesi kompre">
        <div class="ticker-track">
            <span>
                Running text: form ini dibuka jika sesi kompre dimulai. Saat ini sesi {{ $openCategories->values()->join(', ') }} sedang dibuka oleh admin.
            </span>
            <span aria-hidden="true">
                Running text: form ini dibuka jika sesi kompre dimulai. Saat ini sesi {{ $openCategories->values()->join(', ') }} sedang dibuka oleh admin.
            </span>
        </div>
    </section>
@else
    <section class="session-ticker muted" aria-label="Informasi sesi kompre">
        <div class="ticker-track">
            <span>Running text: form ini dibuka jika sesi kompre dimulai dengan cara admin mengaktifkan tombol sesi upload di dashboard admin.</span>
            <span aria-hidden="true">Running text: form ini dibuka jika sesi kompre dimulai dengan cara admin mengaktifkan tombol sesi upload di dashboard admin.</span>
        </div>
    </section>
@endif

<section class="section role-dashboard-grid">
    <div class="role-actions-panel">
        <div class="role-panel-header">
            <div>
                <p class="eyebrow">Sesi Upload</p>
                <h2>Pilih form sesuai kebutuhan Anda</h2>
            </div>
            <a class="btn secondary" href="{{ route('guides.index') }}">Panduan</a>
        </div>
        <div class="role-action-list">
            @foreach ($categories as $key => $label)
                @php $isOpen = $uploadStatuses[$key] ?? false; @endphp
                <div class="role-action-item">
                    <div>
                        <span class="status-pill {{ $isOpen ? 'open' : 'closed' }}">{{ $isOpen ? 'Dibuka admin' : 'Ditutup' }}</span>
                        <strong>{{ $label }}</strong>
                        <span>{{ $isOpen ? 'Sesi kompre sedang dibuka. Anda bisa upload dokumen sekarang.' : 'Menunggu admin membuka sesi upload.' }}</span>
                    </div>
                    @if ($isOpen)
                        <div class="role-action-cta">
                            <a class="btn primary" href="{{ route('repository.create', $key) }}">Upload</a>
                            <a class="btn" href="#my-documents">Lihat Dokumen Saya</a>
                        </div>
                    @else
                        <span class="btn disabled">Ditutup</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <div class="role-summary-panel">
        <p class="eyebrow">Ringkasan</p>
        <h2>{{ $documents->count() }} dokumen terakhir</h2>
        <p>Riwayat di bawah hanya menampilkan dokumen milik akun Anda sendiri.</p>
        <div class="summary-mini-list">
            <span><strong>{{ $openCategories->count() }}</strong> sesi dibuka</span>
            <span><strong>{{ count($categories) - $openCategories->count() }}</strong> sesi menunggu</span>
        </div>
        @php $wa = \App\Models\RepositorySetting::where('key','admin_whatsapp')->value('value'); @endphp
        @if($wa)
            @php $waClean = preg_replace('/[^0-9]/', '', $wa); @endphp
            <div style="margin-top:1rem">
                <a class="btn primary" href="https://wa.me/{{ $waClean }}" target="_blank">Hubungi Pustaka via WhatsApp</a>
            </div>
        @endif
    </div>
</section>

{{-- make documents targetable for smooth scroll --}}
<div id="my-documents" class="scroll-offset"></div>
