<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', ' Repository Kampus') - Universitas Metamedia</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800|fraunces:500,600,700&display=swap"
        rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- =====================================================
    LAYOUT STYLES
    Semua gaya untuk navbar, drawer, dropdown, flash message,
    dan komponen global lain disatukan di sini agar file ini
    mandiri (tidak bergantung pada berkas CSS terpisah).
    ===================================================== --}}
    <style>
        :root {
            --ink: #161B2E;
            --ink-soft: #4A5170;
            --navy: #1B2A4A;
            --navy-deep: #101830;
            --parchment: #FBF9F5;
            --parchment-dim: #F1ECE1;
            --line: rgba(22, 27, 46, .09);
            --accent: #B9822F;
            --accent-soft: #E8CE9C;
            --accent-ink: #5C3E12;
            --white: #ffffff;
            --radius-sm: 8px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --shadow-soft: 0 8px 24px -12px rgba(16, 24, 48, .18);
            --shadow-pop: 0 24px 60px -20px rgba(16, 24, 48, .35);
            --font-display: 'Fraunces', Georgia, serif;
            --font-body: 'Instrument Sans', system-ui, sans-serif;
            --ease: cubic-bezier(.22, 1, .36, 1);
        }

        [data-theme="dark"] {
            --ink: #EFEDE6;
            --ink-soft: #B7BBD1;
            --navy: #0E1730;
            --navy-deep: #080D1C;
            --parchment: #0D1120;
            --parchment-dim: #141A2E;
            --line: rgba(239, 237, 230, .10);
            --accent: #E1B15B;
            --accent-soft: #3A2F16;
            --accent-ink: #F3DDA8;
            --white: #151A2C;
            --shadow-soft: 0 8px 28px -12px rgba(0, 0, 0, .55);
            --shadow-pop: 0 24px 70px -18px rgba(0, 0, 0, .7);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: var(--font-body);
            color: var(--ink);
            background: var(--parchment);
            transition: background .35s var(--ease), color .35s var(--ease);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button {
            font-family: inherit;
            cursor: pointer;
        }

        svg {
            display: block;
        }

        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: .001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .001ms !important;
                scroll-behavior: auto !important;
            }
        }

        /* ===================== HEADER ===================== */
        .site-header {
            position: sticky;
            top: 0;
            z-index: 60;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            padding: 14px 28px;
            background: color-mix(in srgb, var(--parchment) 88%, transparent);
            backdrop-filter: saturate(160%) blur(14px);
            -webkit-backdrop-filter: saturate(160%) blur(14px);
            border-bottom: 1px solid transparent;
            transition: border-color .3s var(--ease), box-shadow .3s var(--ease), background .35s var(--ease);
        }

        .site-header.scrolled {
            border-bottom-color: var(--line);
            box-shadow: var(--shadow-soft);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .brand img {
            width: 56px;
            height: 56px;
            object-fit: contain;
            border-radius: 0;
        }

        .brand-logo-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .brand-logo {
            display: block;
            filter: drop-shadow(0 8px 16px rgba(16, 24, 48, .16));
        }

        .brand-logo.enbi-logo {
            width: 60px;
            height: 60px;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.15;
        }

        .brand-title {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 16.5px;
            letter-spacing: .1px;
        }

        .brand-subtitle {
            font-size: 11.5px;
            color: var(--ink-soft);
            font-weight: 500;
            letter-spacing: .2px;
        }

        /* Desktop nav */
        .main-nav {
            display: flex;
            align-items: center;
            gap: 4px;
            flex: 1;
            padding-left: 12px;
        }

        .nav-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 9px 14px;
            font-size: 14px;
            font-weight: 600;
            color: var(--ink-soft);
            border-radius: 999px;
            transition: color .25s var(--ease), background .25s var(--ease);
            white-space: nowrap;
        }

        .nav-link svg {
            transition: transform .3s var(--ease);
        }

        .nav-link:hover {
            color: var(--ink);
            background: var(--parchment-dim);
        }

        .nav-link:hover svg {
            transform: translateY(-1px);
        }

        .nav-link.active {
            color: var(--navy);
        }

        [data-theme="dark"] .nav-link.active {
            color: var(--accent-ink);
        }

        .nav-link.active::after {
            content: "";
            position: absolute;
            left: 14px;
            right: 14px;
            bottom: 2px;
            height: 2px;
            border-radius: 2px;
            background: var(--accent);
        }

        /* Right side of header */
        .header-end {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        /* Search */
        .search-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-toggle {
            width: 38px;
            height: 38px;
            border-radius: 999px;
            border: 1px solid transparent;
            background: transparent;
            color: var(--ink-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .25s var(--ease), color .25s var(--ease);
        }

        .search-toggle:hover {
            background: var(--parchment-dim);
            color: var(--ink);
        }

        .search-form {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%) scaleX(0);
            transform-origin: right center;
            display: flex;
            align-items: center;
            gap: 6px;
            background: var(--white);
            border: 1px solid var(--line);
            box-shadow: var(--shadow-soft);
            border-radius: 999px;
            padding: 5px 6px 5px 14px;
            width: 280px;
            opacity: 0;
            pointer-events: none;
            transition: transform .3s var(--ease), opacity .25s var(--ease);
        }

        .search-form.open {
            transform: translateY(-50%) scaleX(1);
            opacity: 1;
            pointer-events: auto;
        }

        .search-form input {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            font-size: 13.5px;
            color: var(--ink);
            font-family: inherit;
        }

        .search-form button {
            width: 30px;
            height: 30px;
            border-radius: 999px;
            border: none;
            background: var(--navy);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background .2s var(--ease);
        }

        .search-form button:hover {
            background: var(--accent);
        }

        /* Theme toggle */
        .theme-toggle {
            width: 38px;
            height: 38px;
            border-radius: 999px;
            border: 1px solid var(--line);
            background: var(--white);
            color: var(--ink-soft);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .25s var(--ease), transform .4s var(--ease);
        }

        .theme-toggle:hover {
            color: var(--accent);
        }

        .theme-toggle svg {
            transition: transform .5s var(--ease), opacity .3s var(--ease);
        }

        .theme-toggle .icon-sun {
            display: none;
        }

        [data-theme="dark"] .theme-toggle .icon-moon {
            display: none;
        }

        [data-theme="dark"] .theme-toggle .icon-sun {
            display: block;
        }

        /* Login button */
        .nav-login-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            background: var(--navy);
            border-radius: 999px;
            transition: background .25s var(--ease), transform .2s var(--ease), box-shadow .25s var(--ease);
        }

        .nav-login-btn:hover {
            background: var(--accent);
            transform: translateY(-1px);
            box-shadow: var(--shadow-soft);
        }

        /* Profile dropdown */
        .profile-dropdown-wrapper {
            position: relative;
        }

        .profile-nav-btn {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 6px 12px 6px 6px;
            background: var(--white);
            border: 1px solid var(--line);
            border-radius: 999px;
            transition: border-color .25s var(--ease), box-shadow .25s var(--ease);
        }

        .profile-nav-btn:hover {
            border-color: var(--accent-soft);
            box-shadow: var(--shadow-soft);
        }

        .avatar-circle-sm {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--navy), var(--navy-deep));
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .3px;
            flex-shrink: 0;
        }

        .avatar-circle-md {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--navy), var(--navy-deep));
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: .3px;
            flex-shrink: 0;
        }

        .profile-nav-name {
            font-size: 13.5px;
            font-weight: 600;
            max-width: 110px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dropdown-chevron {
            transition: transform .3s var(--ease);
            color: var(--ink-soft);
        }

        .profile-dropdown-wrapper.open .dropdown-chevron {
            transform: rotate(180deg);
        }

        .profile-dropdown-menu {
            position: absolute;
            right: 0;
            top: calc(100% + 10px);
            width: 288px;
            background: var(--white);
            border: 1px solid var(--line);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-pop);
            padding: 8px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px) scale(.97);
            transform-origin: top right;
            transition: opacity .22s var(--ease), transform .22s var(--ease), visibility .22s;
            z-index: 70;
        }

        .profile-dropdown-wrapper.open .profile-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        .dropdown-identity {
            display: flex;
            gap: 12px;
            padding: 12px 10px;
            align-items: flex-start;
        }

        .dropdown-identity-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
            min-width: 0;
        }

        .dropdown-identity-info strong {
            font-size: 14px;
        }

        .dropdown-email,
        .dropdown-uid {
            color: var(--ink-soft);
            font-size: 11.5px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dropdown-role-badge {
            align-self: flex-start;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .3px;
            text-transform: uppercase;
            padding: 2px 9px;
            border-radius: 999px;
            margin: 2px 0;
        }

        .role-admin {
            background: #EFE1C4;
            color: #7A5A12;
        }

        .role-dosen {
            background: #D9EEEA;
            color: #1D6E60;
        }

        .role-mahasiswa {
            background: #DEE7FB;
            color: #2A4F9E;
        }

        [data-theme="dark"] .role-admin {
            background: #3A2F16;
            color: #F3DDA8;
        }

        [data-theme="dark"] .role-dosen {
            background: #123330;
            color: #8FE0CE;
        }

        [data-theme="dark"] .role-mahasiswa {
            background: #1A2544;
            color: #AFC4F3;
        }

        .dropdown-section {
            padding: 6px 4px;
            border-top: 1px solid var(--line);
        }

        .dropdown-section-label {
            display: block;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .5px;
            text-transform: uppercase;
            color: var(--ink-soft);
            padding: 8px 10px 4px;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 9px 10px;
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            font-weight: 600;
            color: var(--ink);
            transition: background .2s var(--ease), padding-left .2s var(--ease);
        }

        .dropdown-item:hover {
            background: var(--parchment-dim);
            padding-left: 14px;
        }

        .dropdown-item-icon {
            color: var(--ink-soft);
            display: flex;
            flex-shrink: 0;
        }

        .dropdown-item:hover .dropdown-item-icon {
            color: var(--accent);
        }

        .dropdown-footer {
            border-top: 1px solid var(--line);
            padding: 6px 4px 2px;
        }

        .dropdown-logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 9px 10px;
            border: none;
            background: transparent;
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            font-weight: 600;
            color: #B5432F;
            transition: background .2s var(--ease);
        }

        .dropdown-logout-btn:hover {
            background: rgba(181, 67, 47, .09);
        }

        /* Hamburger (mobile only) */
        .hamburger {
            display: none;
            width: 38px;
            height: 38px;
            border: none;
            background: transparent;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .hamburger span {
            width: 20px;
            height: 2px;
            border-radius: 2px;
            background: var(--ink);
            transition: transform .3s var(--ease), opacity .2s var(--ease);
        }

        .hamburger.open span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .hamburger.open span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.open span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        /* ===================== MOBILE DRAWER ===================== */
        .mobile-overlay {
            position: fixed;
            inset: 0;
            z-index: 80;
            background: rgba(10, 13, 25, .5);
            backdrop-filter: blur(2px);
            opacity: 0;
            visibility: hidden;
            transition: opacity .3s var(--ease), visibility .3s;
        }

        .mobile-overlay.open {
            opacity: 1;
            visibility: visible;
        }

        .mobile-drawer {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 90;
            width: min(340px, 86vw);
            background: var(--parchment);
            border-left: 1px solid var(--line);
            box-shadow: var(--shadow-pop);
            transform: translateX(100%);
            transition: transform .35s var(--ease);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .mobile-drawer.open {
            transform: translateX(0);
        }

        .drawer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 18px 14px;
            border-bottom: 1px solid var(--line);
        }

        .drawer-close {
            width: 34px;
            height: 34px;
            border: none;
            background: var(--parchment-dim);
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ink-soft);
        }

        .drawer-close:hover {
            color: var(--ink);
        }

        .drawer-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 18px;
            border-bottom: 1px solid var(--line);
        }

        .drawer-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            flex-shrink: 0;
            background: linear-gradient(135deg, var(--navy), var(--navy-deep));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .drawer-profile strong {
            display: block;
            font-size: 14px;
        }

        .drawer-role {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .3px;
            padding: 1px 8px;
            border-radius: 999px;
            display: inline-block;
            margin-top: 3px;
        }

        .drawer-nav {
            padding: 10px 12px 16px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .drawer-section-label {
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--ink-soft);
            margin: 14px 10px 4px;
        }

        .drawer-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 12px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 600;
            color: var(--ink);
            transition: background .2s var(--ease), padding-left .2s var(--ease);
        }

        .drawer-link:hover,
        .drawer-link.active {
            background: var(--parchment-dim);
            padding-left: 16px;
        }

        .drawer-link.active {
            color: var(--navy);
        }

        [data-theme="dark"] .drawer-link.active {
            color: var(--accent-ink);
        }

        .drawer-footer {
            margin-top: auto;
            padding: 14px 18px 20px;
            border-top: 1px solid var(--line);
        }

        .drawer-logout-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            padding: 11px;
            border: 1px solid rgba(181, 67, 47, .3);
            background: transparent;
            border-radius: 999px;
            font-size: 13.5px;
            font-weight: 700;
            color: #B5432F;
            transition: background .2s var(--ease);
        }

        .drawer-logout-btn:hover {
            background: rgba(181, 67, 47, .09);
        }

        /* ===================== FLASH TOAST ===================== */
        .flash-message {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            max-width: 920px;
            margin: 18px auto 0;
            padding: 14px 18px;
            background: var(--white);
            border: 1px solid var(--accent-soft);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-soft);
            font-size: 13.5px;
            font-weight: 600;
            animation: toast-in .4s var(--ease);
            overflow: hidden;
        }

        .flash-message::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--accent);
        }

        .flash-message>span {
            padding-left: 6px;
        }

        .flash-progress {
            position: absolute;
            left: 0;
            bottom: 0;
            height: 2px;
            background: var(--accent);
            animation: toast-progress 6s linear forwards;
        }

        .flash-close {
            border: none;
            background: transparent;
            color: var(--ink-soft);
            width: 26px;
            height: 26px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .flash-close:hover {
            background: var(--parchment-dim);
            color: var(--ink);
        }

        @keyframes toast-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes toast-progress {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }

        .btn.primary {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            background: linear-gradient(135deg, #dff8ff, #72d9ff 50%, #0b8fe8);
            color: #00111d;
            border: 1px solid rgba(255,255,255,.3);
            box-shadow: 0 8px 22px rgba(5,150,216,.28);
            transition: transform .2s var(--ease), box-shadow .2s var(--ease), filter .2s var(--ease);
        }

        .btn.primary:hover {
            filter: brightness(1.06) saturate(1.1);
            transform: translateY(-2px);
            box-shadow: 0 16px 36px rgba(5,150,216,.38);
        }

        /* ===================== MAIN / FOOTER ===================== */
        main {
            min-height: 60vh;
            animation: content-in .45s var(--ease);
        }

        @keyframes content-in {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .site-footer {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            text-align: center;
            padding: 36px 20px 32px;
            margin-top: 48px;
            border-top: 1px solid var(--line);
            color: var(--ink-soft);
            font-size: 13px;
        }

        .site-footer strong {
            font-family: var(--font-display);
            font-size: 15px;
            color: var(--ink);
        }

        /* ===================== BACK TO TOP ===================== */
        .back-to-top {
            position: fixed;
            right: 22px;
            bottom: 22px;
            z-index: 50;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: none;
            background: var(--navy);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-soft);
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: opacity .25s var(--ease), transform .25s var(--ease), background .25s var(--ease), visibility .25s;
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .back-to-top:hover {
            background: var(--accent);
        }

        /* ===================== VIEWER MODAL ===================== */
        .viewer-modal {
            position: fixed;
            inset: 0;
            z-index: 100;
        }

        .viewer-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(8, 11, 22, .65);
            backdrop-filter: blur(3px);
        }

        .viewer-modal-content {
            position: relative;
            max-width: 1000px;
            margin: 4vh auto;
            height: 92vh;
            background: var(--white);
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-pop);
            animation: toast-in .3s var(--ease);
        }

        .viewer-modal-close,
        .viewer-modal-fullscreen-toggle {
            position: absolute;
            top: 10px;
            z-index: 2;
            width: 34px;
            height: 34px;
            border: none;
            border-radius: 999px;
            background: rgba(0, 0, 0, .55);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: background .2s var(--ease);
        }

        .viewer-modal-close {
            right: 10px;
        }

        .viewer-modal-fullscreen-toggle {
            right: 52px;
        }

        .viewer-modal-close:hover,
        .viewer-modal-fullscreen-toggle:hover {
            background: rgba(0, 0, 0, .8);
        }

        .viewer-modal-body,
        .viewer-frame {
            width: 100%;
            height: 100%;
        }

        .viewer-frame iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .viewer-watermark {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        /* ===================== RESPONSIVE ===================== */
        @media (max-width: 980px) {
            .main-nav {
                display: none;
            }

            .hamburger {
                display: flex;
            }

            .profile-nav-name {
                display: none;
            }

            .site-header {
                padding: 12px 16px;
            }
        }

        @media (max-width: 520px) {
            .search-form {
                width: 220px;
            }
        }

        /* ===================== PUBLIC NAVBAR MOBILE POLISH ===================== */
        @media (max-width: 980px) {
            .site-header {
                gap: 10px !important;
                min-height: 64px !important;
                padding: 10px 12px !important;
                background: rgba(9, 45, 71, 0.96) !important;
                border-bottom: 1px solid rgba(255, 255, 255, 0.12) !important;
            }

            .brand {
                min-width: 0 !important;
                gap: 0.65rem !important;
            }

            .brand-logo-group {
                gap: 0.45rem !important;
            }

            .brand img {
                width: 46px !important;
                height: 46px !important;
                flex-shrink: 0 !important;
            }

            .brand-logo.enbi-logo {
                width: 48px !important;
                height: 48px !important;
            }

            .brand-text {
                min-width: 0 !important;
            }

            .brand-title {
                max-width: 42vw !important;
                overflow: hidden !important;
                font-size: 0.95rem !important;
                line-height: 1.15 !important;
                color: #ffffff !important;
                text-overflow: ellipsis !important;
                white-space: nowrap !important;
            }

            .brand-subtitle {
                max-width: 42vw !important;
                overflow: hidden !important;
                font-size: 0.66rem !important;
                color: #72d9ff !important;
                text-overflow: ellipsis !important;
                white-space: nowrap !important;
            }

            .main-nav {
                display: none !important;
            }

            .header-end {
                gap: 0.35rem !important;
                flex-shrink: 0 !important;
            }

            .search-toggle,
            .theme-toggle,
            .hamburger,
            .profile-nav-btn {
                width: 40px !important;
                height: 40px !important;
                min-width: 40px !important;
                min-height: 40px !important;
                border: 1px solid rgba(255, 255, 255, 0.18) !important;
                border-radius: 8px !important;
                color: #ffffff !important;
                background: rgba(255, 255, 255, 0.11) !important;
                box-shadow: none !important;
            }

            .search-toggle:hover,
            .theme-toggle:hover,
            .profile-nav-btn:hover {
                background: rgba(255, 255, 255, 0.18) !important;
            }

            .hamburger {
                display: inline-flex !important;
                padding: 0 !important;
            }

            .hamburger span {
                background: #ffffff !important;
            }

            .profile-nav-btn {
                padding: 0 !important;
                gap: 0 !important;
                justify-content: center !important;
            }

            .profile-nav-btn .avatar-circle-sm {
                width: 28px !important;
                height: 28px !important;
                font-size: 0.68rem !important;
            }

            .profile-nav-name,
            .profile-nav-btn .dropdown-chevron {
                display: none !important;
            }

            .profile-dropdown-menu {
                position: fixed !important;
                top: 66px !important;
                right: 10px !important;
                left: 10px !important;
                width: auto !important;
                max-width: none !important;
                transform-origin: top right !important;
            }

            .search-form {
                position: fixed !important;
                top: 72px !important;
                right: 10px !important;
                left: 10px !important;
                width: auto !important;
                transform: translateY(-8px) scale(0.98) !important;
                border-radius: 8px !important;
                padding: 0.55rem !important;
                z-index: 95 !important;
            }

            .search-form.open {
                transform: translateY(0) scale(1) !important;
            }

            .mobile-overlay {
                z-index: 110 !important;
                background: rgba(3, 18, 31, 0.62) !important;
            }

            .mobile-drawer {
                z-index: 120 !important;
                width: min(88vw, 336px) !important;
                height: 100dvh !important;
                max-height: 100dvh !important;
                color: var(--ink) !important;
                background: var(--parchment) !important;
            }

            .mobile-drawer .brand-title,
            .mobile-drawer .drawer-profile strong,
            .mobile-drawer .drawer-link {
                color: var(--ink) !important;
            }

            .mobile-drawer .brand-subtitle,
            .mobile-drawer .drawer-section-label {
                color: var(--ink-soft) !important;
            }

            .mobile-drawer .drawer-link svg {
                color: currentColor !important;
                opacity: 0.82 !important;
            }

            .mobile-drawer .drawer-link:hover,
            .mobile-drawer .drawer-link.active {
                color: var(--navy) !important;
                background: var(--parchment-dim) !important;
                border-left-color: var(--accent) !important;
            }

            [data-theme="dark"] .mobile-drawer .drawer-link:hover,
            [data-theme="dark"] .mobile-drawer .drawer-link.active {
                color: var(--accent-ink) !important;
            }

            .drawer-header {
                padding: 14px 14px 12px !important;
            }

            .drawer-header .brand-title {
                max-width: 190px !important;
                color: var(--ink) !important;
            }

            .drawer-header .brand-subtitle {
                color: var(--ink-soft) !important;
            }

            .drawer-close {
                border-radius: 8px !important;
            }

            .drawer-link {
                min-height: 44px !important;
                border-radius: 8px !important;
            }
        }

        @media (max-width: 520px) {
            .site-header {
                min-height: 60px !important;
                padding: 9px 10px !important;
            }

            .brand img {
                width: 40px !important;
                height: 40px !important;
            }

            .brand-logo.enbi-logo {
                width: 42px !important;
                height: 42px !important;
            }

            .brand-title {
                max-width: 34vw !important;
                font-size: 0.88rem !important;
            }

            .brand-subtitle {
                max-width: 34vw !important;
                font-size: 0.6rem !important;
            }

            .search-toggle,
            .theme-toggle,
            .hamburger,
            .profile-nav-btn {
                width: 38px !important;
                height: 38px !important;
                min-width: 38px !important;
                min-height: 38px !important;
            }

            .search-form,
            .profile-dropdown-menu {
                top: 62px !important;
            }
        }

        @media (max-width: 380px) {
            .brand-title {
                max-width: 30vw !important;
            }

            .brand-subtitle {
                display: none !important;
            }

            .header-end {
                gap: 0.25rem !important;
            }

            .search-toggle,
            .theme-toggle,
            .hamburger,
            .profile-nav-btn {
                width: 36px !important;
                height: 36px !important;
                min-width: 36px !important;
                min-height: 36px !important;
            }
        }
    </style>
</head>

<body>

    {{-- ===================== NAVBAR ===================== --}}
    <header class="site-header" id="site-header">
        {{-- Brand --}}
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-logo-group">
                <img src="{{ asset('assets/metamedia.png') }}" alt="Logo Universitas Metamedia" class="brand-logo">
                <img src="{{ asset('assets/enbi1.png') }}" alt="Logo ENBI" class="brand-logo enbi-logo">
            </span>
            <div class="brand-text">
                <span class="brand-title">Universitas Metamedia</span>
                <span class="brand-subtitle">Sistem Repository Digital</span>
            </div>
        </a>

        {{-- Desktop Navigation --}}
        <nav class="main-nav" id="main-nav" aria-label="Menu Utama">
            <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                <span>Beranda</span>
            </a>
            <a href="{{ route('repository.index') }}"
                class="nav-link {{ request()->routeIs('repository.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                </svg>
                <span>Repository</span>
            </a>
            <a href="{{ route('guides.index') }}" class="nav-link {{ request()->routeIs('guides.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                    <line x1="12" y1="17" x2="12.01" y2="17" />
                </svg>
                <span>Panduan</span>
            </a>
            @auth
                @php $currentUser = auth()->user(); @endphp
                @if ($currentUser->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    @if (request()->is('admin/*') || request()->routeIs('admin.*') || request()->routeIs('reports.*'))
                        <a href="{{ route('admin.users.pending') }}"
                            class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Verifikasi Akun</a>
                        <a href="{{ route('admin.documents.pending') }}"
                            class="nav-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">Verifikasi Upload</a>
                        <a href="{{ route('reports.index') }}"
                            class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">Laporan</a>
                    @endif
                @elseif ($currentUser->role === 'dosen')
                    <a href="{{ route('dosen.dashboard') }}"
                        class="nav-link {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                @elseif ($currentUser->role === 'mahasiswa')
                    <a href="{{ route('mahasiswa.dashboard') }}"
                        class="nav-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                @endif
            @endauth
        </nav>

        {{-- Right: Search, Theme, Profile/Login + Hamburger --}}
        <div class="header-end">

            {{-- Quick Search --}}
            <div class="search-box" id="search-box">
                <button type="button" class="search-toggle" id="search-toggle" aria-label="Buka pencarian"
                    aria-expanded="false">
                    <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                </button>
                <form action="{{ route('repository.index') }}" method="GET" class="search-form" id="search-form">
                    <input type="text" name="q" placeholder="Cari judul, penulis, kata kunci..." autocomplete="off"
                        value="{{ request('q') }}">
                    <button type="submit" aria-label="Cari">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                            stroke-width="2.4">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </button>
                </form>
            </div>

            {{-- Theme Toggle --}}
            <button type="button" class="theme-toggle" id="theme-toggle" aria-label="Ubah tema tampilan">
                <svg class="icon-moon" viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                </svg>
                <svg class="icon-sun" viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <circle cx="12" cy="12" r="4" />
                    <line x1="12" y1="2" x2="12" y2="4" />
                    <line x1="12" y1="20" x2="12" y2="22" />
                    <line x1="4.2" y1="4.2" x2="5.6" y2="5.6" />
                    <line x1="18.4" y1="18.4" x2="19.8" y2="19.8" />
                    <line x1="2" y1="12" x2="4" y2="12" />
                    <line x1="20" y1="12" x2="22" y2="12" />
                    <line x1="4.2" y1="19.8" x2="5.6" y2="18.4" />
                    <line x1="18.4" y1="5.6" x2="19.8" y2="4.2" />
                </svg>
            </button>

            @auth
                {{-- Profile Dropdown --}}
                <div class="profile-dropdown-wrapper" id="profile-dropdown-wrapper">
                    <button type="button" class="profile-nav-btn" id="profile-dropdown-toggle" aria-expanded="false"
                        aria-haspopup="true" aria-label="Menu profil akun">
                        <span class="avatar-circle-sm">
                            {{ strtoupper(substr($currentUser->name, 0, 2)) }}
                        </span>
                        <span
                            class="profile-nav-name">{{ \Illuminate\Support\Str::words($currentUser->name, 2, '') }}</span>
                        <svg class="dropdown-chevron" viewBox="0 0 24 24" width="13" height="13" fill="none"
                            stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path d="M6 9l6 6 6-6" />
                        </svg>
                    </button>

                    <div class="profile-dropdown-menu" id="profile-dropdown-menu" role="menu">
                        {{-- User Identity --}}
                        <div class="dropdown-identity">
                            <div class="avatar-circle-md">
                                {{ strtoupper(substr($currentUser->name, 0, 2)) }}
                            </div>
                            <div class="dropdown-identity-info">
                                <strong>{{ $currentUser->name }}</strong>
                                <span
                                    class="dropdown-role-badge role-{{ $currentUser->role }}">{{ ucfirst($currentUser->role) }}</span>
                                <small class="dropdown-email">{{ $currentUser->email }}</small>
                                @if ($currentUser->nim)
                                    <small class="dropdown-uid">NIM: {{ $currentUser->nim }}</small>
                                @elseif ($currentUser->nidn)
                                    <small class="dropdown-uid">NIDN: {{ $currentUser->nidn }}</small>
                                @endif
                            </div>
                        </div>

                        <div class="dropdown-section">
                            <span class="dropdown-section-label">Akun</span>
                            <a href="{{ route('profile.show') }}" class="dropdown-item" role="menuitem">
                                <span class="dropdown-item-icon">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                </span>
                                <span>Profil & Pengaturan</span>
                            </a>
                        </div>

                        <div class="dropdown-section">
                            <span class="dropdown-section-label">Navigasi</span>
                            @if ($currentUser->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-item" role="menuitem">
                                    <span class="dropdown-item-icon"><svg viewBox="0 0 24 24" width="16" height="16" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="7" height="7" />
                                            <rect x="14" y="3" width="7" height="7" />
                                            <rect x="14" y="14" width="7" height="7" />
                                            <rect x="3" y="14" width="7" height="7" />
                                        </svg></span>
                                    <span>Dashboard Admin</span>
                                </a>
                                <a href="{{ route('admin.users.pending') }}" class="dropdown-item" role="menuitem">
                                    <span class="dropdown-item-icon"><svg viewBox="0 0 24 24" width="16" height="16" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                            <circle cx="8.5" cy="7" r="4" />
                                            <line x1="20" y1="8" x2="20" y2="14" />
                                            <line x1="23" y1="11" x2="17" y2="11" />
                                        </svg></span>
                                    <span>Verifikasi Akun</span>
                                </a>
                                <a href="{{ route('admin.documents.pending') }}" class="dropdown-item" role="menuitem">
                                    <span class="dropdown-item-icon"><svg viewBox="0 0 24 24" width="16" height="16" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                            <polyline points="14 2 14 8 20 8" />
                                        </svg></span>
                                    <span>Verifikasi Upload</span>
                                </a>
                                <a href="{{ route('reports.index') }}" class="dropdown-item" role="menuitem">
                                    <span class="dropdown-item-icon"><svg viewBox="0 0 24 24" width="16" height="16" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <line x1="18" y1="20" x2="18" y2="10" />
                                            <line x1="12" y1="20" x2="12" y2="4" />
                                            <line x1="6" y1="20" x2="6" y2="14" />
                                        </svg></span>
                                    <span>Laporan & Statistik</span>
                                </a>
                            @elseif ($currentUser->role === 'dosen')
                                <a href="{{ route('dosen.dashboard') }}" class="dropdown-item" role="menuitem">
                                    <span class="dropdown-item-icon"><svg viewBox="0 0 24 24" width="16" height="16" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="7" height="7" />
                                            <rect x="14" y="3" width="7" height="7" />
                                            <rect x="14" y="14" width="7" height="7" />
                                            <rect x="3" y="14" width="7" height="7" />
                                        </svg></span>
                                    <span>Dashboard Dosen</span>
                                </a>
                                <a href="{{ route('dosen.approvals.index') }}" class="dropdown-item" role="menuitem">
                                    <span class="dropdown-item-icon"><svg viewBox="0 0 24 24" width="16" height="16" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <polyline points="9 11 12 14 22 4" />
                                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                                        </svg></span>
                                    <span>ACC Dokumen Bimbingan</span>
                                </a>
                            @elseif ($currentUser->role === 'mahasiswa')
                                <a href="{{ route('mahasiswa.dashboard') }}" class="dropdown-item" role="menuitem">
                                    <span class="dropdown-item-icon"><svg viewBox="0 0 24 24" width="16" height="16" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="3" width="7" height="7" />
                                            <rect x="14" y="3" width="7" height="7" />
                                            <rect x="14" y="14" width="7" height="7" />
                                            <rect x="3" y="14" width="7" height="7" />
                                        </svg></span>
                                    <span>Dashboard Mahasiswa</span>
                                </a>
                            @endif
                        </div>

                        <div class="dropdown-footer">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-logout-btn" role="menuitem">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                        <polyline points="16 17 21 12 16 7" />
                                        <line x1="21" y1="12" x2="9" y2="12" />
                                    </svg>
                                    <span>Keluar dari Akun</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="nav-login-btn">
                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                        <polyline points="10 17 15 12 10 7" />
                        <line x1="15" y1="12" x2="3" y2="12" />
                    </svg>
                    <span>Masuk</span>
                </a>
            @endauth

            {{-- Hamburger --}}
            <button type="button" class="hamburger" id="hamburger" aria-label="Buka menu" aria-expanded="false"
                aria-controls="mobile-drawer">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    {{-- Mobile Drawer Overlay --}}
    <div class="mobile-overlay" id="mobile-overlay" aria-hidden="true"></div>

    {{-- Mobile Drawer Panel --}}
    <aside class="mobile-drawer" id="mobile-drawer" aria-label="Menu Mobile" aria-hidden="true">
        <div class="drawer-header">
            <a href="{{ route('home') }}" class="brand">
                <span class="brand-logo-group">
                    <img src="{{ asset('assets/metamedia.png') }}" alt="Logo Universitas Metamedia" class="brand-logo">
                    <img src="{{ asset('assets/enbi1.png') }}" alt="Logo ENBI" class="brand-logo enbi-logo">
                </span>
                <div class="brand-text">
                    <span class="brand-title">Metamedia</span>
                    <span class="brand-subtitle">Repository Digital</span>
                </div>
            </a>
            <button type="button" class="drawer-close" id="drawer-close" aria-label="Tutup menu">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>

        {{-- Search di mobile --}}
        <form action="{{ route('repository.index') }}" method="GET"
            style="display:flex; gap:8px; padding:14px 18px; border-bottom:1px solid var(--line);">
            <input type="text" name="q" placeholder="Cari dokumen..." value="{{ request('q') }}"
                style="flex:1; padding:10px 14px; border-radius:999px; border:1px solid var(--line); background:var(--white); color:var(--ink); font-size:13.5px; font-family:inherit; outline:none;">
            <button type="submit"
                style="width:40px; height:40px; border:none; border-radius:999px; background:var(--navy); color:#fff; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="7" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
            </button>
        </form>

        @auth
            <div class="drawer-profile">
                <div class="drawer-avatar">{{ strtoupper(substr($currentUser->name, 0, 2)) }}</div>
                <div>
                    <strong>{{ $currentUser->name }}</strong>
                    <span class="drawer-role role-{{ $currentUser->role }}">{{ ucfirst($currentUser->role) }}</span>
                </div>
            </div>
        @endauth

        <nav class="drawer-nav">
            <p class="drawer-section-label">Menu Utama</p>
            <a href="{{ route('home') }}" class="drawer-link {{ request()->routeIs('home') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                Beranda
            </a>
            <a href="{{ route('repository.index') }}"
                class="drawer-link {{ request()->routeIs('repository.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                </svg>
                Repository
            </a>
            <a href="{{ route('guides.index') }}"
                class="drawer-link {{ request()->routeIs('guides.*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                    <line x1="12" y1="17" x2="12.01" y2="17" />
                </svg>
                Panduan
            </a>

            @auth
                @php $mu = auth()->user(); @endphp
                <p class="drawer-section-label">{{ ucfirst($mu->role) }}</p>
                @if ($mu->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="drawer-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                        </svg>
                        Dashboard Admin
                    </a>
                    <a href="{{ route('admin.users.pending') }}"
                        class="drawer-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="8.5" cy="7" r="4" />
                            <line x1="20" y1="8" x2="20" y2="14" />
                            <line x1="23" y1="11" x2="17" y2="11" />
                        </svg>
                        Verifikasi Akun
                    </a>
                    <a href="{{ route('admin.documents.pending') }}"
                        class="drawer-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                        </svg>
                        Verifikasi Upload
                    </a>
                    <a href="{{ route('reports.index') }}"
                        class="drawer-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10" />
                            <line x1="12" y1="20" x2="12" y2="4" />
                            <line x1="6" y1="20" x2="6" y2="14" />
                        </svg>
                        Laporan & Statistik
                    </a>
                @elseif ($mu->role === 'dosen')
                    <a href="{{ route('dosen.dashboard') }}"
                        class="drawer-link {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                        </svg>
                        Dashboard Dosen
                    </a>
                    <a href="{{ route('dosen.approvals.index') }}"
                        class="drawer-link {{ request()->routeIs('dosen.approvals.*') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 11 12 14 22 4" />
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                        </svg>
                        ACC Dokumen Bimbingan
                    </a>
                @elseif ($mu->role === 'mahasiswa')
                    <a href="{{ route('mahasiswa.dashboard') }}"
                        class="drawer-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                        </svg>
                        Dashboard Mahasiswa
                    </a>
                @endif

                <p class="drawer-section-label">Akun</p>
                <a href="{{ route('profile.show') }}"
                    class="drawer-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                    Profil & Pengaturan
                </a>
            @else
                <p class="drawer-section-label">Masuk</p>
                <a href="{{ route('login') }}" class="drawer-link">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                        <polyline points="10 17 15 12 10 7" />
                        <line x1="15" y1="12" x2="3" y2="12" />
                    </svg>
                    Login Akun
                </a>
            @endauth
        </nav>

        @auth
            <div class="drawer-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="drawer-logout-btn">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        Keluar dari Akun
                    </button>
                </form>
            </div>
        @endauth
    </aside>

    {{-- ===================== FLASH ===================== --}}
    @if (session('status'))
        <div class="flash-message" id="flash-message">
            <span>{{ session('status') }}</span>
            <div style="display:flex; align-items:center; gap:10px;">
                @if (session('whatsapp_notification_url'))
                    <a class="btn primary" href="{{ session('whatsapp_notification_url') }}" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor">
                            <path
                                d="M17.6 6.32A8.85 8.85 0 0 0 12.03 4a8.94 8.94 0 0 0-7.75 13.4L3 21l3.72-1.24a8.9 8.9 0 0 0 4.29 1.1 8.94 8.94 0 0 0 8.94-8.94 8.86 8.86 0 0 0-2.35-6.6z" />
                        </svg>
                        Kirim WhatsApp Admin
                    </a>
                @endif
                <button type="button" class="flash-close" id="flash-close" aria-label="Tutup notifikasi">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.4">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>
            <span class="flash-progress" id="flash-progress"></span>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    {{-- Global viewer modal --}}
    <div id="viewer-modal" class="viewer-modal" aria-hidden="true" style="display:none;">
        <div class="viewer-modal-backdrop"></div>
        <div class="viewer-modal-content">
            <button class="viewer-modal-close" aria-label="Tutup">×</button>
            <button class="viewer-modal-fullscreen-toggle" aria-label="Fullscreen">⤢</button>
            <div class="viewer-modal-body">
                <div class="viewer-frame">
                    <iframe src="" title="Preview dokumen"></iframe>
                    <div class="viewer-watermark" style="display:none"></div>
                </div>
            </div>
        </div>
    </div>

    <footer class="site-footer">
        <strong>Universitas Metamedia</strong>
        <span>Sistem Repository Kampus untuk skripsi, magang, karya dosen, panduan, laporan, dan pencarian
            global.</span>
    </footer>

    {{-- Tombol kembali ke atas --}}
    <button type="button" class="back-to-top" id="back-to-top" aria-label="Kembali ke atas">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4">
            <line x1="12" y1="19" x2="12" y2="5" />
            <polyline points="5 12 12 5 19 12" />
        </svg>
    </button>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // ── Tema Gelap / Terang ───────────────────────────────
            const themeToggle = document.getElementById('theme-toggle');
            const htmlEl = document.documentElement;
            const savedTheme = localStorage.getItem('repo-theme');
            if (savedTheme) htmlEl.setAttribute('data-theme', savedTheme);

            if (themeToggle) {
                themeToggle.addEventListener('click', function () {
                    const next = htmlEl.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                    htmlEl.setAttribute('data-theme', next);
                    localStorage.setItem('repo-theme', next);
                });
            }

            // ── Pencarian Cepat ───────────────────────────────────
            const searchToggle = document.getElementById('search-toggle');
            const searchForm = document.getElementById('search-form');
            const searchInput = searchForm ? searchForm.querySelector('input[name="q"]') : null;

            function openSearch() {
                searchForm.classList.add('open');
                searchToggle.setAttribute('aria-expanded', 'true');
                setTimeout(() => searchInput && searchInput.focus(), 150);
            }
            function closeSearch() {
                searchForm.classList.remove('open');
                searchToggle.setAttribute('aria-expanded', 'false');
            }
            if (searchToggle) {
                searchToggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    searchForm.classList.contains('open') ? closeSearch() : openSearch();
                });
                document.addEventListener('click', function (e) {
                    if (searchForm && !searchForm.contains(e.target) && e.target !== searchToggle) closeSearch();
                });
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') closeSearch();
                });
            }

            // ── Profile Dropdown ──────────────────────────────────
            const pdToggle = document.getElementById('profile-dropdown-toggle');
            const pdWrapper = document.getElementById('profile-dropdown-wrapper');

            function openDropdown() { pdWrapper.classList.add('open'); pdToggle.setAttribute('aria-expanded', 'true'); }
            function closeDropdown() { pdWrapper.classList.remove('open'); pdToggle.setAttribute('aria-expanded', 'false'); }

            if (pdToggle) {
                pdToggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    pdWrapper.classList.contains('open') ? closeDropdown() : openDropdown();
                });
                document.addEventListener('click', function (e) {
                    if (pdWrapper && !pdWrapper.contains(e.target)) closeDropdown();
                });
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') closeDropdown();
                });
            }

            // ── Mobile Drawer ─────────────────────────────────────
            const hamburger = document.getElementById('hamburger');
            const overlay = document.getElementById('mobile-overlay');
            const drawer = document.getElementById('mobile-drawer');
            const drawerClose = document.getElementById('drawer-close');
            const body = document.body;
            const desktopNavQuery = window.matchMedia('(min-width: 981px)');

            function openDrawer() {
                if (!hamburger || !overlay || !drawer) return;

                drawer.classList.add('open');
                overlay.classList.add('open');
                hamburger.classList.add('open');
                hamburger.setAttribute('aria-label', 'Tutup menu');
                hamburger.setAttribute('aria-expanded', 'true');
                drawer.setAttribute('aria-hidden', 'false');
                overlay.setAttribute('aria-hidden', 'false');
                body.style.overflow = 'hidden';
            }

            function closeDrawer() {
                if (!hamburger || !overlay || !drawer) return;

                drawer.classList.remove('open');
                overlay.classList.remove('open');
                hamburger.classList.remove('open');
                hamburger.setAttribute('aria-label', 'Buka menu');
                hamburger.setAttribute('aria-expanded', 'false');
                drawer.setAttribute('aria-hidden', 'true');
                overlay.setAttribute('aria-hidden', 'true');
                body.style.overflow = '';
            }

            function toggleDrawer() {
                if (!drawer) return;

                drawer.classList.contains('open') ? closeDrawer() : openDrawer();
            }

            if (hamburger) hamburger.addEventListener('click', toggleDrawer);
            if (overlay) overlay.addEventListener('click', closeDrawer);
            if (drawerClose) drawerClose.addEventListener('click', closeDrawer);
            if (drawer) {
                drawer.querySelectorAll('a').forEach(function (link) {
                    link.addEventListener('click', closeDrawer);
                });
            }
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeDrawer();
            });
            desktopNavQuery.addEventListener('change', function (event) {
                if (event.matches) closeDrawer();
            });

            // Scroll-shadow on header
            const header = document.getElementById('site-header');
            window.addEventListener('scroll', function () {
                header.classList.toggle('scrolled', window.scrollY > 8);
            }, { passive: true });

            // ── Flash Message: tutup manual + auto-hilang ─────────
            const flash = document.getElementById('flash-message');
            const flashClose = document.getElementById('flash-close');
            if (flash) {
                const hideFlash = () => {
                    flash.style.transition = 'opacity .3s ease, transform .3s ease';
                    flash.style.opacity = '0';
                    flash.style.transform = 'translateY(-8px)';
                    setTimeout(() => flash.remove(), 300);
                };
                if (flashClose) flashClose.addEventListener('click', hideFlash);
                setTimeout(hideFlash, 6000);
            }

            // ── Tombol Kembali ke Atas ─────────────────────────────
            const backToTop = document.getElementById('back-to-top');
            if (backToTop) {
                window.addEventListener('scroll', function () {
                    backToTop.classList.toggle('show', window.scrollY > 400);
                }, { passive: true });
                backToTop.addEventListener('click', function () {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }
        });
    </script>
</body>

</html>
