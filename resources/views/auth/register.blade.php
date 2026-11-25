@extends('layouts.app')

@section('title', 'Daftar Akun Mahasiswa')

@section('content')
<style>
    /* ========================================================
       REGISTER PAGE - MOBILE-FIRST RESPONSIVE DESIGN SYSTEM
       ======================================================== */
    .register-shell {
        min-height: calc(100vh - 140px);
        min-height: calc(100dvh - 140px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 32px 16px;
        background: radial-gradient(circle at 50% 10%, rgba(56, 189, 248, 0.08), transparent 450px),
                    linear-gradient(180deg, rgba(11, 19, 43, 0.85) 0%, rgba(15, 23, 42, 0.95) 100%);
    }

    .register-card {
        width: 100%;
        max-width: 660px;
        background: rgba(15, 23, 42, 0.9);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(56, 189, 248, 0.22);
        border-radius: 20px;
        padding: 36px 32px;
        box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.65), 0 0 0 1px rgba(255, 255, 255, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .register-header {
        text-align: center;
        margin-bottom: 24px;
    }

    .register-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #38BDF8;
        font-size: 11px;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        font-weight: 700;
        background: rgba(56, 189, 248, 0.12);
        border: 1px solid rgba(56, 189, 248, 0.25);
        padding: 4px 12px;
        border-radius: 999px;
        margin-bottom: 10px;
    }

    .register-title {
        font-size: clamp(1.4rem, 4vw, 2rem);
        font-weight: 800;
        color: #FFFFFF;
        letter-spacing: -0.02em;
        line-height: 1.25;
        margin: 0 0 8px;
    }

    .register-subtitle {
        font-size: 13.5px;
        color: #94A3B8;
        line-height: 1.5;
        max-width: 480px;
        margin: 0 auto;
    }

    .dosen-info-box {
        background: rgba(30, 41, 59, 0.6);
        border: 1px solid rgba(56, 189, 248, 0.2);
        border-radius: 12px;
        padding: 12px 16px;
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: #94A3B8;
        line-height: 1.45;
    }

    .dosen-info-box strong {
        color: #38BDF8;
    }

    /* Form Fields Grid */
    .register-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px 18px;
    }

    .col-span-2 {
        grid-column: span 2 / span 2;
    }

    .col-span-1 {
        grid-column: span 1 / span 1;
    }

    .form-item {
        display: flex;
        flex-direction: column;
    }

    .form-item label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #E2E8F0;
        margin-bottom: 6px;
        letter-spacing: 0.01em;
    }

    .form-item label .req {
        color: #EF4444;
        font-weight: 700;
        margin-left: 2px;
    }

    .reg-input,
    .reg-select {
        width: 100%;
        background: rgba(30, 41, 59, 0.75);
        border: 1.5px solid rgba(255, 255, 255, 0.16);
        border-radius: 10px;
        padding: 11px 14px;
        min-height: 46px;
        font-size: 14px;
        color: #FFFFFF;
        outline: none;
        transition: border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
        box-sizing: border-box;
    }

    .reg-input::placeholder {
        color: #64748B;
        font-size: 13.5px;
    }

    .reg-input:focus,
    .reg-select:focus {
        background: rgba(30, 41, 59, 0.95);
        border-color: #38BDF8;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.22);
    }

    .reg-select {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2.2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 16px;
        padding-right: 38px;
        cursor: pointer;
    }

    .reg-select option {
        background: #0F172A;
        color: #FFFFFF;
    }

    /* Submit Button */
    .reg-submit-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: linear-gradient(135deg, #0284c7, #2563eb);
        color: #FFFFFF;
        border: none;
        border-radius: 10px;
        padding: 13px 20px;
        min-height: 48px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
        transition: all 0.2s ease;
        margin-top: 24px;
        user-select: none;
    }

    .reg-submit-btn:hover {
        background: linear-gradient(135deg, #0369a1, #1d4ed8);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.5);
        transform: translateY(-1px);
    }

    .reg-submit-btn:active {
        transform: scale(0.99);
    }

    .reg-footer {
        margin-top: 18px;
        text-align: center;
        font-size: 13.5px;
        color: #94A3B8;
        line-height: 1.5;
    }

    .reg-footer a {
        color: #38BDF8;
        font-weight: 700;
        text-decoration: none;
        margin-left: 4px;
        transition: color 0.2s ease, text-decoration 0.2s ease;
    }

    .reg-footer a:hover {
        color: #7DD3FC;
        text-decoration: underline;
    }

    .error-alert {
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.35);
        border-left: 4px solid #EF4444;
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 22px;
        color: #FCA5A5;
        font-size: 13.5px;
        line-height: 1.5;
    }

    /* ========================================================
       MEDIA QUERIES - RESPONSIVE FOR TABLET & HP
       ======================================================== */

    /* Small Tablet & Mobile Phone (<= 680px) */
    @media (max-width: 680px) {
        .register-shell {
            padding: 16px 12px;
            min-height: calc(100vh - 100px);
            min-height: calc(100dvh - 100px);
        }

        .register-card {
            padding: 24px 18px;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
        }

        .register-header {
            margin-bottom: 20px;
        }

        .register-title {
            font-size: 20px;
        }

        .register-subtitle {
            font-size: 12.5px;
        }

        /* 1 Column stack on mobile for maximum clean readability */
        .register-grid {
            grid-template-columns: 1fr !important;
            gap: 14px;
        }

        .col-span-2,
        .col-span-1 {
            grid-column: span 1 !important;
        }

        /* Prevent auto-zoom on iOS Safari & Chrome Mobile (must be at least 16px) */
        .reg-input,
        .reg-select {
            font-size: 16px !important;
            min-height: 48px;
            padding: 12px 14px;
        }

        .reg-submit-btn {
            min-height: 48px;
            font-size: 15px;
            padding: 13px 16px;
        }
    }

    /* Very Small Screens (<= 380px) */
    @media (max-width: 380px) {
        .register-shell {
            padding: 12px 8px;
        }

        .register-card {
            padding: 18px 14px;
            border-radius: 14px;
        }

        .register-title {
            font-size: 18px;
        }
    }
</style>

<section class="register-shell">
    <div class="register-card">

        {{-- Header --}}
        <div class="register-header">
            <span class="register-eyebrow">Pendaftaran Mahasiswa</span>
            <h1 class="register-title">Buat Akun Mahasiswa</h1>
            <p class="register-subtitle">Lengkapi formulir di bawah untuk mendaftar akun Mahasiswa Universitas Metamedia.</p>
        </div>

        {{-- Info Box Dosen --}}
        <div class="dosen-info-box">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#38BDF8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="16" x2="12" y2="12"></line>
                <line x1="12" y1="8" x2="12.01" y2="8"></line>
            </svg>
            <span><strong>Informasi untuk Dosen:</strong> Pendaftaran akun Dosen dilakukan langsung oleh Administrator Perpustakaan. Dosen tidak perlu mendaftar mandiri.</span>
        </div>

        {{-- Error Alert --}}
        @if ($errors->any())
            <div class="error-alert">
                <strong style="color: #F87171; display: block; margin-bottom: 4px;">Pendaftaran Gagal:</strong>
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" id="register-form">
            @csrf
            <input type="hidden" name="role" value="mahasiswa">

            {{-- Form Grid --}}
            <div class="register-grid">
                {{-- Nama Lengkap --}}
                <div class="form-item col-span-2">
                    <label for="name-field">Nama Lengkap <span class="req">*</span></label>
                    <input type="text" id="name-field" name="name" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso" required autocomplete="name" class="reg-input">
                </div>

                {{-- NIM Mahasiswa --}}
                <div class="form-item col-span-1">
                    <label for="nim-field">NIM (Nomor Induk Mahasiswa) <span class="req">*</span></label>
                    <input type="text" id="nim-field" name="nim" value="{{ old('nim') }}" placeholder="Contoh: 202201001" required class="reg-input">
                </div>

                {{-- Program Studi --}}
                <div class="form-item col-span-1">
                    <label for="prodi-field">Program Studi <span class="req">*</span></label>
                    <select id="prodi-field" name="program_studi_id" required class="reg-select">
                        <option value="">-- Pilih Program Studi --</option>
                        @foreach ($programStudi as $prodi)
                            <option value="{{ $prodi->id }}" @selected(old('program_studi_id') == $prodi->id)>
                                {{ $prodi->nama }} ({{ $prodi->jenjang }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Email --}}
                <div class="form-item col-span-1">
                    <label for="email-field">Alamat Email <span class="req">*</span></label>
                    <input type="email" id="email-field" name="email" value="{{ old('email') }}" placeholder="email@kampus.ac.id" required autocomplete="email" class="reg-input">
                </div>

                {{-- WhatsApp --}}
                <div class="form-item col-span-1">
                    <label for="whatsapp-field">No. WhatsApp / HP</label>
                    <input type="tel" id="whatsapp-field" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="08xxxxxxxxxx" autocomplete="tel" class="reg-input">
                </div>

                {{-- Password --}}
                <div class="form-item col-span-1">
                    <label for="password-field">Kata Sandi <span class="req">*</span></label>
                    <input type="password" id="password-field" name="password" placeholder="Minimal 8 karakter" required minlength="8" autocomplete="new-password" class="reg-input">
                </div>

                {{-- Konfirmasi Password --}}
                <div class="form-item col-span-1">
                    <label for="password-confirm-field">Konfirmasi Sandi <span class="req">*</span></label>
                    <input type="password" id="password-confirm-field" name="password_confirmation" placeholder="Ulangi kata sandi" required minlength="8" autocomplete="new-password" class="reg-input">
                </div>
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="reg-submit-btn">
                <span>Daftar Akun Mahasiswa</span>
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                    <polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>

            <div class="reg-footer">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}">Masuk ke sini</a>
            </div>
        </form>
    </div>
</section>
@endsection
