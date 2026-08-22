@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     HOME-SPECIFIC STYLES
══════════════════════════════════════════════════════════════ --}}
<style>
    /* ── Keyframes ─────────────────────────────────────────────── */
    @keyframes heroSlide {
        0%   { opacity: 0; transform: scale(1.06); }
        8%   { opacity: 1; transform: scale(1);    }
        33%  { opacity: 1; transform: scale(1);    }
        41%  { opacity: 0; transform: scale(0.97); }
        100% { opacity: 0; transform: scale(1.06); }
    }
    @keyframes badgeFloat {
        0%,100% { transform: translateY(0);    }
        50%      { transform: translateY(-5px); }
    }
    @keyframes titleEnter {
        from { opacity: 0; transform: translateY(28px); }
        to   { opacity: 1; transform: translateY(0);    }
    }
    @keyframes titleGlow {
        0%,100% { background-position: 0%   50%; }
        50%      { background-position: 100% 50%; }
    }
    @keyframes lineReveal {
        from { transform: scaleX(0); opacity: 0; }
        to   { transform: scaleX(1); opacity: 1; }
    }
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0);    }
    }
    @keyframes pulseRing {
        0%  { transform: scale(1);   opacity: .6; }
        70% { transform: scale(1.5); opacity: 0;  }
        100%{ transform: scale(1);   opacity: 0;  }
    }
    @keyframes countUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0);    }
    }

    /* ── Hero overrides ────────────────────────────────────────── */
    .home-hero {
        min-height: 88vh;
        display: grid;
        align-items: center;
        position: relative;
        overflow: hidden;
        padding: clamp(5rem,12vw,8rem) clamp(1rem,5vw,5rem);
    }
    .hero-slideshow,
    .hero-slideshow span,
    .hero-overlay { position: absolute; inset: 0; }

    .hero-slideshow span {
        background-image: var(--bg);
        background-size: cover;
        background-position: center;
        opacity: 0;
        transform: scale(1.06);
        animation: heroSlide 18s infinite;
    }
    .hero-slideshow span:nth-child(2) { animation-delay: 6s; }
    .hero-slideshow span:nth-child(3) { animation-delay: 12s; }

    .hero-overlay {
        background:
            linear-gradient(90deg, rgba(5,37,62,.88), rgba(14,114,164,.34), rgba(7,43,68,.6)),
            linear-gradient(180deg, rgba(9,92,137,.04), rgba(12,86,135,.78));
    }

    .home-hero-content {
        position: relative;
        max-width: 880px;
    }

    /* Badge */
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: .55rem;
        width: fit-content;
        min-height: 38px;
        margin-bottom: 1.4rem;
        padding: .42rem 1rem .42rem .7rem;
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 999px;
        color: #f0faff;
        font-size: .8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        background: rgba(255,255,255,.13);
        box-shadow: 0 16px 46px rgba(8,68,104,.22), inset 0 1px 0 rgba(255,255,255,.2);
        backdrop-filter: blur(16px);
        animation: badgeFloat 4.5s ease-in-out infinite;
    }
    .hero-badge-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #72d9ff;
        position: relative;
        flex-shrink: 0;
    }
    .hero-badge-dot::after {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        border: 1.5px solid #72d9ff;
        animation: pulseRing 2s ease-out infinite;
    }

    /* Title */
    .hero-title {
        position: relative;
        max-width: 14ch;
        margin: 0 0 1.1rem;
        font-size: clamp(3rem,8vw,6.5rem);
        line-height: .93;
        letter-spacing: -.02em;
        color: transparent;
        background: linear-gradient(110deg,#ffffff 0%,#e0f2fe 30%,#38bdf8 60%,#ffffff 100%);
        background-size: 220% 100%;
        -webkit-background-clip: text;
        background-clip: text;
        animation: titleGlow 7s ease-in-out infinite, titleEnter 720ms cubic-bezier(.2,.9,.2,1) both;
    }
    .hero-title span { display: block; }
    .hero-title::after {
        display: block;
        width: min(260px,58vw);
        height: 4px;
        margin-top: 1rem;
        border-radius: 999px;
        background: linear-gradient(90deg,#38bdf8,#2563eb,#ffffff);
        box-shadow: 0 0 32px rgba(114,217,255,.42);
        content: "";
        transform-origin: left;
        animation: lineReveal 900ms 220ms cubic-bezier(.2,.9,.2,1) both;
    }

    /* Lead */
    .hero-lead {
        max-width: 640px;
        color: #cee8f6;
        font-size: clamp(1rem,2vw,1.2rem);
        line-height: 1.65;
        margin-bottom: 2rem;
        animation: fadeUp 720ms 160ms cubic-bezier(.2,.9,.2,1) both;
    }

    /* Hero actions */
    .hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: .85rem;
        animation: fadeUp 720ms 300ms cubic-bezier(.2,.9,.2,1) both;
    }

    /* Hero scroll indicator */
    .hero-scroll {
        position: absolute;
        bottom: 2.5rem;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .5rem;
        color: rgba(255,255,255,.5);
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .1em;
        text-transform: uppercase;
        animation: fadeUp 1s 800ms both;
    }
    .hero-scroll-line {
        width: 1px;
        height: 36px;
        background: linear-gradient(to bottom,rgba(114,217,255,.6),transparent);
        animation: badgeFloat 2s ease-in-out infinite;
    }

    /* ── Stat cards with icons ────────────────────────────────── */
    .stats-section {
        width: min(1200px, calc(100% - 2rem));
        margin: 0 auto;
        padding: clamp(2.5rem,6vw,5rem) 0;
    }
    .stats-section-inner {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px,1fr));
        gap: 1.2rem;
    }
    .stat-card-home {
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(114,217,255,.22);
        border-radius: 16px;
        background: linear-gradient(145deg, rgba(22,111,153,.75), rgba(12,58,90,.8));
        box-shadow: 0 25px 70px rgba(5,73,112,.22), inset 0 1px 0 rgba(255,255,255,.15);
        padding: 1.6rem 1.4rem;
        text-decoration: none;
        color: inherit;
        transition: transform .25s cubic-bezier(.2,.9,.2,1), box-shadow .25s ease, border-color .25s ease;
        display: flex;
        flex-direction: column;
        gap: .5rem;
    }
    .stat-card-home::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        background: radial-gradient(circle at 80% 20%,rgba(114,217,255,.12),transparent 60%);
        pointer-events: none;
    }
    .stat-card-home:hover {
        transform: translateY(-6px) perspective(900px) rotateX(4deg) rotateY(-3deg);
        box-shadow: 0 40px 90px rgba(5,73,112,.38), inset 0 1px 0 rgba(255,255,255,.22);
        border-color: rgba(114,217,255,.5);
    }
    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(114,217,255,.15);
        border: 1px solid rgba(114,217,255,.25);
        margin-bottom: .4rem;
    }
    .stat-icon svg { color: #72d9ff; }
    .stat-label {
        font-size: .82rem;
        font-weight: 700;
        color: #9dcfe8;
        text-transform: uppercase;
        letter-spacing: .06em;
    }
    .stat-number {
        font-size: clamp(2.6rem,5vw,3.6rem);
        font-weight: 900;
        line-height: 1;
        color: #ffffff;
        letter-spacing: -.03em;
        animation: countUp .6s cubic-bezier(.2,.9,.2,1) both;
    }
    .stat-sublabel {
        font-size: .78rem;
        color: #7ab5d0;
        font-weight: 600;
    }
    .stat-arrow {
        margin-top: auto;
        align-self: flex-end;
        width: 30px; height: 30px;
        border-radius: 50%;
        background: rgba(114,217,255,.12);
        border: 1px solid rgba(114,217,255,.2);
        display: flex; align-items: center; justify-content: center;
        color: #72d9ff;
        transition: background .2s, transform .2s;
    }
    .stat-card-home:hover .stat-arrow {
        background: rgba(114,217,255,.25);
        transform: translate(2px,-2px);
    }

    /* ── Section headings ─────────────────────────────────────── */
    .home-section {
        width: min(1200px, calc(100% - 2rem));
        margin: 0 auto;
        padding: clamp(2rem,6vw,4.5rem) 0;
    }
    .home-section-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .home-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        font-size: .72rem;
        font-weight: 800;
        color: #0b8fe8;
        text-transform: uppercase;
        letter-spacing: .1em;
        margin-bottom: .6rem;
    }
    .home-eyebrow span {
        display: inline-block;
        width: 18px; height: 2px;
        border-radius: 2px;
        background: linear-gradient(90deg,#0b8fe8,transparent);
    }
    [data-theme="dark"] .home-eyebrow {
        color: #72d9ff;
    }
    [data-theme="dark"] .home-eyebrow span {
        background: linear-gradient(90deg,#72d9ff,transparent);
    }
    .home-section-heading h2 {
        margin: 0;
        font-size: clamp(1.8rem,4vw,3rem);
        line-height: 1.05;
        color: var(--ink);
    }
    .home-text-link {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        font-size: .85rem;
        font-weight: 700;
        color: #0b8fe8;
        white-space: nowrap;
        transition: gap .2s ease, opacity .2s ease;
        flex-shrink: 0;
    }
    [data-theme="dark"] .home-text-link {
        color: #72d9ff;
    }
    .home-text-link:hover { gap: .7rem; opacity: .85; }

    /* ── Actor cards (mahasiswa / dosen) ──────────────────────── */
    .actor-grid-home {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(270px,1fr));
        gap: 1.3rem;
    }
    .actor-card-home {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        padding: 2rem 1.8rem;
        border: 1px solid rgba(255,255,255,.12);
        display: flex;
        flex-direction: column;
        gap: .9rem;
        transition: transform .3s cubic-bezier(.2,.9,.2,1), box-shadow .3s ease;
    }
    .actor-card-home:hover {
        transform: translateY(-6px);
        box-shadow: 0 40px 90px rgba(3,20,38,.5);
    }
    .actor-card-home.mahasiswa {
        background: linear-gradient(145deg, rgba(2,84,163,.82), rgba(12,58,112,.85));
        box-shadow: 0 28px 64px rgba(2,84,163,.35), inset 0 1px 0 rgba(255,255,255,.18);
    }
    .actor-card-home.dosen {
        background: linear-gradient(145deg, rgba(2,108,111,.82), rgba(4,72,80,.85));
        box-shadow: 0 28px 64px rgba(2,108,111,.35), inset 0 1px 0 rgba(255,255,255,.18);
    }
    .actor-card-home::after {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,.04);
        pointer-events: none;
    }
    .actor-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.2);
        display: flex; align-items: center; justify-content: center;
    }
    .actor-icon svg { color: #ffffff; }
    .actor-card-home h3 {
        margin: 0;
        font-size: 1.4rem;
        font-weight: 800;
        color: #ffffff;
    }
    .actor-card-home p {
        margin: 0;
        font-size: .9rem;
        color: rgba(255,255,255,.72);
        line-height: 1.6;
        flex: 1;
    }

    /* ── Buttons (replace green) ──────────────────────────────── */
    .btn-home-primary {
        display: inline-flex;
        align-items: center;
        gap: .55rem;
        padding: .7rem 1.5rem;
        border-radius: 10px;
        font-size: .875rem;
        font-weight: 800;
        color: #00111d;
        background: linear-gradient(135deg, #dff8ff, #72d9ff 50%, #0b8fe8);
        box-shadow: 0 10px 28px rgba(5,150,216,.3);
        border: 1px solid rgba(255,255,255,.35);
        cursor: pointer;
        text-decoration: none;
        transition: transform .2s cubic-bezier(.2,.9,.2,1), box-shadow .2s ease, filter .2s ease;
    }
    .btn-home-primary:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 18px 44px rgba(5,150,216,.42);
        filter: brightness(1.06) saturate(1.1);
    }
    .btn-home-secondary {
        display: inline-flex;
        align-items: center;
        gap: .55rem;
        padding: .7rem 1.5rem;
        border-radius: 10px;
        font-size: .875rem;
        font-weight: 800;
        color: #ffffff;
        background: rgba(255,255,255,.12);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255,255,255,.22);
        cursor: pointer;
        text-decoration: none;
        transition: transform .2s cubic-bezier(.2,.9,.2,1), background .2s ease;
    }
    .btn-home-secondary:hover {
        background: rgba(255,255,255,.2);
        transform: translateY(-2px);
    }

    /* ── Document cards ───────────────────────────────────────── */
    .doc-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0,1fr));
        gap: 1.2rem;
    }
    .doc-card {
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(114,217,255,.18);
        border-radius: 16px;
        background: linear-gradient(145deg, rgba(22,111,153,.68), rgba(12,58,90,.75));
        box-shadow: 0 20px 60px rgba(5,73,112,.2), inset 0 1px 0 rgba(255,255,255,.14);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: .75rem;
        transition: transform .25s cubic-bezier(.2,.9,.2,1), box-shadow .25s ease, border-color .25s ease;
    }
    .doc-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 36px 80px rgba(5,73,112,.35);
        border-color: rgba(114,217,255,.38);
    }
    .doc-badge {
        display: inline-flex;
        padding: .22rem .7rem;
        border-radius: 999px;
        font-size: .7rem;
        font-weight: 800;
        letter-spacing: .05em;
        background: rgba(114,217,255,.18);
        border: 1px solid rgba(114,217,255,.3);
        color: #72d9ff;
        align-self: flex-start;
    }
    .doc-card h3 {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: #eaf6ff;
        line-height: 1.45;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .doc-card p {
        margin: 0;
        font-size: .8rem;
        color: #7ab5d0;
        font-weight: 600;
    }
    .doc-link {
        margin-top: auto;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        font-size: .82rem;
        font-weight: 700;
        color: #72d9ff;
        transition: gap .2s ease;
    }
    .doc-link:hover { gap: .6rem; }

    /* ── Session status cards ─────────────────────────────────── */
    .session-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px,1fr));
        gap: 1.2rem;
    }
    .session-card {
        border-radius: 16px;
        border: 1px solid;
        padding: 1.4rem;
        display: flex;
        flex-direction: column;
        gap: .6rem;
        transition: transform .25s ease;
    }
    .session-card:hover { transform: translateY(-4px); }
    .session-card.open {
        background: linear-gradient(145deg, rgba(2,108,78,.72), rgba(4,80,60,.78));
        border-color: rgba(50,214,166,.3);
        box-shadow: 0 22px 56px rgba(2,108,78,.28), inset 0 1px 0 rgba(255,255,255,.12);
    }
    .session-card.closed {
        background: linear-gradient(145deg, rgba(22,60,100,.72), rgba(12,40,72,.78));
        border-color: rgba(114,217,255,.15);
        box-shadow: 0 22px 56px rgba(5,30,60,.22), inset 0 1px 0 rgba(255,255,255,.08);
    }
    .session-status-badge {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        width: fit-content;
        padding: .25rem .75rem;
        border-radius: 999px;
        font-size: .7rem;
        font-weight: 800;
        letter-spacing: .05em;
    }
    .session-status-badge.open {
        background: rgba(50,214,166,.2);
        border: 1px solid rgba(50,214,166,.4);
        color: #32d6a6;
    }
    .session-status-badge.closed {
        background: rgba(114,217,255,.1);
        border: 1px solid rgba(114,217,255,.2);
        color: #72d9ff;
    }
    .status-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: currentColor;
        position: relative;
    }
    .status-dot.pulse::after {
        content: '';
        position: absolute;
        inset: -3px;
        border-radius: 50%;
        background: rgba(50,214,166,.5);
        animation: pulseRing 1.8s ease-out infinite;
    }
    .session-card h3 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 800;
        color: #e8f6ff;
    }
    .session-card p {
        margin: 0;
        font-size: .83rem;
        color: rgba(255,255,255,.55);
        line-height: 1.5;
    }

    /* ── Flow steps ───────────────────────────────────────────── */
    .flow-section {
        width: min(1200px, calc(100% - 2rem));
        margin: 0 auto;
        padding: clamp(2rem,6vw,4.5rem) 0;
    }
    .flow-split {
        display: grid;
        grid-template-columns: minmax(0,.85fr) minmax(280px,1fr);
        gap: 2.5rem;
        align-items: center;
    }
    .flow-split-text h2 {
        font-size: clamp(1.8rem,4vw,3rem);
        color: var(--ink);
        line-height: 1.1;
        margin: 0 0 1rem;
    }
    .flow-split-text p {
        color: var(--ink-soft);
        font-size: .95rem;
        line-height: 1.7;
        margin: 0;
    }
    .flow-steps {
        display: grid;
        grid-template-columns: repeat(2,1fr);
        gap: .8rem;
    }
    .flow-step {
        display: flex;
        flex-direction: column;
        gap: .5rem;
        padding: 1.1rem;
        border-radius: 14px;
        border: 1px solid rgba(114,217,255,.18);
        background: linear-gradient(145deg,rgba(22,111,153,.65),rgba(12,58,90,.72));
        box-shadow: inset 0 1px 0 rgba(255,255,255,.12);
        transition: border-color .2s ease, transform .2s ease;
    }
    .flow-step:hover {
        border-color: rgba(114,217,255,.45);
        transform: translateY(-3px);
    }
    .flow-step-num {
        width: 32px; height: 32px;
        border-radius: 8px;
        background: rgba(114,217,255,.15);
        border: 1px solid rgba(114,217,255,.25);
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem;
        font-weight: 900;
        color: #72d9ff;
    }
    .flow-step-label {
        font-size: .88rem;
        font-weight: 700;
        color: #e8f6ff;
    }

    /* ── Module cards ─────────────────────────────────────────── */
    .module-grid-home {
        display: grid;
        grid-template-columns: repeat(3, minmax(0,1fr));
        gap: 1.2rem;
    }
    .module-card-home {
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(114,217,255,.16);
        border-radius: 16px;
        background: linear-gradient(145deg,rgba(22,111,153,.65),rgba(12,58,90,.72));
        box-shadow: 0 20px 56px rgba(5,73,112,.18), inset 0 1px 0 rgba(255,255,255,.13);
        padding: 1.6rem 1.4rem;
        display: flex;
        flex-direction: column;
        gap: .7rem;
        transition: transform .25s cubic-bezier(.2,.9,.2,1), box-shadow .25s ease, border-color .25s ease;
    }
    .module-card-home:hover {
        transform: translateY(-5px);
        box-shadow: 0 36px 78px rgba(5,73,112,.3);
        border-color: rgba(114,217,255,.35);
    }
    .module-card-icon {
        width: 44px; height: 44px;
        border-radius: 11px;
        background: rgba(114,217,255,.12);
        border: 1px solid rgba(114,217,255,.2);
        display: flex; align-items: center; justify-content: center;
        color: #72d9ff;
        margin-bottom: .2rem;
    }
    .module-card-home h3 {
        margin: 0;
        font-size: 1rem;
        font-weight: 800;
        color: #e8f6ff;
    }
    .module-card-home p {
        margin: 0;
        font-size: .85rem;
        color: #7ab5d0;
        line-height: 1.6;
    }

    /* ── Empty state ──────────────────────────────────────────── */
    .home-empty {
        grid-column: 1 / -1;
        padding: 2.5rem;
        text-align: center;
        color: var(--ink-soft);
        border: 1px dashed var(--line);
        border-radius: 16px;
        font-weight: 600;
    }

    /* ── Divider ──────────────────────────────────────────────── */
    .home-divider {
        width: min(1200px, calc(100% - 2rem));
        margin: 0 auto;
        height: 1px;
        background: linear-gradient(90deg,transparent,var(--line),transparent);
    }

    /* ── Responsive ───────────────────────────────────────────── */
    @media (max-width: 900px) {
        .flow-split { grid-template-columns: 1fr; }
        .module-grid-home { grid-template-columns: repeat(2,1fr); }
        .doc-grid { grid-template-columns: repeat(2,1fr); }
    }
    @media (max-width: 600px) {
        .module-grid-home,
        .doc-grid { grid-template-columns: 1fr; }
        .flow-steps { grid-template-columns: 1fr; }
        .home-section-heading { flex-direction: column; align-items: flex-start; }
    }

    /* ── Contact / WhatsApp Banner ────────────────────────────────── */
    .contact-banner-home {
        width: min(1200px, calc(100% - 2rem));
        margin: 0 auto;
        padding: clamp(2rem, 5vw, 3rem);
        border-radius: 20px;
        background: linear-gradient(145deg, rgba(14, 114, 164, 0.45), rgba(6, 35, 60, 0.88));
        border: 1px solid rgba(114, 217, 255, 0.28);
        box-shadow: 0 25px 70px rgba(5, 73, 112, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        flex-wrap: wrap;
    }
    .contact-banner-info {
        max-width: 650px;
    }
    .contact-banner-info h3 {
        font-size: clamp(1.5rem, 3.5vw, 2.2rem);
        margin: 0 0 .6rem;
        color: #ffffff;
        font-weight: 800;
        line-height: 1.2;
    }
    .contact-banner-info p {
        margin: 0;
        color: #9dcfe8;
        font-size: .95rem;
        line-height: 1.65;
    }
    .btn-whatsapp {
        display: inline-flex;
        align-items: center;
        gap: .75rem;
        padding: .9rem 1.6rem;
        border-radius: 12px;
        font-size: .95rem;
        font-weight: 800;
        color: #ffffff;
        background: linear-gradient(135deg, #25D366, #128C7E);
        box-shadow: 0 12px 30px rgba(37, 211, 102, 0.35);
        border: 1px solid rgba(255, 255, 255, 0.3);
        text-decoration: none;
        transition: transform .25s ease, box-shadow .25s ease, filter .25s ease;
        white-space: nowrap;
    }
    .btn-whatsapp:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 18px 40px rgba(37, 211, 102, 0.48);
        filter: brightness(1.08);
    }

    /* Floating WhatsApp Button */
    .float-wa-btn {
        position: fixed;
        bottom: 24px;
        left: 24px;
        z-index: 99;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 18px 10px 14px;
        background: linear-gradient(135deg, #25D366, #128C7E);
        color: #ffffff;
        border-radius: 999px;
        box-shadow: 0 10px 25px rgba(37, 211, 102, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.3);
        text-decoration: none;
        font-size: 13.5px;
        font-weight: 700;
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .float-wa-btn:hover {
        transform: translateY(-4px) scale(1.03);
        box-shadow: 0 15px 35px rgba(37, 211, 102, 0.55);
        color: #ffffff;
    }
    @media (max-width: 600px) {
        .float-wa-btn span { display: none; }
        .float-wa-btn { padding: 12px; border-radius: 50%; }
    }

    /* ── FAQ Section ──────────────────────────────────────────── */
    .faq-section {
        width: min(1200px, calc(100% - 2rem));
        margin: 0 auto;
        padding: clamp(2rem,6vw,4.5rem) 0;
    }
    .faq-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(520px, 1fr));
        gap: .7rem;
        margin-top: 0;
    }
    @media (max-width: 700px) {
        .faq-grid { grid-template-columns: 1fr; }
    }
    .faq-item {
        border: 1px solid rgba(114,217,255,.18);
        border-radius: 14px;
        background: linear-gradient(145deg, rgba(22,111,153,.55), rgba(12,58,90,.65));
        box-shadow: inset 0 1px 0 rgba(255,255,255,.1);
        overflow: hidden;
        transition: border-color .2s ease;
    }
    .faq-item.open {
        border-color: rgba(114,217,255,.4);
    }
    .faq-question {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.1rem 1.3rem;
        background: none;
        border: none;
        cursor: pointer;
        text-align: left;
        font-size: .9rem;
        font-weight: 700;
        color: #e8f6ff;
        line-height: 1.4;
        transition: background .2s;
    }
    .faq-question:hover { background: rgba(255,255,255,.04); }
    .faq-chevron {
        flex-shrink: 0;
        color: #72d9ff;
        transition: transform .3s cubic-bezier(.2,.9,.2,1);
    }
    .faq-item.open .faq-chevron { transform: rotate(180deg); }
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height .4s cubic-bezier(.2,.9,.2,1), padding .3s ease;
        padding: 0 1.3rem;
    }
    .faq-answer p {
        margin: 0;
        font-size: .86rem;
        color: rgba(255,255,255,.65);
        line-height: 1.7;
        padding-bottom: 1.1rem;
    }
    .faq-item.open .faq-answer { max-height: 300px; }

    /* ── Back-to-Top Button ───────────────────────────────────── */
    .back-to-top {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 99;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(14,114,164,.85), rgba(37,99,235,.9));
        border: 1px solid rgba(114,217,255,.35);
        box-shadow: 0 8px 24px rgba(5,73,112,.35);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #ffffff;
        opacity: 0;
        transform: translateY(12px);
        transition: opacity .35s ease, transform .35s ease, box-shadow .2s ease;
        backdrop-filter: blur(10px);
    }
    .back-to-top.visible {
        opacity: 1;
        transform: translateY(0);
    }
    .back-to-top:hover {
        box-shadow: 0 14px 36px rgba(5,73,112,.5);
        transform: translateY(-3px);
    }
</style>

{{-- ══════════════════════════════════════════════════════════════
     HERO
══════════════════════════════════════════════════════════════ --}}
<section class="home-hero">
    <div class="hero-slideshow" aria-hidden="true">
        <span style="--bg: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=1800&q=85')"></span>
        <span style="--bg: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1800&q=85')"></span>
        <span style="--bg: url('https://images.unsplash.com/photo-1481627834876-b7833e8f5570b?auto=format&fit=crop&w=1800&q=85')"></span>
    </div>
    <div class="hero-overlay"></div>

    <div class="home-hero-content reveal">
        <p class="hero-badge">
            <span class="hero-badge-dot"></span>
            Repository Digital Kampus
        </p>

        <h1 class="hero-title">
            <span> Repository</span>
            <span>Universitas Metamedia</span>
        </h1>

        <p class="hero-lead">
            Kelola skripsi, laporan magang, PKM dosen, penelitian dosen, panduan, statistik,
            dan pencarian global dalam satu alur terstruktur.
        </p>

        <div class="hero-actions">
            <a href="{{ route('repository.index') }}" class="btn-home-primary">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
                Jelajahi Repository
            </a>
            <a href="{{ route('login') }}" class="btn-home-secondary">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Masuk E-Repository
            </a>
        </div>
    </div>

    <div class="hero-scroll" aria-hidden="true">
        <div class="hero-scroll-line"></div>
        <span>Gulir</span>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     STAT CARDS
══════════════════════════════════════════════════════════════ --}}
<section class="stats-section">
    <div class="stats-section-inner">
        @php
            $statIcons = [
                'skripsi' => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>',
                'magang'  => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>',
                'pkm'     => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>',
                'penelitian' => '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
            ];
            $statLabels = [
                'skripsi'    => 'Skripsi / TA',
                'magang'     => 'Laporan Magang',
                'pkm'        => 'PKM Dosen',
                'penelitian' => 'Penelitian Dosen',
            ];
        @endphp
        @foreach ($statLabels as $key => $label)
            <a href="{{ route('repository.index', $key) }}" class="stat-card-home reveal">
                <div class="stat-icon">{!! $statIcons[$key] !!}</div>
                <div class="stat-label">{{ $label }}</div>
                <div class="stat-number">{{ $stats[$key] ?? 0 }}</div>
                <div class="stat-sublabel">Total dokumen</div>
                <div class="stat-arrow">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                    </svg>
                </div>
            </a>
        @endforeach
    </div>
</section>

<div class="home-divider"></div>

{{-- ══════════════════════════════════════════════════════════════
     AKSES AKUN – MAHASISWA & DOSEN
══════════════════════════════════════════════════════════════ --}}
<section class="home-section">
    <div class="home-section-heading">
        <div>
            <p class="home-eyebrow"><span></span>Akses Akun</p>
            <h2>Portal Akses Mahasiswa &amp; Dosen</h2>
        </div>
    </div>

    <div class="actor-grid-home">
        {{-- Mahasiswa --}}
        <article class="actor-card-home mahasiswa reveal">
            <div class="actor-icon">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                </svg>
            </div>
            <h3>Mahasiswa</h3>
            <p>Gunakan akun yang diberikan admin untuk mengunggah skripsi atau laporan magang saat sesi dibuka. Setelah masuk, Anda akan diarahkan ke Beranda Mahasiswa.</p>
            <a href="{{ route('login') }}" class="btn-home-primary" style="align-self:flex-start">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Akses Akun Mahasiswa
            </a>
        </article>

        {{-- Dosen --}}
        <article class="actor-card-home dosen reveal">
            <div class="actor-icon">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <h3>Dosen</h3>
            <p>Gunakan akun yang diberikan admin untuk mengunggah PKM, penelitian, dan memberi ACC pada dokumen mahasiswa bimbingan.</p>
            <a href="{{ route('login') }}" class="btn-home-primary" style="align-self:flex-start">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Akses Akun Dosen
            </a>
        </article>
    </div>
</section>

<div class="home-divider"></div>

{{-- ══════════════════════════════════════════════════════════════
     DOKUMEN TERBARU
══════════════════════════════════════════════════════════════ --}}
<section class="home-section">
    <div class="home-section-heading">
        <div>
            <p class="home-eyebrow"><span></span>Dokumen Terbaru</p>
            <h2>Tambahan terbaru di repository</h2>
        </div>
        <a href="{{ route('repository.index', ['search' => '']) }}" class="home-text-link">
            Cari di repository
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
        </a>
    </div>

    <div class="doc-grid">
        @forelse ($featured->take(3) as $document)
            <article class="doc-card reveal">
                <span class="doc-badge">{{ strtoupper($document->kategori) }}</span>
                <h3>{{ $document->judul }}</h3>
                @if ($document->nama || $document->tahun)
                    <p>{{ collect([$document->nama, $document->tahun])->filter()->join(' · ') }}</p>
                @endif
                <a href="{{ route('repository.show', $document) }}" class="doc-link">
                    Lihat dokumen
                    <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                    </svg>
                </a>
            </article>
        @empty
            <div class="home-empty">Belum ada dokumen terbaru untuk ditampilkan.</div>
        @endforelse
    </div>
</section>

<div class="home-divider"></div>

{{-- ══════════════════════════════════════════════════════════════
     STATUS SESI UPLOAD
══════════════════════════════════════════════════════════════ --}}
<section class="home-section">
    <div class="home-section-heading">
        <div>
            <p class="home-eyebrow"><span></span>Status Sesi Upload</p>
            <h2>Pantau kategori yang sedang tersedia</h2>
        </div>
    </div>

    <div class="session-grid">
        @foreach (['skripsi' => 'Skripsi/TA', 'magang' => 'Laporan Magang', 'pkm' => 'PKM Dosen', 'penelitian' => 'Penelitian Dosen'] as $key => $label)
            @php $isOpen = $uploadStatuses[$key] ?? false; @endphp
            <article class="session-card {{ $isOpen ? 'open' : 'closed' }} reveal">
                <span class="session-status-badge {{ $isOpen ? 'open' : 'closed' }}">
                    <span class="status-dot {{ $isOpen ? 'pulse' : '' }}"></span>
                    {{ $isOpen ? 'Terbuka' : 'Tertutup' }}
                </span>
                <h3>{{ $label }}</h3>
                <p>{{ $isOpen ? 'Sesi upload sedang aktif. Silakan unggah dokumen Anda sekarang.' : 'Sesi upload belum dibuka oleh admin.' }}</p>
            </article>
        @endforeach
    </div>
</section>

<div class="home-divider"></div>

{{-- ══════════════════════════════════════════════════════════════
     ALUR SISTEM
══════════════════════════════════════════════════════════════ --}}
<section class="flow-section">
    <div class="flow-split">
        <div class="flow-split-text reveal">
            <p class="home-eyebrow"><span></span>Alur Sistem</p>
            <h2>Upload dibuka admin, lalu dokumen diverifikasi.</h2>
            <p>Mahasiswa dan dosen memakai akun dari admin. Dokumen mahasiswa di-ACC dosen pembimbing terlebih dahulu, kemudian masuk antrean verifikasi admin sebelum tampil di repository.</p>
        </div>

        <div class="flow-steps reveal">
            @foreach ([
                [1, 'Admin Buka Sesi'],
                [2, 'Login Akun'],
                [3, 'Upload Dokumen'],
                [4, 'ACC Dosen'],
                [5, 'Verifikasi Admin'],
                [6, 'Repository & Search'],
            ] as [$num, $step])
                <div class="flow-step">
                    <div class="flow-step-num">{{ $num }}</div>
                    <div class="flow-step-label">{{ $step }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<div class="home-divider"></div>

{{-- ══════════════════════════════════════════════════════════════
     MODUL UTAMA
══════════════════════════════════════════════════════════════ --}}
<section class="home-section" style="padding-bottom: clamp(3rem,8vw,6rem);">
    <div class="home-section-heading">
        <div>
            <p class="home-eyebrow"><span></span>Modul Utama</p>
            <h2>Struktur sesuai dokumen rancangan</h2>
        </div>
        <a href="{{ route('repository.index') }}" class="home-text-link">
            Lihat repository
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
            </svg>
        </a>
    </div>

    <div class="module-grid-home">
        @foreach ([
            ['Akses Akun',      'Admin membuat akun mahasiswa dan dosen beserta password awal.',
             '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>'],
            ['TA / Skripsi',    'Upload PDF mahasiswa wajib memilih dosen pembimbing.',
             '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>'],
            ['Karya Dosen',     'PKM dan penelitian dosen masuk antrean verifikasi admin.',
             '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>'],
            ['ACC Pembimbing',  'Skripsi dan laporan magang harus disetujui dosen pembimbing.',
             '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>'],
            ['Panduan & Template','Dokumen acuan resmi yang dikelola admin.',
             '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>'],
            ['Search Global',   'Pencarian judul, NIM, nama, NIDN, tahun, prodi, dan status.',
             '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>'],
        ] as [$title, $body, $icon])
            <article class="module-card-home reveal">
                <div class="module-card-icon">{!! $icon !!}</div>
                <h3>{{ $title }}</h3>
                <p>{{ $body }}</p>
            </article>
        @endforeach
    </div>
</section>

<div class="home-divider"></div>

{{-- ══════════════════════════════════════════════════════════════
     FAQ
══════════════════════════════════════════════════════════════ --}}
<section class="faq-section">
    <div class="home-section-heading">
        <div>
            <p class="home-eyebrow"><span></span>FAQ</p>
            <h2>Pertanyaan yang Sering Ditanyakan</h2>
        </div>
    </div>

    <div class="faq-grid">
        @foreach ([
            [
                'Bagaimana cara mendapatkan akun untuk upload dokumen?',
                'Akun mahasiswa dan dosen dibuat langsung oleh admin. Hubungi admin melalui WhatsApp atau email untuk meminta pembuatan akun beserta password awal.'
            ],
            [
                'Kapan sesi upload dokumen dibuka?',
                'Sesi upload dibuka dan ditutup oleh admin sesuai jadwal. Pantau status sesi pada bagian "Status Sesi Upload" di halaman beranda ini.'
            ],
            [
                'Berapa batas ukuran file yang bisa diupload?',
                'Ukuran file maksimal adalah 10 MB dengan format PDF. Pastikan file sudah terkompresi dengan baik sebelum diunggah.'
            ],
            [
                'Apakah dokumen langsung muncul di repository setelah upload?',
                'Tidak. Dokumen mahasiswa harus mendapat persetujuan dosen pembimbing terlebih dahulu, kemudian diverifikasi oleh admin sebelum tampil di repository publik.'
            ],
            [
                'Apa perbedaan antara Skripsi/TA, Laporan Magang, PKM, dan Penelitian?',
                'Skripsi/TA dan Laporan Magang diupload oleh mahasiswa. PKM (Program Kreativitas Mahasiswa dosen) dan Penelitian Dosen diupload oleh dosen. Masing-masing memiliki sesi upload tersendiri.'
            ],
            [
                'Bagaimana jika dokumen saya ditolak oleh dosen atau admin?',
                'Anda akan mendapat notifikasi dan dapat mengupload ulang dokumen yang telah diperbaiki sesuai catatan dari dosen atau admin.'
            ],
        ] as [$q, $a])
        <div class="faq-item reveal">
            <button class="faq-question" type="button" aria-expanded="false">
                <span>{{ $q }}</span>
                <svg class="faq-chevron" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div class="faq-answer" role="region">
                <p>{{ $a }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>

<div class="home-divider"></div>

{{-- ══════════════════════════════════════════════════════════════
     BANTUAN & KONTAK WHATSAPP
══════════════════════════════════════════════════════════════ --}}
<section class="home-section" style="padding-bottom: clamp(3rem,8vw,6rem);">
    <div class="contact-banner-home reveal">
        <div class="contact-banner-info">
            <p class="home-eyebrow"><span></span>Pusat Bantuan &amp; Informasi</p>
            <h3>Punya Pertanyaan tentang Repository?</h3>
            <p>Jika Anda mengalami kendala saat mengunggah dokumen, butuh bantuan akun, atau ingin bertanya seputar layanan e-repository, silakan hubungi tim kami via WhatsApp.</p>
        </div>
        <div>
            <a href="https://wa.me/6285363097108?text=Halo%20Admin%20Repository%20Universitas%20Metamedia,%20saya%20ingin%20bertanya" target="_blank" rel="noopener noreferrer" class="btn-whatsapp">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.105 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
                <span>Tanya via WhatsApp</span>
            </a>
        </div>
    </div>
</section>

{{-- Floating WhatsApp Contact Button --}}
<a href="https://wa.me/6285363097108?text=Halo%20Admin%20Repository%20Universitas%20Metamedia,%20saya%20ingin%20bertanya" target="_blank" rel="noopener noreferrer" class="float-wa-btn" title="Hubungi Admin WhatsApp (+62 853-6309-7108)">
    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.105 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
    </svg>
    <span>Hubungi WA (+62 853-6309-7108)</span>
</a>

{{-- Back-to-Top Button --}}
<button class="back-to-top" id="back-to-top" aria-label="Kembali ke atas" title="Kembali ke atas">
    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
</button>

<script>
(function () {
    /* ── FAQ Accordion ─────────────────────────────────────── */
    document.querySelectorAll('.faq-item').forEach(function (item) {
        const btn = item.querySelector('.faq-question');
        if (!btn) return;
        btn.addEventListener('click', function () {
            const isOpen = item.classList.contains('open');
            // Close all others
            document.querySelectorAll('.faq-item.open').forEach(function (o) {
                o.classList.remove('open');
                o.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
            });
            if (!isOpen) {
                item.classList.add('open');
                btn.setAttribute('aria-expanded', 'true');
            }
        });
    });

    /* ── Back-to-Top ───────────────────────────────────────── */
    const bttBtn = document.getElementById('back-to-top');
    if (bttBtn) {
        window.addEventListener('scroll', function () {
            bttBtn.classList.toggle('visible', window.scrollY > 320);
        }, { passive: true });
        bttBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* ── Counter Animasi (IntersectionObserver) ────────────── */
    function animateCount(el) {
        const target = parseInt(el.textContent.replace(/\D/g, ''), 10);
        if (isNaN(target) || target === 0) return;
        const duration = 1200;
        const start = performance.now();
        el.textContent = '0';
        function step(now) {
            const elapsed = now - start;
            const progress = Math.min(elapsed / duration, 1);
            // Ease-out cubic
            const ease = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(ease * target).toLocaleString('id-ID');
            if (progress < 1) requestAnimationFrame(step);
            else el.textContent = target.toLocaleString('id-ID');
        }
        requestAnimationFrame(step);
    }

    const counterObs = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                animateCount(entry.target);
                counterObs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.stat-number').forEach(function (el) {
        counterObs.observe(el);
    });
})();
</script>

@endsection
