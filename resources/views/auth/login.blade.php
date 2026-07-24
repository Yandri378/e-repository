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
            --ink:#16233A;
            --ink-soft:#2A3B57;
            --parchment:#F6EFDE;
            --parchment-deep:#EEE3C8;
            --brass:#A9823C;
            --brass-light:#C9A45C;
            --sage:#5E7259;
            --flag:#9B4A3F;
            --line: rgba(22,35,58,0.16);
            --line-light: rgba(246,239,222,0.28);
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
                repeating-linear-gradient(180deg, rgba(255,255,255,0.02) 0px, rgba(255,255,255,0.02) 1px, transparent 1px, transparent 34px),
                linear-gradient(160deg, var(--ink) 0%, #101A2B 100%);
            color:var(--parchment);
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
        }
        .brandmark .mark{
            width:34px; height:34px;
            border:1px solid var(--brass-light);
            border-radius:4px;
            display:flex; align-items:center; justify-content:center;
            font-family:'Instrument Sans', sans-serif;
            font-weight:700; font-size:13px;
            color:var(--brass-light);
        }

        .stacks-heading{
            margin-top:64px;
            max-width:360px;
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
        }
        .stacks-heading h2{
            font-family:'Newsreader', serif;
            font-weight:500;
            font-size:30px;
            line-height:1.32;
            margin:0;
            color:#F3ECDB;
        }
        .stacks-heading p{
            font-size:14.5px;
            line-height:1.7;
            color:rgba(246,239,222,0.62);
            margin:18px 0 0;
        }

        /* ---------- signature element: catalog card ---------- */
        .catalog-card{
            position:relative;
            z-index:1;
            margin-top:44px;
            width:100%;
            max-width:340px;
            background:var(--parchment);
            color:var(--ink);
            border-radius:2px;
            padding:20px 22px 18px;
            transform:rotate(-2.2deg);
            box-shadow: 0 22px 44px rgba(0,0,0,0.35), 0 2px 0 rgba(0,0,0,0.06);
        }
        .catalog-card::before{
            content:"";
            position:absolute;
            top:0; bottom:0; left:38px;
            width:1px;
            background: repeating-linear-gradient(180deg, var(--flag) 0 6px, transparent 6px 12px);
            opacity:0.35;
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
            color:var(--sage);
            font-size:10px;
            font-weight:700;
            letter-spacing:0.14em;
            text-transform:uppercase;
            border-radius:3px;
            transform:rotate(-3deg);
        }

        .stacks-foot{
            position:relative; z-index:1;
            font-size:12px;
            color:rgba(246,239,222,0.42);
            letter-spacing:0.03em;
        }

        /* ---------- RIGHT: request-slip form panel ---------- */
        .slip-panel{
            background:var(--parchment);
            display:flex;
            align-items:center;
            justify-content:center;
            padding:48px 32px;
        }
        .auth-card{
            width:100%;
            max-width:400px;
        }
        .eyebrow{
            margin:0 0 8px;
            font-size:11px;
            letter-spacing:0.2em;
            text-transform:uppercase;
            color:var(--brass);
            font-weight:600;
        }
        .auth-card h1{
            font-family:'Newsreader', serif;
            font-weight:500;
            font-size:32px;
            margin:0 0 30px;
            color:var(--ink);
        }

        .flash-message{
            font-size:13px;
            line-height:1.6;
            background:#EFE6CE;
            border:1px solid var(--line);
            border-left:3px solid var(--brass);
            padding:11px 14px;
            border-radius:3px;
            margin-bottom:20px;
            color:var(--ink-soft);
        }
        .error-box{
            font-size:13px;
            line-height:1.6;
            background:#F6E4E0;
            border:1px solid rgba(155,74,63,0.35);
            border-left:3px solid var(--flag);
            padding:11px 14px;
            border-radius:3px;
            margin-bottom:20px;
            color:#7A342B;
        }

        label{
            display:block;
            font-size:11px;
            font-weight:600;
            letter-spacing:0.1em;
            text-transform:uppercase;
            color:var(--ink-soft);
            margin-bottom:22px;
        }
        input[type="text"], input[type="password"]{
            display:block;
            width:100%;
            margin-top:9px;
            font-family:'Instrument Sans', sans-serif;
            font-size:15px;
            color:var(--ink);
            background:transparent;
            border:none;
            border-bottom:1.5px solid var(--line);
            padding:9px 2px;
            transition: border-color 0.15s ease;
        }
        input[type="text"]::placeholder, input[type="password"]::placeholder{
            color:rgba(22,35,58,0.32);
            font-weight:400;
            text-transform:none;
            letter-spacing:0;
        }
        input[type="text"]:focus, input[type="password"]:focus{
            outline:none;
            border-bottom-color:var(--brass);
        }

        .check-line{
            display:flex;
            align-items:center;
            gap:9px;
            text-transform:none;
            font-size:13px;
            letter-spacing:0;
            font-weight:400;
            color:var(--ink-soft);
            margin-bottom:26px;
        }
        .check-line input{
            width:15px; height:15px;
            accent-color:var(--brass);
        }

        .btn.primary.full{
            width:100%;
            display:block;
            background:var(--ink);
            color:var(--parchment);
            border:none;
            border-radius:3px;
            padding:14px 18px;
            font-family:'Instrument Sans', sans-serif;
            font-size:14px;
            font-weight:600;
            letter-spacing:0.03em;
            cursor:pointer;
            transition: background 0.15s ease, transform 0.1s ease;
        }
        .btn.primary.full:hover{ background:var(--ink-soft); }
        .btn.primary.full:active{ transform:translateY(1px); }
        .btn.primary.full:focus-visible{
            outline:2px solid var(--brass);
            outline-offset:2px;
        }

        .auth-note{
            margin:20px 0 0;
            font-size:12.5px;
            color:var(--ink-soft);
            text-align:center;
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
            <p class="eyebrow">Login Akun Repository</p>
            <h1>Masuk Repository</h1>

            @auth
                <div class="flash-message">
                    Anda sedang login sebagai {{ ucfirst(auth()->user()->role) }}. Isi form ini untuk berpindah akun.
                </div>
            @endauth

            @if ($errors->any())
                <div class="error-box">{{ $errors->first() }}</div>
            @endif

            @if (!empty($role))
                <input type="hidden" name="role" value="{{ $role }}">
            @endif

            <label>Email / NIM / NIDN
                <input type="text" name="identifier" value="{{ old('identifier') }}" placeholder="Masukkan Email, NIM, atau NIDN" required autofocus>
            </label>

            <label>Password
                <input type="password" name="password" required>
            </label>

            <label class="check-line">
                <input type="checkbox" name="remember" value="1"> Ingat saya
            </label>

            <button type="submit" class="btn primary full">Masuk</button>

            <p class="auth-note">Belum punya akun? Minta username dan password awal ke Admin.</p>
        </form>
    </div>

</section>
</body>
</html>