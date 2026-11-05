@extends('layouts.app')

@section('title', 'Daftar Akun Mahasiswa & Dosen')

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
        margin-bottom: 26px;
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

    /* Role Tabs */
    .role-tabs-wrap {
        margin-bottom: 22px;
    }

    .role-tabs-label {
        display: block;
        font-size: 12px;
        font-weight: 700;
        color: #E2E8F0;
        margin-bottom: 8px;
        letter-spacing: 0.04em;
    }

    .role-tabs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        background: rgba(30, 41, 59, 0.7);
        padding: 5px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .role-tab {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 16px;
        min-height: 46px;
        border-radius: 9px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 700;
        transition: all 0.2s ease;
        margin: 0;
        user-select: none;
        color: #94A3B8;
    }

    .role-tab.active {
        background: linear-gradient(135deg, #0284c7, #2563eb);
        color: #FFFFFF;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.45);
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

        .role-tab {
            padding: 10px 12px;
            font-size: 13.5px;
            min-height: 44px;
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

        .role-tab {
            font-size: 12.5px;
            gap: 5px;
            padding: 8px 10px;
        }

        .role-tab svg {
            width: 16px;
            height: 16px;
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
            <span class="register-eyebrow">Pendaftaran Mandiri</span>
            <h1 class="register-title">Buat Akun Repositori</h1>
            <p class="register-subtitle">Lengkapi formulir di bawah untuk mendaftar sebagai Mahasiswa atau Dosen Universitas Metamedia.</p>
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
            @php
                $selectedRole = old('role', $role ?? 'mahasiswa');
            @endphp

            {{-- Role Selector Tabs --}}
            <div class="role-tabs-wrap">
                <label class="role-tabs-label">Daftar Sebagai:</label>
                <div class="role-tabs">
                    <label id="tab-mahasiswa" class="role-tab {{ $selectedRole === 'mahasiswa' ? 'active' : '' }}">
                        <input type="radio" name="role" value="mahasiswa" id="role-mahasiswa" style="display: none;" {{ $selectedRole === 'mahasiswa' ? 'checked' : '' }}>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                        </svg>
                        <span>Mahasiswa</span>
                    </label>
                    <label id="tab-dosen" class="role-tab {{ $selectedRole === 'dosen' ? 'active' : '' }}">
                        <input type="radio" name="role" value="dosen" id="role-dosen" style="display: none;" {{ $selectedRole === 'dosen' ? 'checked' : '' }}>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/>
                            <path d="M6 6h10M6 10h10"/>
                        </svg>
                        <span>Dosen</span>
                    </label>
                </div>
            </div>

            {{-- Form Grid --}}
            <div class="register-grid">
                {{-- Nama Lengkap --}}
                <div class="form-item col-span-2">
                    <label for="name-field">Nama Lengkap (dengan Gelar jika Dosen) <span class="req">*</span></label>
                    <input type="text" id="name-field" name="name" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso, S.Kom." required autocomplete="name" class="reg-input">
                </div>

                {{-- NIM Container (Mahasiswa) --}}
                <div id="nim-box" class="form-item col-span-1" style="display: {{ $selectedRole === 'mahasiswa' ? 'flex' : 'none' }};">
                    <label for="nim-field">NIM (Nomor Induk Mahasiswa) <span class="req">*</span></label>
                    <input type="text" id="nim-field" name="nim" value="{{ old('nim') }}" placeholder="Contoh: 202201001" {{ $selectedRole === 'mahasiswa' ? 'required' : '' }} class="reg-input">
                </div>

                {{-- NIDN Container (Dosen) --}}
                <div id="nidn-box" class="form-item col-span-1" style="display: {{ $selectedRole === 'dosen' ? 'flex' : 'none' }};">
                    <label for="nidn-field">NIDN (Nomor Induk Dosen Nasional) <span class="req">*</span></label>
                    <input type="text" id="nidn-field" name="nidn" value="{{ old('nidn') }}" placeholder="Contoh: 0012345678" {{ $selectedRole === 'dosen' ? 'required' : '' }} class="reg-input">
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
                <span>Daftar Akun Sekarang</span>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const radioMahasiswa = document.getElementById('role-mahasiswa');
        const radioDosen = document.getElementById('role-dosen');
        const tabMahasiswa = document.getElementById('tab-mahasiswa');
        const tabDosen = document.getElementById('tab-dosen');
        const nimBox = document.getElementById('nim-box');
        const nidnBox = document.getElementById('nidn-box');
        const nimField = document.getElementById('nim-field');
        const nidnField = document.getElementById('nidn-field');

        function updateRoleState(isMahasiswa) {
            if (isMahasiswa) {
                tabMahasiswa.classList.add('active');
                tabDosen.classList.remove('active');

                nimBox.style.display = 'flex';
                nidnBox.style.display = 'none';
                nimField.required = true;
                nidnField.required = false;
                nidnField.value = '';
            } else {
                tabDosen.classList.add('active');
                tabMahasiswa.classList.remove('active');

                nimBox.style.display = 'none';
                nidnBox.style.display = 'flex';
                nimField.required = false;
                nidnField.required = true;
                nimField.value = '';
            }
        }

        if (radioMahasiswa && radioDosen) {
            radioMahasiswa.addEventListener('change', () => updateRoleState(true));
            tabMahasiswa.addEventListener('click', () => {
                radioMahasiswa.checked = true;
                updateRoleState(true);
            });

            radioDosen.addEventListener('change', () => updateRoleState(false));
            tabDosen.addEventListener('click', () => {
                radioDosen.checked = true;
                updateRoleState(false);
            });

            // Inisialisasi sesuai kondisi awal
            updateRoleState(radioMahasiswa.checked);
        }
    });
</script>
@endsection