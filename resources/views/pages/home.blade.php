@extends('layouts.app')

@section('title', 'Beranda - E-Repository Universitas Metamedia')

@section('content')

{{-- ══════════════════════════════════════════════════════════════
     AESTHETIC STYLES & ANIMATIONS
══════════════════════════════════════════════════════════════ --}}
<style>
    /* ── Keyframe Animations ────────────────────────────────────── */
    @keyframes heroFloatOrb {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -40px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.95); }
    }
    @keyframes heroBadgePulse {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-4px); }
    }
    @keyframes pulseGlow {
        0% { transform: scale(1); opacity: .7; }
        70% { transform: scale(1.6); opacity: 0; }
        100% { transform: scale(1); opacity: 0; }
    }
    @keyframes shimmerTitle {
        0%, 100% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
    }
    @keyframes modalFadeIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    /* ── Library Hero Slideshow ─────────────────────────────────── */
    .hero-slideshow-container {
        position: absolute;
        inset: 0;
        z-index: 1;
        overflow: hidden;
    }
    .hero-slide {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        opacity: 0;
        transform: scale(1.06);
        transition: opacity 1.2s cubic-bezier(0.4, 0, 0.2, 1), transform 5s cubic-bezier(0.2, 0.9, 0.2, 1);
        will-change: opacity, transform;
    }
    .hero-slide.active {
        opacity: 0.85;
        transform: scale(1);
    }
    .hero-slideshow-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(4, 19, 36, 0.45) 0%, rgba(3, 13, 23, 0.6) 50%, rgba(3, 13, 23, 0.95) 100%);
        pointer-events: none;
    }
    .hero-slide-indicators {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 1.5rem;
        padding: 0.35rem 0.75rem;
        background: rgba(15, 23, 42, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 999px;
        backdrop-filter: blur(12px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.4);
    }
    .slide-dot {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.45);
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        padding: 0;
    }
    .slide-dot.active {
        width: 24px;
        background: #38bdf8;
        box-shadow: 0 0 12px rgba(56, 189, 248, 0.8);
    }

    /* ── Hero Section ───────────────────────────────────────────── */
    .hero-ultra {
        position: relative;
        overflow: hidden;
        min-height: 86vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: clamp(4.5rem, 9vw, 7.5rem) clamp(1.2rem, 4vw, 4rem);
        background: #041324;
    }

    .hero-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(90px);
        pointer-events: none;
        opacity: 0.35;
        z-index: 2;
        animation: heroFloatOrb 16s ease-in-out infinite alternate;
    }
    .hero-orb-1 {
        width: 360px; height: 360px;
        top: 5%; left: 10%;
        background: rgba(14, 165, 233, 0.3);
    }
    .hero-orb-2 {
        width: 420px; height: 420px;
        bottom: 5%; right: 10%;
        background: rgba(59, 130, 246, 0.25);
        animation-delay: -5s;
    }
    .hero-orb-3 {
        width: 280px; height: 280px;
        top: 40%; right: 25%;
        background: rgba(16, 185, 129, 0.18);
        animation-delay: -9s;
    }

    .hero-grid-pattern {
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px);
        background-size: 32px 32px;
        mask-image: radial-gradient(ellipse 65% 65% at 50% 50%, #000 70%, transparent 100%);
        pointer-events: none;
        z-index: 2;
    }

    .hero-content-wrap {
        position: relative;
        z-index: 3;
        max-width: 920px;
        width: 100%;
        text-align: center;
        margin: 0 auto;
    }

    /* Live Badge */
    .hero-live-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.4rem 1.1rem;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(14px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.2);
        color: #e2f2ff;
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin-bottom: 1.6rem;
        animation: heroBadgePulse 4s ease-in-out infinite;
    }
    .hero-live-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #38bdf8;
        position: relative;
    }
    .hero-live-dot::after {
        content: '';
        position: absolute;
        inset: -3px;
        border-radius: 50%;
        border: 1.5px solid #38bdf8;
        animation: pulseGlow 1.8s ease-out infinite;
    }

    /* Hero Heading */
    .hero-main-title {
        font-size: clamp(2.4rem, 6.2vw, 4.8rem);
        font-weight: 900;
        line-height: 1.08;
        letter-spacing: -0.03em;
        margin: 0 0 1.2rem;
        color: #ffffff;
        text-shadow: 0 4px 30px rgba(0, 0, 0, 0.85), 0 2px 10px rgba(0, 0, 0, 0.7);
    }
    .hero-gradient-text {
        background: linear-gradient(120deg, #ffffff 10%, #7dd3fc 45%, #38bdf8 70%, #93c5fd 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: shimmerTitle 8s linear infinite;
        filter: drop-shadow(0 4px 20px rgba(0, 0, 0, 0.8));
    }

    .hero-subtitle {
        font-size: clamp(1rem, 2vw, 1.2rem);
        line-height: 1.65;
        color: rgba(240, 249, 255, 0.95);
        max-width: 680px;
        margin: 0 auto 2.2rem;
        text-shadow: 0 2px 14px rgba(0, 0, 0, 0.85), 0 1px 4px rgba(0, 0, 0, 0.7);
    }

    /* Hero Interactive Live Search */
    .hero-search-box {
        max-width: 720px;
        margin: 0 auto 2rem;
        background: rgba(15, 23, 42, 0.82);
        border: 1px solid rgba(255, 255, 255, 0.28);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 0.5rem;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.2);
        transition: border-color 0.3s, box-shadow 0.3s;
    }
    .hero-search-box:focus-within {
        border-color: rgba(56, 189, 248, 0.8);
        box-shadow: 0 25px 60px rgba(14, 165, 233, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }
    .hero-search-input-wrap {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        padding: 0.4rem 0.8rem;
    }
    .hero-search-input {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        color: #ffffff;
        font-size: 0.95rem;
        font-weight: 500;
    }
    .hero-search-input::placeholder {
        color: rgba(224, 242, 254, 0.6);
    }
    .hero-search-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #0284c7, #2563eb);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 14px;
        padding: 0.75rem 1.4rem;
        font-size: 0.875rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 8px 20px rgba(2, 132, 199, 0.35);
    }
    .hero-search-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 28px rgba(2, 132, 199, 0.5);
        filter: brightness(1.1);
    }
    .kbd-shortcut {
        display: inline-flex;
        align-items: center;
        padding: 0.15rem 0.45rem;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.22);
        border-radius: 6px;
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.75);
        font-family: monospace;
    }

    /* Quick Filter Chips */
    .hero-quick-chips {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 0.6rem;
    }
    .chip-item {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.35rem 0.85rem;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.75);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #f0f9ff;
        font-size: 0.78rem;
        font-weight: 600;
        text-decoration: none;
        backdrop-filter: blur(12px);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.35);
        transition: all 0.2s ease;
    }
    .chip-item:hover {
        background: rgba(56, 189, 248, 0.25);
        border-color: rgba(56, 189, 248, 0.5);
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(56, 189, 248, 0.3);
    }
    .chip-item:hover {
        background: rgba(56, 189, 248, 0.15);
        border-color: rgba(56, 189, 248, 0.35);
        color: #ffffff;
        transform: translateY(-2px);
    }

    /* ── Statistics Cards with 3D Tilt ──────────────────────────── */
    .stats-container {
        width: min(1200px, calc(100% - 2.5rem));
        margin: -3.5rem auto 3rem;
        position: relative;
        z-index: 10;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.25rem;
    }
    .stat-card-glass {
        position: relative;
        border-radius: 22px;
        padding: 1.6rem 1.4rem;
        text-decoration: none;
        color: inherit;
        border: 1px solid rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(16px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.18);
        transition: transform 0.25s cubic-bezier(0.2, 0.9, 0.2, 1), box-shadow 0.25s, border-color 0.25s;
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
        overflow: hidden;
    }
    .stat-card-glass::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.12), transparent 70%);
        pointer-events: none;
    }
    .stat-card-glass:hover {
        transform: translateY(-6px);
        border-color: rgba(56, 189, 248, 0.45);
        box-shadow: 0 30px 70px rgba(2, 132, 199, 0.28);
    }
    .stat-card-glass.theme-skripsi {
        background: linear-gradient(145deg, rgba(2, 132, 199, 0.45), rgba(7, 89, 133, 0.65));
    }
    .stat-card-glass.theme-magang {
        background: linear-gradient(145deg, rgba(14, 165, 233, 0.45), rgba(3, 105, 161, 0.65));
    }
    .stat-card-glass.theme-pkm {
        background: linear-gradient(145deg, rgba(99, 102, 241, 0.45), rgba(67, 56, 202, 0.65));
    }
    .stat-card-glass.theme-penelitian {
        background: linear-gradient(145deg, rgba(16, 185, 129, 0.45), rgba(5, 150, 105, 0.65));
    }

    .stat-glass-icon {
        width: 46px; height: 46px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #ffffff;
        margin-bottom: 0.2rem;
    }
    .stat-glass-label {
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: rgba(224, 242, 254, 0.85);
    }
    .stat-glass-num {
        font-size: clamp(2.2rem, 4vw, 3rem);
        font-weight: 900;
        line-height: 1;
        color: #ffffff;
        letter-spacing: -0.03em;
    }
    .stat-glass-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: auto;
        padding-top: 0.4rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        font-size: 0.75rem;
        color: rgba(224, 242, 254, 0.65);
    }

    /* ── Content Sections ───────────────────────────────────────── */
    .section-wrapper {
        width: min(1200px, calc(100% - 2.5rem));
        margin: 0 auto;
        padding: clamp(2.5rem, 5vw, 4.5rem) 0;
    }
    .section-header-modern {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        margin-bottom: 2rem;
    }
    @media (min-width: 640px) {
        .section-header-modern {
            flex-direction: row;
            align-items: flex-end;
            justify-content: space-between;
        }
    }
    .section-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #38bdf8;
    }
    .section-eyebrow span {
        width: 18px; height: 2px;
        background: linear-gradient(90deg, #38bdf8, transparent);
        border-radius: 2px;
    }
    .section-title {
        font-size: clamp(1.6rem, 3.5vw, 2.5rem);
        font-weight: 800;
        line-height: 1.15;
        color: var(--ink, #ffffff);
        margin: 0;
    }

    /* Category Tab Switcher */
    .tab-nav {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.8rem;
    }
    .tab-btn {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        padding: 0.55rem 1.1rem;
        font-size: 0.82rem;
        font-weight: 700;
        color: rgba(224, 242, 254, 0.7);
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .tab-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
    }
    .tab-btn.active {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.3), rgba(37, 99, 235, 0.4));
        border-color: rgba(56, 189, 248, 0.5);
        color: #ffffff;
        box-shadow: 0 4px 16px rgba(14, 165, 233, 0.25);
    }

    /* Document Grid & Cards */
    .doc-showcase-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
        gap: 1.3rem;
    }
    .doc-showcase-card {
        border-radius: 18px;
        padding: 1.5rem;
        background: linear-gradient(145deg, rgba(15, 23, 42, 0.75), rgba(30, 41, 59, 0.8));
        border: 1px solid rgba(255, 255, 255, 0.12);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.1);
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
        transition: all 0.25s cubic-bezier(0.2, 0.9, 0.2, 1);
        position: relative;
    }
    .doc-showcase-card:hover {
        transform: translateY(-5px);
        border-color: rgba(56, 189, 248, 0.4);
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.4);
    }
    .doc-cat-tag {
        display: inline-flex;
        align-items: center;
        padding: 0.22rem 0.65rem;
        border-radius: 999px;
        font-size: 0.68rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        width: fit-content;
    }
    .doc-cat-tag.skripsi {
        background: rgba(2, 132, 199, 0.2);
        border: 1px solid rgba(2, 132, 199, 0.4);
        color: #38bdf8;
    }
    .doc-cat-tag.magang {
        background: rgba(14, 165, 233, 0.2);
        border: 1px solid rgba(14, 165, 233, 0.4);
        color: #7dd3fc;
    }
    .doc-cat-tag.pkm {
        background: rgba(99, 102, 241, 0.2);
        border: 1px solid rgba(99, 102, 241, 0.4);
        color: #a5b4fc;
    }
    .doc-cat-tag.penelitian {
        background: rgba(16, 185, 129, 0.2);
        border: 1px solid rgba(16, 185, 129, 0.4);
        color: #6ee7b7;
    }

    .doc-card-title {
        font-size: 0.98rem;
        font-weight: 700;
        line-height: 1.4;
        color: #ffffff;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .doc-card-meta {
        font-size: 0.78rem;
        color: rgba(224, 242, 254, 0.65);
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    .doc-card-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: auto;
        padding-top: 0.8rem;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }
    .btn-quick-view {
        background: transparent;
        border: none;
        color: #38bdf8;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0;
        transition: color 0.2s;
    }
    .btn-quick-view:hover {
        color: #7dd3fc;
        text-decoration: underline;
    }
    .btn-doc-link {
        color: rgba(255, 255, 255, 0.8);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px; height: 32px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        transition: all 0.2s;
    }
    .btn-doc-link:hover {
        background: rgba(56, 189, 248, 0.25);
        color: #ffffff;
        transform: translateX(2px);
    }

    /* ── Quick Preview Modal ────────────────────────────────────── */
    .preview-modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(3, 13, 23, 0.75);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.25s ease;
    }
    .preview-modal-backdrop.active {
        opacity: 1;
        pointer-events: auto;
    }
    .preview-modal-dialog {
        background: linear-gradient(145deg, #0b1e33, #071524);
        border: 1px solid rgba(56, 189, 248, 0.3);
        border-radius: 24px;
        width: min(650px, 100%);
        padding: 2rem;
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255, 255, 255, 0.2);
        display: flex;
        flex-direction: column;
        gap: 1.2rem;
        animation: modalFadeIn 0.3s cubic-bezier(0.2, 0.9, 0.2, 1) forwards;
        max-height: 88vh;
        overflow-y: auto;
    }

    /* ── System Flow & Modules ──────────────────────────────────── */
    .flow-card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 1rem;
    }
    .flow-item-card {
        background: linear-gradient(145deg, rgba(15, 23, 42, 0.7), rgba(30, 41, 59, 0.75));
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 16px;
        padding: 1.25rem 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
        transition: all 0.2s;
    }
    .flow-item-card:hover {
        border-color: rgba(56, 189, 248, 0.4);
        transform: translateY(-3px);
    }
    .flow-num-badge {
        width: 32px; height: 32px;
        border-radius: 10px;
        background: rgba(56, 189, 248, 0.15);
        border: 1px solid rgba(56, 189, 248, 0.3);
        display: flex; align-items: center; justify-content: center;
        font-weight: 800;
        font-size: 0.8rem;
        color: #38bdf8;
    }

    /* ── Session Status Grid & Cards ───────────────────────────── */
    .session-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.25rem;
    }
    .session-card {
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        backdrop-filter: blur(14px);
        transition: transform 0.25s cubic-bezier(0.2, 0.9, 0.2, 1), box-shadow 0.25s, border-color 0.25s;
    }
    .session-card:hover {
        transform: translateY(-4px);
    }
    .session-card.open {
        background: linear-gradient(145deg, rgba(6, 78, 59, 0.75), rgba(4, 47, 46, 0.85));
        border-color: rgba(52, 211, 153, 0.35);
        box-shadow: 0 20px 50px rgba(6, 78, 59, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.15);
    }
    .session-card.closed {
        background: linear-gradient(145deg, rgba(15, 23, 42, 0.75), rgba(30, 41, 59, 0.8));
        border-color: rgba(255, 255, 255, 0.1);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25), inset 0 1px 0 rgba(255, 255, 255, 0.08);
    }
    .session-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        width: fit-content;
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .session-status-badge.open {
        background: rgba(52, 211, 153, 0.2);
        border: 1px solid rgba(52, 211, 153, 0.4);
        color: #34d399;
    }
    .session-status-badge.closed {
        background: rgba(148, 163, 184, 0.15);
        border: 1px solid rgba(148, 163, 184, 0.25);
        color: #94a3b8;
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
        background: rgba(52, 211, 153, 0.5);
        animation: pulseGlow 1.8s ease-out infinite;
    }
    .session-card h3 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 800;
        color: #ffffff;
    }
    .session-card .status-desc {
        margin: 0;
        font-size: 0.82rem;
        color: rgba(224, 242, 254, 0.7);
        line-height: 1.55;
    }
    .session-upload-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
        width: fit-content;
    }
    .session-upload-btn.is-open {
        background: linear-gradient(135deg, #059669, #10b981);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #ffffff;
        box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
    }
    .session-upload-btn.is-open:hover {
        filter: brightness(1.1);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5);
    }
    .session-upload-btn.is-closed {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: rgba(255, 255, 255, 0.4);
        cursor: not-allowed;
    }

    /* ── FAQ Accordion ─────────────────────────────────────────── */
    .faq-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(480px, 1fr));
        gap: 0.85rem;
    }
    @media (max-width: 640px) {
        .faq-grid { grid-template-columns: 1fr; }
    }
    .faq-item {
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 16px;
        background: linear-gradient(145deg, rgba(15, 23, 42, 0.7), rgba(30, 41, 59, 0.75));
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
        overflow: hidden;
        transition: border-color 0.2s ease;
    }
    .faq-item.open {
        border-color: rgba(56, 189, 248, 0.4);
    }
    .faq-question {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.15rem 1.4rem;
        background: none;
        border: none;
        cursor: pointer;
        text-align: left;
        font-size: 0.92rem;
        font-weight: 700;
        color: #ffffff;
        line-height: 1.4;
        transition: background 0.2s;
    }
    .faq-question:hover { background: rgba(255, 255, 255, 0.04); }
    .faq-chevron {
        flex-shrink: 0;
        color: #38bdf8;
        transition: transform 0.3s cubic-bezier(0.2, 0.9, 0.2, 1);
    }
    .faq-item.open .faq-chevron { transform: rotate(180deg); }
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.35s cubic-bezier(0.2, 0.9, 0.2, 1), padding 0.3s ease;
        padding: 0 1.4rem;
    }
    .faq-answer p {
        margin: 0;
        font-size: 0.86rem;
        color: rgba(224, 242, 254, 0.75);
        line-height: 1.7;
        padding-bottom: 1.2rem;
    }
    .faq-item.open .faq-answer { max-height: 300px; }

    /* ── Contact Banner & Floating WA ──────────────────────────── */
    .contact-banner-home {
        border-radius: 24px;
        background: linear-gradient(145deg, rgba(2, 132, 199, 0.35), rgba(15, 23, 42, 0.85));
        border: 1px solid rgba(56, 189, 248, 0.25);
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.15);
        padding: clamp(2rem, 5vw, 3rem);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
        flex-wrap: wrap;
    }
    .contact-banner-info { max-width: 620px; }
    .contact-banner-info h3 {
        font-size: clamp(1.4rem, 3.2vw, 2rem);
        margin: 0 0 0.5rem;
        color: #ffffff;
        font-weight: 800;
        line-height: 1.2;
    }
    .contact-banner-info p {
        margin: 0;
        color: rgba(224, 242, 254, 0.75);
        font-size: 0.92rem;
        line-height: 1.65;
    }
    .btn-whatsapp {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 1.6rem;
        border-radius: 14px;
        font-size: 0.92rem;
        font-weight: 800;
        color: #ffffff;
        background: linear-gradient(135deg, #25D366, #128C7E);
        box-shadow: 0 10px 25px rgba(37, 211, 102, 0.35);
        border: 1px solid rgba(255, 255, 255, 0.25);
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease, filter 0.2s ease;
        white-space: nowrap;
    }
    .btn-whatsapp:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(37, 211, 102, 0.45);
        filter: brightness(1.08);
    }
    .float-wa-btn {
        position: fixed;
        bottom: 24px;
        left: 24px;
        z-index: 99;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px 10px 14px;
        background: linear-gradient(135deg, #25D366, #128C7E);
        color: #ffffff;
        border-radius: 999px;
        box-shadow: 0 10px 25px rgba(37, 211, 102, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.3);
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .float-wa-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 30px rgba(37, 211, 102, 0.55);
        color: #ffffff;
    }
    @media (max-width: 600px) {
        .float-wa-btn span { display: none; }
        .float-wa-btn { padding: 12px; border-radius: 50%; }
    }

    /* ── Back to Top & Utility ─────────────────────────────────── */
    .back-to-top {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 99;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(135deg, #0284c7, #2563eb);
        border: 1px solid rgba(255, 255, 255, 0.25);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #ffffff;
        opacity: 0;
        transform: translateY(12px);
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    .back-to-top.visible {
        opacity: 1;
        transform: translateY(0);
    }
    .back-to-top:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(2, 132, 199, 0.5);
    }
    .home-divider {
        width: min(1200px, calc(100% - 2.5rem));
        margin: 0 auto;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.12), transparent);
    }
    .home-empty {
        padding: 3rem;
        text-align: center;
        color: rgba(224, 242, 254, 0.6);
        border: 1px dashed rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.95rem;
    }
    .home-text-link {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.85rem;
        font-weight: 700;
        color: #38bdf8;
        text-decoration: none;
        transition: gap 0.2s ease;
    }
    .home-text-link:hover {
        gap: 0.65rem;
        color: #7dd3fc;
    }

    /* ── Responsive Refinements ─────────────────────────────────── */
    @media (max-width: 640px) {
        .stats-container {
            margin-top: -1.5rem;
            grid-template-columns: 1fr;
        }
        .hero-search-input-wrap {
            flex-direction: column;
            gap: 0.5rem;
        }
        .hero-search-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

{{-- ══════════════════════════════════════════════════════════════
     HERO SECTION
══════════════════════════════════════════════════════════════ --}}
<section class="hero-ultra">
    {{-- Library Background Slideshow (6 Curated Atmospheric Images) --}}
    <div class="hero-slideshow-container" aria-hidden="true" id="hero-slideshow">
        <div class="hero-slide active" style="background-image: url('https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=1920&q=80');"></div>
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&w=1920&q=80');"></div>
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1920&q=80');"></div>
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1481627834876-b7833e8f5570b?auto=format&fit=crop&w=1920&q=80');"></div>
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1568667256549-094345857637?auto=format&fit=crop&w=1920&q=80');"></div>
        <div class="hero-slide" style="background-image: url('https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?auto=format&fit=crop&w=1920&q=80');"></div>
        <div class="hero-slideshow-overlay"></div>
    </div>

    {{-- Ambient Lighting Orbs --}}
    <div class="hero-orb hero-orb-1" aria-hidden="true"></div>
    <div class="hero-orb hero-orb-2" aria-hidden="true"></div>
    <div class="hero-orb hero-orb-3" aria-hidden="true"></div>
    <div class="hero-grid-pattern" aria-hidden="true"></div>

    <div class="hero-content-wrap">
        {{-- Live Status Pill --}}
        <div class="hero-live-badge">
            <span class="hero-live-dot"></span>
            <span>E-Repository Universitas Metamedia &bull; {{ date('Y') }}</span>
        </div>

        {{-- Main Heading --}}
        <h1 class="hero-main-title">
            Pusat Repositori Digital<br>
            <span class="hero-gradient-text">Karya Ilmiah &amp; Riset</span>
        </h1>

        <p class="hero-subtitle">
            Akses publik dan pengelolaan komprehensif untuk Skripsi, Laporan Magang, PKM Dosen, serta Penelitian Akademik dalam satu sistem terintegrasi.
        </p>

        {{-- Interactive Hero Live Search --}}
        <form action="{{ route('repository.index') }}" method="GET" class="hero-search-box">
            <div class="hero-search-input-wrap">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" class="text-sky-400 shrink-0">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>
                </svg>

                <input type="search"
                       name="search"
                       id="hero-search-input"
                       class="hero-search-input"
                       placeholder="Cari judul skripsi, nama mahasiswa, NIM, NIDN, atau topik riset..."
                       autocomplete="off">

                <span class="kbd-shortcut hidden sm:inline-flex" title="Tekan '/' untuk mencari">/</span>

                <button type="submit" class="hero-search-btn">
                    <span>Cari Dokumen</span>
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>
            </div>
        </form>

        {{-- Quick Category Filter Chips --}}
        <div class="hero-quick-chips">
            <span class="text-xs font-semibold text-sky-200/60 mr-1">Kategori Cepat:</span>
            <a href="{{ route('repository.index', 'skripsi') }}" class="chip-item">
                <span>🎓 Skripsi / TA</span>
            </a>
            <a href="{{ route('repository.index', 'magang') }}" class="chip-item">
                <span>💼 Laporan Magang</span>
            </a>
            <a href="{{ route('repository.index', 'pkm') }}" class="chip-item">
                <span>💡 PKM Dosen</span>
            </a>
            <a href="{{ route('repository.index', 'penelitian') }}" class="chip-item">
                <span>🔬 Penelitian Dosen</span>
            </a>
            <a href="{{ route('login') }}" class="chip-item" style="border-color:rgba(56,189,248,0.35);color:#7dd3fc;">
                <span>🔐 Masuk Akun</span>
            </a>
        </div>

        {{-- Slideshow Navigation Dots --}}
        <div class="hero-slide-indicators" aria-label="Navigasi Latar Perpustakaan">
            <button type="button" class="slide-dot active" data-slide="0" aria-label="Slide 1"></button>
            <button type="button" class="slide-dot" data-slide="1" aria-label="Slide 2"></button>
            <button type="button" class="slide-dot" data-slide="2" aria-label="Slide 3"></button>
            <button type="button" class="slide-dot" data-slide="3" aria-label="Slide 4"></button>
            <button type="button" class="slide-dot" data-slide="4" aria-label="Slide 5"></button>
            <button type="button" class="slide-dot" data-slide="5" aria-label="Slide 6"></button>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     LIVE STATISTICS CARDS (3D TILT & ANIMATED COUNT)
══════════════════════════════════════════════════════════════ --}}
<div class="stats-container">
    @php
        $statData = [
            'skripsi' => [
                'label' => 'Skripsi / TA',
                'theme' => 'theme-skripsi',
                'icon'  => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 013 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>',
            ],
            'magang' => [
                'label' => 'Laporan Magang',
                'theme' => 'theme-magang',
                'icon'  => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>',
            ],
            'pkm' => [
                'label' => 'PKM Dosen',
                'theme' => 'theme-pkm',
                'icon'  => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>',
            ],
            'penelitian' => [
                'label' => 'Penelitian Dosen',
                'theme' => 'theme-penelitian',
                'icon'  => '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
            ],
        ];
    @endphp

    @foreach ($statData as $key => $conf)
        <a href="{{ route('repository.index', $key) }}" class="stat-card-glass {{ $conf['theme'] }} tilt-hover">
            <div class="stat-glass-icon">{!! $conf['icon'] !!}</div>
            <div class="stat-glass-label">{{ $conf['label'] }}</div>
            <div class="stat-glass-num counter-number" data-target="{{ $stats[$key] ?? 0 }}">{{ $stats[$key] ?? 0 }}</div>
            <div class="stat-glass-footer">
                <span>Total Koleksi Terbit</span>
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </div>
        </a>
    @endforeach
</div>

{{-- ══════════════════════════════════════════════════════════════
     STATUS SESI UPLOAD (REAL-TIME SYNC)
══════════════════════════════════════════════════════════════ --}}
<section class="section-wrapper" id="status-sesi-section">
    <div class="section-header-modern">
        <div>
            <p class="section-eyebrow"><span></span>Kontrol Akses</p>
            <h2 class="section-title">Status Sesi Unggah Dokumen</h2>
        </div>
        <p class="text-xs text-sky-200/70 max-w-sm">
            Status diperbarui secara real-time. Anda dapat langsung mengunggah dokumen saat sesi dibuka.
        </p>
    </div>

    <div class="session-grid">
        @php
            $sessionConfigs = [
                'skripsi' => ['label' => 'Skripsi / Tugas Akhir', 'actor' => 'mahasiswa'],
                'magang' => ['label' => 'Laporan Praktik Magang', 'actor' => 'mahasiswa'],
                'pkm' => ['label' => 'PKM (Kreativitas Mahasiswa/Dosen)', 'actor' => 'dosen'],
                'penelitian' => ['label' => 'Penelitian Dosen', 'actor' => 'dosen'],
            ];
        @endphp

        @foreach ($sessionConfigs as $key => $config)
            @php
                $isOpen = $uploadStatuses[$key] ?? false;
                $uploadUrl = auth()->check()
                    ? route('repository.create', $key)
                    : route('public.upload.create', ['actor' => $config['actor'], 'kategori' => $key]);
            @endphp
            <article class="session-card {{ $isOpen ? 'open' : 'closed' }} reveal" data-kategori="{{ $key }}" data-upload-url="{{ $uploadUrl }}">
                <span class="session-status-badge {{ $isOpen ? 'open' : 'closed' }}">
                    <span class="status-dot {{ $isOpen ? 'pulse' : '' }}"></span>
                    <span class="status-text">{{ $isOpen ? 'Terbuka' : 'Tertutup' }}</span>
                </span>
                <h3>{{ $config['label'] }}</h3>
                <p class="status-desc">{{ $isOpen ? 'Sesi upload sedang aktif. Silakan unggah dokumen Anda sekarang.' : 'Sesi upload belum dibuka oleh admin.' }}</p>
                <div class="session-card-action mt-auto pt-2">
                    <a href="{{ $isOpen ? $uploadUrl : 'javascript:void(0)' }}"
                       class="session-upload-btn {{ $isOpen ? 'is-open' : 'is-closed' }}"
                       style="{{ $isOpen ? '' : 'pointer-events:none;opacity:0.6;' }}">
                        <span>{{ $isOpen ? 'Upload Sekarang' : 'Sesi Ditutup' }}</span>
                        @if ($isOpen)
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>
                            </svg>
                        @endif
                    </a>
                </div>
            </article>
        @endforeach
    </div>
</section>

<div class="home-divider"></div>

{{-- ══════════════════════════════════════════════════════════════
     DOKUMEN TERBARU (INTERACTIVE TABS & QUICK VIEW)
══════════════════════════════════════════════════════════════ --}}
<section class="section-wrapper">
    <div class="section-header-modern">
        <div>
            <p class="section-eyebrow"><span></span>Katalog Terbaru</p>
            <h2 class="section-title">Dokumen Terbit Terbaru</h2>
        </div>
        <a href="{{ route('repository.index') }}" class="home-text-link">
            <span>Buka Semua Katalog ({{ $stats['total'] ?? 0 }})</span>
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
        </a>
    </div>

    {{-- Interactive Tab Filter Buttons --}}
    <div class="tab-nav">
        <button type="button" class="tab-btn active" data-filter="all">Semua Kategori</button>
        <button type="button" class="tab-btn" data-filter="skripsi">Skripsi / TA</button>
        <button type="button" class="tab-btn" data-filter="magang">Laporan Magang</button>
        <button type="button" class="tab-btn" data-filter="pkm">PKM Dosen</button>
        <button type="button" class="tab-btn" data-filter="penelitian">Penelitian Dosen</button>
    </div>

    {{-- Document Cards Grid --}}
    <div class="doc-showcase-grid" id="doc-showcase-container">
        @forelse ($featured as $document)
            <article class="doc-showcase-card" data-category="{{ $document->kategori }}">
                <span class="doc-cat-tag {{ $document->kategori }}">
                    {{ strtoupper($document->kategori) }}
                </span>

                <h3 class="doc-card-title" title="{{ $document->judul }}">
                    {{ $document->judul }}
                </h3>

                <div class="doc-card-meta">
                    <span class="font-semibold text-slate-200">{{ $document->nama }}</span>
                    <span>{{ $document->nim ?: $document->nidn ?: '-' }} &bull; {{ $document->programStudi?->nama ?: 'Prodi' }} ({{ $document->tahun }})</span>
                </div>

                <div class="doc-card-actions">
                    <button type="button"
                            class="btn-quick-view"
                            data-judul="{{ $document->judul }}"
                            data-kategori="{{ strtoupper($document->kategori) }}"
                            data-nama="{{ $document->nama }}"
                            data-identifier="{{ $document->nim ?: $document->nidn ?: '-' }}"
                            data-prodi="{{ $document->programStudi?->nama ?: '-' }}"
                            data-tahun="{{ $document->tahun }}"
                            data-abstrak="{{ $document->abstrak ?: 'Abstrak belum tersedia untuk dokumen ini.' }}"
                            data-url="{{ route('repository.show', $document) }}"
                            onclick="openQuickPreviewFromBtn(this)">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        <span>Quick Preview</span>
                    </button>

                    <a href="{{ route('repository.show', $document) }}" class="btn-doc-link" title="Buka Detail">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                </div>
            </article>
        @empty
            <div class="home-empty col-span-full">
                Belum ada dokumen yang dipublikasikan.
            </div>
        @endforelse
    </div>
</section>

<div class="home-divider"></div>

{{-- ══════════════════════════════════════════════════════════════
     ALUR SISTEM & FITUR UNGGULAN
══════════════════════════════════════════════════════════════ --}}
<section class="section-wrapper">
    <div class="section-header-modern">
        <div>
            <p class="section-eyebrow"><span></span>Struktur Terpadu</p>
            <h2 class="section-title">Alur Pengelolaan Dokumen</h2>
        </div>
    </div>

    <div class="flow-card-grid">
        @foreach ([
            [1, 'Buka Sesi', 'Admin mengaktifkan periode upload.'],
            [2, 'Login Akun', 'Masuk dengan kredensial dari admin.'],
            [3, 'Upload PDF', 'Lengkapi form & unggah berkas PDF.'],
            [4, 'ACC Dosen', 'Dosen pembimbing memeriksa skripsi.'],
            [5, 'Verifikasi Admin', 'Pustakawan memvalidasi dokumen.'],
            [6, 'Katalog Publik', 'Dokumen terindeks di pencarian global.'],
        ] as [$stepNum, $stepTitle, $stepDesc])
            <div class="flow-item-card">
                <div class="flow-num-badge">0{{ $stepNum }}</div>
                <h3 class="text-sm font-bold text-white m-0">{{ $stepTitle }}</h3>
                <p class="text-xs text-sky-100/70 m-0 leading-relaxed">{{ $stepDesc }}</p>
            </div>
        @endforeach
    </div>
</section>

<div class="home-divider"></div>

{{-- ══════════════════════════════════════════════════════════════
     FAQ ACCORDION
══════════════════════════════════════════════════════════════ --}}
<section class="section-wrapper">
    <div class="section-header-modern">
        <div>
            <p class="section-eyebrow"><span></span>Bantuan</p>
            <h2 class="section-title">Pertanyaan yang Sering Diajukan</h2>
        </div>
    </div>

    <div class="faq-grid">
        @foreach ([
            ['Bagaimana cara mendapatkan akun untuk upload dokumen?', 'Akun mahasiswa dan dosen dibuat langsung oleh Admin Repository. Hubungi admin melalui WhatsApp untuk menerima username dan password awal.'],
            ['Kapan sesi upload dokumen dibuka?', 'Sesi upload dibuka dan ditutup oleh admin sesuai kalender akademik. Anda dapat memantau statusnya langsung pada seksi "Status Sesi Upload".'],
            ['Berapa batas ukuran file yang dapat diunggah?', 'Ukuran berkas dokumen PDF maksimal adalah 10 MB. Jika memiliki file pendukung/source code project (ZIP/RAR), batas ukurannya hingga 100 MB.'],
            ['Apakah dokumen langsung tampil di pencarian publik?', 'Untuk dokumen mahasiswa (Skripsi & Magang), dokumen harus disetujui (ACC) oleh Dosen Pembimbing terlebih dahulu sebelum diverifikasi oleh Admin Pustaka.'],
        ] as [$q, $a])
            <div class="faq-item">
                <button class="faq-question" type="button" aria-expanded="false">
                    <span>{{ $q }}</span>
                    <svg class="faq-chevron" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="faq-answer">
                    <p>{{ $a }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ══════════════════════════════════════════════════════════════
     WHATSAPP BANNER
══════════════════════════════════════════════════════════════ --}}
<section class="section-wrapper">
    <div class="contact-banner-home reveal">
        <div class="contact-banner-info">
            <p class="home-eyebrow"><span></span>Pusat Layanan Mahasiswa &amp; Dosen</p>
            <h3>Butuh Bantuan Seputar Repositori?</h3>
            <p>Tim admin siap membantu pertanyaan mengenai pembuatan akun, sesi kompre, format PDF, maupun verifikasi berkas karya ilmiah Anda.</p>
        </div>
        <div>
            <a href="https://wa.me/6285363097108?text=Halo%20Admin%20Repository%20Universitas%20Metamedia,%20saya%20ingin%20bertanya"
               target="_blank"
               rel="noopener noreferrer"
               class="btn-whatsapp">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.105 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
                <span>Hubungi Admin WhatsApp</span>
            </a>
        </div>
    </div>
</section>

{{-- Floating WhatsApp Action Button --}}
<a href="https://wa.me/6285363097108?text=Halo%20Admin%20Repository%20Universitas%20Metamedia,%20saya%20ingin%20bertanya"
   target="_blank"
   rel="noopener noreferrer"
   class="float-wa-btn"
   title="Hubungi Admin WhatsApp (+62 853-6309-7108)">
    <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor">
        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.105 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
    </svg>
    <span>Hubungi WA (+62 853-6309-7108)</span>
</a>

{{-- Back-to-Top Button --}}
<button class="back-to-top" id="back-to-top" aria-label="Kembali ke atas" title="Kembali ke atas">
    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
</button>

{{-- ══════════════════════════════════════════════════════════════
     QUICK PREVIEW MODAL
══════════════════════════════════════════════════════════════ --}}
<div class="preview-modal-backdrop" id="preview-modal" onclick="closeQuickPreview(event)">
    <div class="preview-modal-dialog" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between gap-4 border-b border-white/10 pb-4">
            <div class="space-y-1">
                <span id="modal-kategori-tag" class="doc-cat-tag skripsi">SKRIPSI</span>
                <h3 id="modal-judul" class="text-lg font-bold text-white leading-snug">Judul Dokumen</h3>
            </div>
            <button type="button" onclick="closeQuickPreview()" class="text-slate-400 hover:text-white p-1 rounded-lg hover:bg-white/10 transition">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="grid grid-cols-2 gap-3 text-xs bg-white/5 rounded-xl p-3.5 border border-white/10">
            <div>
                <span class="text-slate-400 block">Penulis / Peneliti:</span>
                <strong id="modal-penulis" class="text-white">Nama Penulis</strong>
            </div>
            <div>
                <span class="text-slate-400 block">NIM / NIDN:</span>
                <span id="modal-identifier" class="text-white font-medium">-</span>
            </div>
            <div>
                <span class="text-slate-400 block">Program Studi:</span>
                <span id="modal-prodi" class="text-white font-medium">-</span>
            </div>
            <div>
                <span class="text-slate-400 block">Tahun Dokumen:</span>
                <span id="modal-tahun" class="text-white font-medium">-</span>
            </div>
        </div>

        <div>
            <span class="text-xs font-bold text-sky-400 uppercase tracking-wider block mb-1.5">Abstrak / Ringkasan</span>
            <p id="modal-abstrak" class="text-xs text-slate-300 leading-relaxed max-h-48 overflow-y-auto bg-black/20 p-3 rounded-xl border border-white/5"></p>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2 border-t border-white/10">
            <button type="button" onclick="closeQuickPreview()" class="rounded-xl border border-white/15 px-4 py-2 text-xs font-semibold text-slate-300 hover:bg-white/10 transition">
                Tutup
            </button>
            <a id="modal-full-link" href="#" class="rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 px-5 py-2 text-xs font-bold text-white shadow-md hover:brightness-110 transition flex items-center gap-1.5">
                <span>Lihat Dokumen Lengkap</span>
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════
     JAVASCRIPT INTERACTION SCRIPTS
══════════════════════════════════════════════════════════════ --}}
<script>
(function () {
    /* ── Library Hero Background Slideshow ─────────────────── */
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slide-dot');
    let currentSlide = 0;
    let slideTimer = null;

    function showSlide(index) {
        if (!slides.length) return;
        slides.forEach((s, i) => {
            s.classList.toggle('active', i === index);
        });
        dots.forEach((d, i) => {
            d.classList.toggle('active', i === index);
        });
        currentSlide = index;
    }

    function nextSlide() {
        const next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }

    if (slides.length > 1) {
        // Auto-cycle every 2.5 seconds
        slideTimer = setInterval(nextSlide, 2500);

        dots.forEach((dot, idx) => {
            dot.addEventListener('click', () => {
                clearInterval(slideTimer);
                showSlide(idx);
                slideTimer = setInterval(nextSlide, 2500);
            });
        });
    }

    /* ── Keyboard shortcut '/' to search ───────────────────── */
    window.addEventListener('keydown', function (e) {
        if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
            e.preventDefault();
            const searchInput = document.getElementById('hero-search-input');
            if (searchInput) {
                searchInput.focus();
                searchInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    /* ── Document Category Filter Tabs ─────────────────────── */
    const tabBtns = document.querySelectorAll('.tab-btn');
    const docCards = document.querySelectorAll('.doc-showcase-card');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            tabBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');
            docCards.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-category') === filter) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    /* ── Real-Time Status Polling ──────────────────────────── */
    const sessionCards = document.querySelectorAll('.session-card[data-kategori]');
    if (sessionCards.length) {
        async function syncLiveStatuses() {
            try {
                const res = await fetch('/api/upload-statuses', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                if (!res.ok) return;
                const statuses = await res.json();

                sessionCards.forEach(card => {
                    const kategori = card.getAttribute('data-kategori');
                    const uploadUrl = card.getAttribute('data-upload-url');
                    const isOpen = Boolean(statuses[kategori]);

                    card.classList.toggle('open', isOpen);
                    card.classList.toggle('closed', !isOpen);

                    const badge = card.querySelector('.session-status-badge');
                    if (badge) {
                        badge.className = 'session-status-badge ' + (isOpen ? 'open' : 'closed');
                        const dot = badge.querySelector('.status-dot');
                        if (dot) dot.className = 'status-dot ' + (isOpen ? 'pulse' : '');
                        const text = badge.querySelector('.status-text');
                        if (text) text.textContent = isOpen ? 'Terbuka' : 'Tertutup';
                    }

                    const desc = card.querySelector('.status-desc');
                    if (desc) {
                        desc.textContent = isOpen
                            ? 'Sesi upload sedang aktif. Silakan unggah dokumen Anda sekarang.'
                            : 'Sesi upload belum dibuka oleh admin.';
                    }

                    const btn = card.querySelector('.session-upload-btn');
                    if (btn) {
                        btn.className = 'session-upload-btn ' + (isOpen ? 'is-open' : 'is-closed');
                        btn.href = isOpen ? uploadUrl : 'javascript:void(0)';
                        btn.style.pointerEvents = isOpen ? 'auto' : 'none';
                        btn.style.opacity = isOpen ? '1' : '0.6';
                        btn.innerHTML = `<span>${isOpen ? 'Upload Sekarang' : 'Sesi Ditutup'}</span>` +
                            (isOpen ? `<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>` : '');
                    }
                });
            } catch (e) {}
        }
        setInterval(syncLiveStatuses, 3000);
    }

    /* ── Animated Number Counters ──────────────────────────── */
    function animateCounters() {
        document.querySelectorAll('.counter-number').forEach(el => {
            const target = parseInt(el.getAttribute('data-target') || el.textContent.replace(/\D/g, ''), 10);
            if (isNaN(target) || target === 0) return;
            const duration = 1200;
            const start = performance.now();
            el.textContent = '0';
            function step(now) {
                const elapsed = now - start;
                const progress = Math.min(elapsed / duration, 1);
                const ease = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.floor(ease * target).toLocaleString('id-ID');
                if (progress < 1) requestAnimationFrame(step);
                else el.textContent = target.toLocaleString('id-ID');
            }
            requestAnimationFrame(step);
        });
    }

    const obs = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                obs.disconnect();
            }
        });
    }, { threshold: 0.2 });

    const statsSec = document.querySelector('.stats-container');
    if (statsSec) obs.observe(statsSec);

    /* ── FAQ Accordion ─────────────────────────────────────── */
    document.querySelectorAll('.faq-item').forEach(item => {
        const btn = item.querySelector('.faq-question');
        if (!btn) return;
        btn.addEventListener('click', () => {
            const isOpen = item.classList.contains('open');
            document.querySelectorAll('.faq-item.open').forEach(o => {
                o.classList.remove('open');
                o.querySelector('.faq-question')?.setAttribute('aria-expanded', 'false');
            });
            if (!isOpen) {
                item.classList.add('open');
                btn.setAttribute('aria-expanded', 'true');
            }
        });
    });

    /* ── Back to Top ───────────────────────────────────────── */
    const btt = document.getElementById('back-to-top');
    if (btt) {
        window.addEventListener('scroll', () => {
            btt.classList.toggle('visible', window.scrollY > 300);
        }, { passive: true });
        btt.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
})();

/* ── Modal Quick View Functions ────────────────────────────── */
function openQuickPreviewFromBtn(btn) {
    if (!btn) return;
    openQuickPreview({
        judul: btn.getAttribute('data-judul') || '',
        kategori: btn.getAttribute('data-kategori') || '',
        nama: btn.getAttribute('data-nama') || '',
        identifier: btn.getAttribute('data-identifier') || '-',
        prodi: btn.getAttribute('data-prodi') || '-',
        tahun: btn.getAttribute('data-tahun') || '-',
        abstrak: btn.getAttribute('data-abstrak') || 'Abstrak belum tersedia.',
        url: btn.getAttribute('data-url') || '#'
    });
}

function openQuickPreview(data) {
    const modal = document.getElementById('preview-modal');
    if (!modal) return;

    document.getElementById('modal-judul').textContent = data.judul;
    document.getElementById('modal-penulis').textContent = data.nama;
    document.getElementById('modal-identifier').textContent = data.identifier;
    document.getElementById('modal-prodi').textContent = data.prodi;
    document.getElementById('modal-tahun').textContent = data.tahun;
    document.getElementById('modal-abstrak').textContent = data.abstrak;
    document.getElementById('modal-full-link').href = data.url;

    const catTag = document.getElementById('modal-kategori-tag');
    catTag.textContent = data.kategori;
    catTag.className = 'doc-cat-tag ' + data.kategori.toLowerCase();

    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeQuickPreview(e) {
    if (e && e.target !== e.currentTarget && e.type === 'click') return;
    const modal = document.getElementById('preview-modal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}
</script>

@endsection
