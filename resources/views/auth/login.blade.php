<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Masuk Repository - Universitas Metamedia</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/metamedia.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/metamedia.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800|newsreader:400,500,600,i400,i500&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --ink: #0F172A;
            --ink-soft: #475569;
            --parchment: #FFFFFF;
            --parchment-deep: #0B132B;
            --brass: #2563EB;
            --brass-light: #38BDF8;
            --sage: #0284C7;
            --flag: #DC2626;
            --line: #E2E8F0;
            --line-light: rgba(56, 189, 248, 0.2);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            min-height: 100dvh;
            font-family: 'Instrument Sans', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--ink);
            background: #091328;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* Base Shell: 2-column layout on Desktop, 1-column on Mobile/Tablet */
        .auth-shell {
            min-height: 100vh;
            min-height: 100dvh;
            display: grid;
            grid-template-columns: 1fr 1.08fr;
            position: relative;
        }

        /* ========================================================
           LEFT: Brand / Stacks Panel
           ======================================================== */
        .stacks {
            position: relative;
            background:
                radial-gradient(circle at 85% 15%, rgba(56, 189, 248, 0.12), transparent 280px),
                radial-gradient(circle at 15% 85%, rgba(37, 99, 235, 0.18), transparent 320px),
                linear-gradient(160deg, #0B132B 0%, #111C3A 50%, #0F172A 100%);
            color: #FFFFFF;
            padding: clamp(32px, 5vh, 56px) clamp(28px, 4vw, 52px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            border-right: 1px solid rgba(56, 189, 248, 0.12);
        }

        .stacks::after {
            content: "";
            position: absolute;
            right: -120px;
            bottom: -140px;
            width: 360px;
            height: 360px;
            border: 1px solid var(--line-light);
            border-radius: 50%;
            pointer-events: none;
        }

        .brand-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            position: relative;
            z-index: 2;
        }

        .brandmark {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #F8FAFC;
            transition: opacity 0.2s ease;
        }
        .brandmark:hover {
            opacity: 0.9;
        }

        .brand-logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.35));
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 0.04em;
            color: #FFFFFF;
            line-height: 1.2;
        }

        .brand-sub {
            font-size: 11px;
            color: var(--brass-light);
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 600;
            color: #94A3B8;
            text-decoration: none;
            padding: 7px 14px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.04);
            transition: all 0.2s ease;
            backdrop-filter: blur(8px);
        }
        .back-link:hover {
            color: #FFFFFF;
            background: rgba(56, 189, 248, 0.14);
            border-color: rgba(56, 189, 248, 0.35);
        }

        .stacks-heading {
            margin: clamp(20px, 3.5vh, 48px) 0;
            max-width: 440px;
            position: relative;
            z-index: 1;
        }

        .stacks-heading .kicker {
            font-size: 11px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--brass-light);
            margin-bottom: 12px;
            font-weight: 700;
        }

        .stacks-heading h2 {
            font-family: 'Newsreader', serif;
            font-weight: 500;
            font-size: clamp(24px, 2.4vw, 32px);
            line-height: 1.35;
            color: #F8FAFC;
            margin-bottom: 14px;
        }

        .stacks-heading p {
            font-size: 14px;
            line-height: 1.7;
            color: #94A3B8;
        }

        /* Signature element: catalog card (visible on Desktop / Large screens) */
        .catalog-card {
            position: relative;
            z-index: 1;
            margin: 20px 0;
            width: 100%;
            max-width: 340px;
            background: #FFFFFF;
            color: var(--ink);
            border-radius: 12px;
            padding: 18px 20px 16px;
            transform: rotate(-1.5deg);
            box-shadow: 0 20px 40px rgba(0,0,0,0.35), 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .catalog-card:hover {
            transform: rotate(0deg) scale(1.02);
        }
        .catalog-card::before {
            content: "";
            position: absolute;
            top: 0; bottom: 0; left: 34px;
            width: 1px;
            background: repeating-linear-gradient(180deg, var(--brass) 0 6px, transparent 6px 12px);
            opacity: 0.3;
        }
        .catalog-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            font-size: 11px;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--ink-soft);
            border-bottom: 1px dashed var(--line);
            padding-bottom: 7px;
            margin-bottom: 7px;
            font-weight: 600;
        }
        .catalog-title {
            font-family: 'Newsreader', serif;
            font-style: italic;
            font-size: 17px;
            margin: 4px 0 8px;
            color: var(--ink);
            font-weight: 600;
        }
        .catalog-meta {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: var(--ink-soft);
        }
        .catalog-stamp {
            display: inline-block;
            margin-top: 10px;
            padding: 3px 10px;
            border: 1.5px solid var(--sage);
            background: #F0F9FF;
            color: var(--sage);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            border-radius: 4px;
            transform: rotate(-2deg);
        }

        .stacks-foot {
            position: relative;
            z-index: 1;
            font-size: 12px;
            color: #64748B;
            letter-spacing: 0.02em;
        }

        /* ========================================================
           RIGHT: Slip / Form Panel
           ======================================================== */
        .slip-panel {
            background: linear-gradient(160deg, #091328 0%, #0d1b38 50%, #070e1e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(24px, 4vh, 48px) clamp(20px, 3.5vw, 44px);
            position: relative;
        }
        .slip-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 80% 20%, rgba(56, 189, 248, 0.08), transparent 300px);
            pointer-events: none;
        }

        /* Form Card */
        .auth-card {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
            background: rgba(15, 23, 42, 0.82);
            border: 1px solid rgba(56, 189, 248, 0.22);
            border-radius: 20px;
            padding: clamp(24px, 4vw, 36px);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.55), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
        }

        .eyebrow {
            margin: 0 0 6px;
            font-size: 11.5px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--brass-light);
            font-weight: 700;
        }

        .auth-card h1 {
            font-family: 'Newsreader', serif;
            font-weight: 600;
            font-size: clamp(24px, 3vw, 30px);
            margin: 0 0 22px;
            color: #FFFFFF;
            letter-spacing: -0.01em;
            line-height: 1.25;
        }

        .flash-message {
            font-size: 13px;
            line-height: 1.6;
            background: rgba(30, 58, 138, 0.35);
            border: 1px solid rgba(59, 130, 246, 0.4);
            border-left: 4px solid #38BDF8;
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: #BFDBFE;
        }

        .error-box {
            font-size: 13px;
            line-height: 1.6;
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.35);
            border-left: 4px solid #EF4444;
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: #FCA5A5;
        }

        /* Form Fields */
        .form-group {
            margin-bottom: 18px;
        }

        label.field-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            letter-spacing: 0.02em;
            color: #E2E8F0;
            margin-bottom: 6px;
        }

        input[type="text"],
        input[type="password"] {
            display: block;
            width: 100%;
            font-family: 'Instrument Sans', system-ui, sans-serif;
            font-size: 16px; /* 16px prevents iOS Safari auto-zoom on mobile devices */
            color: #FFFFFF;
            background: rgba(30, 41, 59, 0.75);
            border: 1.5px solid rgba(255, 255, 255, 0.16);
            border-radius: 10px;
            padding: 12px 15px;
            min-height: 46px; /* Touch friendly minimum target */
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.25);
            transition: border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
            outline: none;
        }

        input[type="text"]::placeholder,
        input[type="password"]::placeholder {
            color: #64748B;
            font-weight: 400;
            font-size: 14px;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            background: rgba(30, 41, 59, 0.95);
            border-color: #38BDF8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.22);
        }

        .check-line {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 13.5px;
            font-weight: 500;
            color: #CBD5E1;
            margin-bottom: 22px;
            cursor: pointer;
            user-select: none;
        }
        .check-line input {
            width: 17px;
            height: 17px;
            accent-color: #2563EB;
            cursor: pointer;
        }

        .btn.primary.full {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: linear-gradient(135deg, #0284c7, #2563eb);
            color: #FFFFFF;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 13px 18px;
            min-height: 48px;
            font-family: 'Instrument Sans', system-ui, sans-serif;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.02em;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
            transition: all 0.2s ease;
        }
        .btn.primary.full:hover {
            background: linear-gradient(135deg, #0369a1, #1d4ed8);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.5);
            transform: translateY(-1px);
        }
        .btn.primary.full:active {
            transform: translateY(1px);
        }
        .btn.primary.full:focus-visible {
            outline: 2px solid #38BDF8;
            outline-offset: 2px;
        }

        .auth-note {
            margin: 20px 0 0;
            font-size: 13.5px;
            color: #94A3B8;
            text-align: center;
            line-height: 1.5;
        }
        .auth-note a {
            color: #38BDF8;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }
        .auth-note a:hover {
            color: #7DD3FC;
            text-decoration: underline;
        }

        /* ========================================================
           RESPONSIVE BREAKPOINTS (Mobile HP, Tablet, Laptop)
           ======================================================== */

        /* Tablet & Smaller Laptops (<= 960px) */
        @media (max-width: 960px) {
            .auth-shell {
                grid-template-columns: 1fr;
                min-height: 100vh;
                min-height: 100dvh;
            }

            .stacks {
                padding: 24px 24px 28px;
                border-right: none;
                border-bottom: 1px solid rgba(56, 189, 248, 0.15);
            }

            .stacks-heading {
                margin: 18px 0;
            }
            .stacks-heading h2 {
                font-size: 24px;
            }
            .stacks-heading p {
                font-size: 13.5px;
            }

            .catalog-card {
                display: none; /* Hide large card on mobile & tablet so user can log in immediately */
            }

            .stacks-foot {
                display: none;
            }

            .slip-panel {
                padding: 32px 20px 48px;
            }
        }

        /* Mobile Phone / HP (<= 640px) */
        @media (max-width: 640px) {
            .stacks {
                padding: 18px 16px 18px;
            }

            .brand-top {
                flex-wrap: wrap;
                gap: 10px;
            }

            .brand-logo {
                width: 32px;
                height: 32px;
            }

            .brand-name {
                font-size: 13px;
            }
            .brand-sub {
                font-size: 10px;
            }

            .back-link {
                font-size: 11px;
                padding: 5px 10px;
            }

            .stacks-heading {
                margin: 10px 0 2px;
            }
            .stacks-heading .kicker {
                font-size: 10px;
                margin-bottom: 4px;
            }
            .stacks-heading h2 {
                font-size: 18px;
                margin-bottom: 4px;
                line-height: 1.3;
            }
            .stacks-heading p {
                display: none; /* Keep top banner super crisp on mobile so form sits right on top */
            }

            .slip-panel {
                padding: 16px 14px 32px;
                align-items: flex-start;
            }

            .auth-card {
                padding: 22px 18px;
                border-radius: 16px;
                box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
            }

            .auth-card h1 {
                font-size: 22px;
                margin-bottom: 18px;
            }

            .form-group {
                margin-bottom: 15px;
            }

            .check-line {
                font-size: 13px;
                margin-bottom: 18px;
            }

            .btn.primary.full {
                padding: 12px 16px;
                font-size: 14.5px;
                min-height: 46px;
            }

            .auth-note {
                font-size: 12.5px;
                margin-top: 16px;
            }
        }

        /* Very Small Mobile Phone (<= 380px) */
        @media (max-width: 380px) {
            .stacks {
                padding: 14px 12px;
            }
            .slip-panel {
                padding: 12px 8px 24px;
            }
            .auth-card {
                padding: 18px 14px;
                border-radius: 14px;
            }
            .auth-card h1 {
                font-size: 20px;
            }
        }

        /* Laptop with short height (<= 800px on Desktop) */
        @media (min-width: 961px) and (max-height: 800px) {
            .stacks {
                padding: 24px 36px;
            }
            .stacks-heading {
                margin: 16px 0;
            }
            .stacks-heading h2 {
                font-size: 25px;
            }
            .catalog-card {
                margin: 10px 0;
                padding: 14px 18px;
            }
            .slip-panel {
                padding: 20px 28px;
            }
            .auth-card {
                padding: 26px 26px;
            }
            .auth-card h1 {
                font-size: 26px;
                margin-bottom: 16px;
            }
            .form-group {
                margin-bottom: 14px;
            }
            .check-line {
                margin-bottom: 16px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .btn.primary.full { transition: none; }
        }
    </style>
</head>
<body>

<section class="auth-shell">

    {{-- LEFT: Brand / Stacks Panel --}}
    <div class="stacks">
        <div class="brand-top">
            <a href="{{ url('/') }}" class="brandmark" title="Kembali ke Beranda">
                <img src="{{ asset('assets/metamedia.png') }}" alt="Logo Universitas Metamedia" class="brand-logo">
                <div class="brand-text">
                    <span class="brand-name">UNIVERSITAS METAMEDIA</span>
                    <span class="brand-sub">E-Repository & Arsip Ilmiah</span>
                </div>
            </a>
            <a href="{{ url('/') }}" class="back-link" title="Menuju Halaman Utama">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                <span>Beranda</span>
            </a>
        </div>

        <div class="stacks-heading">
            <p class="kicker">Sistem Repositori Kampus</p>
            <h2>Setiap skripsi, magang, PKM, dan penelitian tersimpan rapi.</h2>
            <p>Pusat akses dan publikasi karya ilmiah mahasiswa serta dosen Universitas Metamedia.</p>
        </div>

        <div class="catalog-card">
            <div class="catalog-row">
                <span>Kartu Akses</span>
                <span>Tahun {{ date('Y') }}</span>
            </div>
            <p class="catalog-title">E-Repository Metamedia</p>
            <div class="catalog-meta">
                <span>Admin &middot; Dosen &middot; Mahasiswa</span>
            </div>
            <span class="catalog-stamp">Terverifikasi</span>
        </div>

        <p class="stacks-foot">&copy; {{ date('Y') }} Universitas Metamedia &mdash; All Rights Reserved.</p>
    </div>

    {{-- RIGHT: Form Panel --}}
    <div class="slip-panel">
        <form method="POST" action="{{ route('login.store') }}" class="auth-card">
            @csrf
            @php
                $targetRole = $role ?? null;
            @endphp

            @if (!empty($targetRole))
                <p class="eyebrow">Portal {{ ucfirst($targetRole) }}</p>
                <h1>Masuk sebagai {{ ucfirst($targetRole) }}</h1>
            @else
                <p class="eyebrow">Login Akun Repository</p>
                <h1>Masuk Repository</h1>
            @endif

            @auth
                @if(auth()->user()->role !== ($targetRole ?? auth()->user()->role))
                    <div class="flash-message">
                        Anda sedang login sebagai <strong>{{ ucfirst(auth()->user()->role) }}</strong>.
                        Isi form di bawah dengan akun {{ ucfirst($targetRole ?? '') }} untuk berpindah akun.
                    </div>
                @else
                    <div class="flash-message">
                        Anda sedang login sebagai {{ ucfirst(auth()->user()->role) }}. Isi form ini untuk berpindah akun.
                    </div>
                @endif
            @endauth

            @if (session('status'))
                <div class="flash-message" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.35); border-left: 4px solid #10B981; color: #6EE7B7; display: flex; align-items: flex-start; gap: 10px;">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" style="flex-shrink:0; margin-top:2px;">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" stroke-linecap="round" stroke-linejoin="round"/>
                        <polyline points="22 4 12 14.01 9 11.01" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="error-box">{{ $errors->first() }}</div>
            @endif

            @if (!empty($targetRole))
                <input type="hidden" name="role" value="{{ $targetRole }}">
            @endif

            <div class="form-group">
                <label class="field-label" for="identifier-input">Email / NIM / NIDN</label>
                <input type="text" id="identifier-input" name="identifier" value="{{ old('identifier') }}" placeholder="{{ $targetRole === 'mahasiswa' ? 'Masukkan Email atau NIM' : ($targetRole === 'dosen' ? 'Masukkan Email atau NIDN' : 'Masukkan Email, NIM, atau NIDN') }}" required autofocus autocomplete="username">
            </div>

            <div class="form-group">
                <label class="field-label" for="password-input">Password</label>
                <input type="password" id="password-input" name="password" placeholder="Masukkan kata sandi akun" required autocomplete="current-password">
            </div>

            <label class="check-line">
                <input type="checkbox" name="remember" value="1">
                <span>Ingat saya di perangkat ini</span>
            </label>

            <button type="submit" class="btn primary full">
                <span>Masuk ke Sistem</span>
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 18 15 12 9 6"/></svg>
            </button>

            <p class="auth-note">
                Belum punya akun? <a href="{{ route('register') }}">Daftar Akun Sekarang</a>
            </p>
        </form>
    </div>

</section>
</body>
</html>