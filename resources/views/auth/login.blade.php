<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Repository</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800|newsreader:400,500,600,i400,i500&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{
            --ink: #0F172A;
            --ink-soft: #475569;
            --parchment: #FFFFFF;
            --parchment-deep: #F8FAFC;
            --brass: #2563EB;
            --brass-light: #38BDF8;
            --sage: #0284C7;
            --flag: #DC2626;
            --line: #E2E8F0;
            --line-light: rgba(56, 189, 248, 0.2);
        }
        *{ box-sizing:border-box; }
        body{
            margin:0;
            font-family:'Instrument Sans', sans-serif;
            color:var(--ink);
            background:var(--parchment-deep);
        }
        .auth-shell{
            min-height:100vh;
            display:grid;
            grid-template-columns: 1fr 1.05fr;
        }
        @media (max-width: 880px){
            .auth-shell{ grid-template-columns: 1fr; }
        }

        /* ---------- LEFT: catalog / stacks panel ---------- */
        .stacks{
            position:relative;
            background:
                radial-gradient(circle at 85% 15%, rgba(56, 189, 248, 0.12), transparent 280px),
                radial-gradient(circle at 15% 85%, rgba(37, 99, 235, 0.16), transparent 320px),
                linear-gradient(160deg, #0B132B 0%, #111C3A 50%, #0F172A 100%);
            color:#FFFFFF;
            padding:56px 52px;
            display:flex;
            flex-direction:column;
            justify-content:space-between;
            overflow:hidden;
        }
        @media (max-width: 880px){
            .stacks{ padding:36px 28px 64px; }
        }
        .stacks::after{
            content:"";
            position:absolute;
            right:-120px; bottom:-140px;
            width:360px; height:360px;
            border:1px solid var(--line-light);
            border-radius:50%;
        }
        .brandmark{
            display:flex;
            align-items:center;
            gap:12px;
            font-family:'Newsreader', serif;
            font-size:20px;
            letter-spacing:0.02em;
            color:#F8FAFC;
        }
        .brandmark .mark{
            width:34px; height:34px;
            border:1.5px solid var(--brass-light);
            background:rgba(56, 189, 248, 0.08);
            border-radius:6px;
            display:flex; align-items:center; justify-content:center;
            font-family:'Instrument Sans', sans-serif;
            font-weight:700; font-size:13px;
            color:var(--brass-light);
        }

        .stacks-heading{
            margin-top:64px;
            max-width:380px;
            position:relative;
            z-index:1;
        }
        .stacks-heading .kicker{
            font-family:'Instrument Sans', sans-serif;
            font-size:11px;
            letter-spacing:0.22em;
            text-transform:uppercase;
            color:var(--brass-light);
            margin:0 0 14px;
            font-weight:600;
        }
        .stacks-heading h2{
            font-family:'Newsreader', serif;
            font-weight:500;
            font-size:30px;
            line-height:1.35;
            margin:0;
            color:#F8FAFC;
        }
        .stacks-heading p{
            font-size:14.5px;
            line-height:1.7;
            color:#94A3B8;
            margin:18px 0 0;
        }

        /* ---------- signature element: catalog card ---------- */
        .catalog-card{
            position:relative;
            z-index:1;
            margin-top:44px;
            width:100%;
            max-width:340px;
            background:#FFFFFF;
            color:var(--ink);
            border-radius:8px;
            padding:20px 22px 18px;
            transform:rotate(-1.8deg);
            box-shadow: 0 20px 40px rgba(0,0,0,0.35), 0 1px 3px rgba(0,0,0,0.1);
        }
        .catalog-card::before{
            content:"";
            position:absolute;
            top:0; bottom:0; left:38px;
            width:1px;
            background: repeating-linear-gradient(180deg, var(--brass) 0 6px, transparent 6px 12px);
            opacity:0.3;
        }
        .catalog-row{
            display:flex;
            justify-content:space-between;
            align-items:baseline;
            font-family:'Instrument Sans', monospace;
            font-size:11px;
            letter-spacing:0.06em;
            text-transform:uppercase;
            color:var(--ink-soft);
            border-bottom:1px dashed var(--line);
            padding-bottom:8px;
            margin-bottom:8px;
        }
        .catalog-title{
            font-family:'Newsreader', serif;
            font-style:italic;
            font-size:17px;
            margin:2px 0 10px;
            color:var(--ink);
        }
        .catalog-meta{
            display:flex;
            justify-content:space-between;
            font-size:11px;
            color:var(--ink-soft);
        }
        .catalog-stamp{
            display:inline-block;
            margin-top:12px;
            padding:4px 10px;
            border:1.5px solid var(--sage);
            background:#F0F9FF;
            color:var(--sage);
            font-size:10px;
            font-weight:700;
            letter-spacing:0.14em;
            text-transform:uppercase;
            border-radius:4px;
            transform:rotate(-3deg);
        }

        .stacks-foot{
            position:relative; z-index:1;
            font-size:12px;
            color:#94A3B8;
            letter-spacing:0.03em;
        }

        /* ---------- RIGHT: request-slip form panel ---------- */
        .slip-panel{
            background: linear-gradient(160deg, #091328 0%, #0d1b38 50%, #070e1e 100%);
            display:flex;
            align-items:center;
            justify-content:center;
            padding:48px 32px;
            position:relative;
        }
        .slip-panel::before{
            content:"";
            position:absolute;
            inset:0;
            background: radial-gradient(circle at 80% 20%, rgba(56, 189, 248, 0.08), transparent 300px);
            pointer-events:none;
        }
        .auth-card{
            width:100%;
            max-width:420px;
            position:relative;
            z-index:1;
            background: rgba(15, 23, 42, 0.75);
            border: 1px solid rgba(56, 189, 248, 0.25);
            border-radius: 20px;
            padding: 36px 32px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(16px);
        }
        .eyebrow{
            margin:0 0 6px;
            font-size:11.5px;
            letter-spacing:0.2em;
            text-transform:uppercase;
            color:#38BDF8;
            font-weight:700;
        }
        .auth-card h1{
            font-family:'Newsreader', serif;
            font-weight:600;
            font-size:30px;
            margin:0 0 24px;
            color:#FFFFFF;
            letter-spacing: -0.01em;
        }

        .flash-message{
            font-size:13px;
            line-height:1.6;
            background:rgba(30, 58, 138, 0.35);
            border:1px solid rgba(59, 130, 246, 0.4);
            border-left:4px solid #38BDF8;
            padding:12px 16px;
            border-radius:8px;
            margin-bottom:22px;
            color:#BFDBFE;
        }
        .error-box{
            font-size:13px;
            line-height:1.6;
            background:rgba(239, 68, 68, 0.15);
            border:1px solid rgba(239, 68, 68, 0.35);
            border-left:4px solid #EF4444;
            padding:12px 16px;
            border-radius:8px;
            margin-bottom:22px;
            color:#FCA5A5;
        }

        label{
            display:block;
            font-size:12.5px;
            font-weight:600;
            letter-spacing:0.03em;
            color:#E2E8F0;
            margin-bottom:18px;
        }
        input[type="text"], input[type="password"]{
            display:block;
            width:100%;
            margin-top:7px;
            font-family:'Instrument Sans', sans-serif;
            font-size:14.5px;
            color:#FFFFFF;
            background: rgba(30, 41, 59, 0.7);
            border:1.5px solid rgba(255, 255, 255, 0.16);
            border-radius:8px;
            padding:12px 15px;
            box-shadow:inset 0 1px 2px rgba(0,0,0,0.2);
            transition: border-color 0.2s ease, background-color 0.2s ease, box-shadow 0.2s ease;
            outline:none;
        }
        input[type="text"]::placeholder, input[type="password"]::placeholder{
            color:#64748B;
            font-weight:400;
            text-transform:none;
            letter-spacing:0;
        }
        input[type="text"]:focus, input[type="password"]:focus{
            background: rgba(30, 41, 59, 0.95);
            border-color:#38BDF8;
            box-shadow:0 0 0 3px rgba(56, 189, 248, 0.2);
        }

        .check-line{
            display:flex;
            align-items:center;
            gap:9px;
            text-transform:none;
            font-size:13.5px;
            letter-spacing:0;
            font-weight:500;
            color:#CBD5E1;
            margin-bottom:24px;
            cursor:pointer;
        }
        .check-line input{
            width:16px; height:16px;
            accent-color:#2563EB;
            cursor:pointer;
        }

        .btn.primary.full{
            width:100%;
            display:block;
            background: linear-gradient(135deg, #0284c7, #2563eb);
            color:#FFFFFF;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius:8px;
            padding:13px 18px;
            font-family:'Instrument Sans', sans-serif;
            font-size:15px;
            font-weight:700;
            letter-spacing:0.02em;
            cursor:pointer;
            box-shadow:0 4px 14px rgba(2, 132, 199, 0.35);
            transition: all 0.2s ease;
        }
        .btn.primary.full:hover{
            background: linear-gradient(135deg, #0369a1, #1d4ed8);
            box-shadow:0 6px 20px rgba(37, 99, 235, 0.5);
            transform:translateY(-1px);
        }
        .btn.primary.full:active{ transform:translateY(1px); }
        .btn.primary.full:focus-visible{
            outline:2px solid #38BDF8;
            outline-offset:2px;
        }

        .auth-note{
            margin:22px 0 0;
            font-size:13.5px;
            color:#FFFFFF;
            text-align:center;
            line-height:1.5;
        }
        .auth-note a{
            color:#38BDF8;
            text-decoration:none;
            font-weight:600;
            transition: color 0.2s ease;
        }
        .auth-note a:hover{
            color:#7DD3FC;
            text-decoration:underline;
        }

        @media (prefers-reduced-motion: reduce){
            .btn.primary.full{ transition:none; }
        }
    </style>
</head>
<body>

<section class="auth-shell">

    <div class="stacks">
        <div class="brandmark">
            <span class="mark">EJ</span>
            E-Jurnal
        </div>

        <div class="stacks-heading">
            <p class="kicker">Repositori Kampus</p>
            <h2>Setiap skripsi, laporan, dan penelitian tersimpan rapi di satu rak.</h2>
            <p>Masuk untuk mengelola arsip Tugas Akhir, Laporan Magang, PKM, dan Penelitian Dosen.</p>
        </div>

        <div class="catalog-card">
            <div class="catalog-row">
                <span>Kartu Akses</span>
                <span>No. 2026</span>
            </div>
            <p class="catalog-title">Repositori E-Jurnal</p>
            <div class="catalog-meta">
                <span>Admin &middot; Dosen &middot; Mahasiswa</span>
            </div>
            <span class="catalog-stamp">Tersedia</span>
        </div>

        <p class="stacks-foot">&copy; {{ date('Y') }} E-Jurnal &mdash; Sistem Repositori Kampus</p>
    </div>

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

            <label>Email / NIM / NIDN
                <input type="text" name="identifier" value="{{ old('identifier') }}" placeholder="{{ $targetRole === 'mahasiswa' ? 'Masukkan Email atau NIM' : ($targetRole === 'dosen' ? 'Masukkan Email atau NIDN' : 'Masukkan Email, NIM, atau NIDN') }}" required autofocus>
            </label>

            <label>Password
                <input type="password" name="password" required>
            </label>

            <label class="check-line">
                <input type="checkbox" name="remember" value="1"> Ingat saya
            </label>

            <button type="submit" class="btn primary full">Masuk ke Sistem</button>

            <p class="auth-note">
                Belum punya akun? <a href="{{ route('register') }}">Daftar Akun Sekarang</a>
            </p>
        </form>
    </div>


</section>
</body>
</html>