@extends('layouts.admin')

@section('title', 'Pengaturan Admin')

@section('content')
<section class="section">
    <h1>Pengaturan Kontak Admin</h1>

    @if(session('status'))
        <div class="flash-message">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" class="settings-form">
        @csrf

        <div class="form-row">
            <label for="admin_whatsapp">Nomor WhatsApp (format internasional tanpa +, e.g. 62853...)</label>
            <input id="admin_whatsapp" name="admin_whatsapp" type="text" value="{{ old('admin_whatsapp', $whatsapp) }}" />
        </div>

        <div class="form-row">
            <label for="admin_email">Email Admin (pemberitahuan)</label>
            <input id="admin_email" name="admin_email" type="email" value="{{ old('admin_email', $email) }}" />
        </div>

        <div class="form-actions">
            <button class="btn primary" type="submit">Simpan Perubahan</button>
            <a href="{{ route('admin.dashboard') }}" class="btn">Batal</a>
        </div>
    </form>
</section>
@endsection
