@extends('layouts.app')

@section('title', 'Daftar Akun Mahasiswa & Dosen')

    <style>
        @media (max-width: 640px) {
            .register-form-grid {
                grid-template-columns: 1fr !important;
                gap: 14px !important;
            }
            .register-form-grid > div {
                grid-column: span 1 !important;
            }
        }
    </style>
    <section class="auth-shell" style="min-height: 85vh; display: flex; align-items: center; justify-content: center; padding: clamp(1rem, 4vw, 2.5rem) 1rem;">
        <div class="auth-card wide" style="width: 100%; max-width: 620px; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(16px); border: 1px solid rgba(56, 189, 248, 0.25); border-radius: 16px; padding: clamp(1.25rem, 5vw, 2.5rem); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
            
            <div style="text-align: center; margin-bottom: 2rem;">
                <p class="eyebrow" style="color: #38BDF8; font-size: 11px; letter-spacing: 0.2em; text-transform: uppercase; font-weight: 700; margin: 0 0 6px;">Pendaftaran Mandiri</p>
                <h1 style="font-size: clamp(1.5rem, 4vw, 2.2rem); font-weight: 700; color: #FFFFFF; margin: 0 0 8px;">Buat Akun Repositori</h1>
                <p style="font-size: 14px; color: #94A3B8; margin: 0;">Silakan isi formulir di bawah untuk mendaftar sebagai Mahasiswa atau Dosen.</p>
            </div>

            @if ($errors->any())
                <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.35); border-left: 4px solid #EF4444; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; color: #FCA5A5; font-size: 13.5px; line-height: 1.5;">
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
                <div style="margin-bottom: 22px;">
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #E2E8F0; margin-bottom: 8px; letter-spacing: 0.04em;">Daftar Sebagai:</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; background: rgba(30, 41, 59, 0.7); padding: 5px; border-radius: 10px; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <label id="tab-mahasiswa" style="display: flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 14px; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.2s ease; margin: 0; {{ $selectedRole === 'mahasiswa' ? 'background: #2563EB; color: #FFFFFF; box-shadow: 0 2px 8px rgba(37,99,235,0.4);' : 'color: #94A3B8;' }}">
                            <input type="radio" name="role" value="mahasiswa" id="role-mahasiswa" style="display: none;" {{ $selectedRole === 'mahasiswa' ? 'checked' : '' }}>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                            <span>Mahasiswa</span>
                        </label>
                        <label id="tab-dosen" style="display: flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 14px; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.2s ease; margin: 0; {{ $selectedRole === 'dosen' ? 'background: #2563EB; color: #FFFFFF; box-shadow: 0 2px 8px rgba(37,99,235,0.4);' : 'color: #94A3B8;' }}">
                            <input type="radio" name="role" value="dosen" id="role-dosen" style="display: none;" {{ $selectedRole === 'dosen' ? 'checked' : '' }}>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5Z"/><path d="M6 6h10M6 10h10"/></svg>
                            <span>Dosen</span>
                        </label>
                    </div>
                </div>

                <div class="register-form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    {{-- Nama Lengkap --}}
                    <div style="grid-column: span 2;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #E2E8F0; margin-bottom: 6px;">Nama Lengkap (dengan Gelar jika Dosen) <span style="color: #EF4444;">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Budi Santoso, S.Kom." required
                            style="width: 100%; background: rgba(30, 41, 59, 0.7); border: 1.5px solid rgba(255, 255, 255, 0.15); border-radius: 8px; padding: 11px 14px; color: #FFFFFF; font-size: 14px; outline: none; transition: border-color 0.2s;">
                    </div>

                    {{-- NIM Container --}}
                    <div id="nim-box" style="grid-column: span 1; display: {{ $selectedRole === 'mahasiswa' ? 'block' : 'none' }};">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #E2E8F0; margin-bottom: 6px;">NIM (Nomor Induk Mahasiswa) <span style="color: #EF4444;">*</span></label>
                        <input type="text" id="nim-field" name="nim" value="{{ old('nim') }}" placeholder="Contoh: 202201001" {{ $selectedRole === 'mahasiswa' ? 'required' : '' }}
                            style="width: 100%; background: rgba(30, 41, 59, 0.7); border: 1.5px solid rgba(255, 255, 255, 0.15); border-radius: 8px; padding: 11px 14px; color: #FFFFFF; font-size: 14px; outline: none; transition: border-color 0.2s;">
                    </div>

                    {{-- NIDN Container --}}
                    <div id="nidn-box" style="grid-column: span 1; display: {{ $selectedRole === 'dosen' ? 'block' : 'none' }};">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #E2E8F0; margin-bottom: 6px;">NIDN (Nomor Induk Dosen Nasional) <span style="color: #EF4444;">*</span></label>
                        <input type="text" id="nidn-field" name="nidn" value="{{ old('nidn') }}" placeholder="Contoh: 0012345678" {{ $selectedRole === 'dosen' ? 'required' : '' }}
                            style="width: 100%; background: rgba(30, 41, 59, 0.7); border: 1.5px solid rgba(255, 255, 255, 0.15); border-radius: 8px; padding: 11px 14px; color: #FFFFFF; font-size: 14px; outline: none; transition: border-color 0.2s;">
                    </div>

                    {{-- Program Studi --}}
                    <div style="grid-column: span 1;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #E2E8F0; margin-bottom: 6px;">Program Studi <span style="color: #EF4444;">*</span></label>
                        <select name="program_studi_id" required
                            style="width: 100%; background: #1E293B; border: 1.5px solid rgba(255, 255, 255, 0.15); border-radius: 8px; padding: 11px 14px; color: #FFFFFF; font-size: 14px; outline: none;">
                            <option value="" style="background: #1E293B; color: #94A3B8;">-- Pilih Program Studi --</option>
                            @foreach ($programStudi as $prodi)
                                <option value="{{ $prodi->id }}" style="background: #1E293B; color: #FFFFFF;" @selected(old('program_studi_id') == $prodi->id)>
                                    {{ $prodi->nama }} ({{ $prodi->jenjang }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Email --}}
                    <div style="grid-column: span 1;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #E2E8F0; margin-bottom: 6px;">Alamat Email <span style="color: #EF4444;">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@kampus.ac.id" required
                            style="width: 100%; background: rgba(30, 41, 59, 0.7); border: 1.5px solid rgba(255, 255, 255, 0.15); border-radius: 8px; padding: 11px 14px; color: #FFFFFF; font-size: 14px; outline: none; transition: border-color 0.2s;">
                    </div>

                    {{-- WhatsApp --}}
                    <div style="grid-column: span 1;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #E2E8F0; margin-bottom: 6px;">No. WhatsApp / HP</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="08xxxxxxxxxx"
                            style="width: 100%; background: rgba(30, 41, 59, 0.7); border: 1.5px solid rgba(255, 255, 255, 0.15); border-radius: 8px; padding: 11px 14px; color: #FFFFFF; font-size: 14px; outline: none; transition: border-color 0.2s;">
                    </div>

                    {{-- Password --}}
                    <div style="grid-column: span 1;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #E2E8F0; margin-bottom: 6px;">Kata Sandi <span style="color: #EF4444;">*</span></label>
                        <input type="password" name="password" placeholder="Minimal 8 karakter" required minlength="8"
                            style="width: 100%; background: rgba(30, 41, 59, 0.7); border: 1.5px solid rgba(255, 255, 255, 0.15); border-radius: 8px; padding: 11px 14px; color: #FFFFFF; font-size: 14px; outline: none; transition: border-color 0.2s;">
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div style="grid-column: span 1;">
                        <label style="display: block; font-size: 12px; font-weight: 600; color: #E2E8F0; margin-bottom: 6px;">Konfirmasi Sandi <span style="color: #EF4444;">*</span></label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi kata sandi" required minlength="8"
                            style="width: 100%; background: rgba(30, 41, 59, 0.7); border: 1.5px solid rgba(255, 255, 255, 0.15); border-radius: 8px; padding: 11px 14px; color: #FFFFFF; font-size: 14px; outline: none; transition: border-color 0.2s;">
                    </div>
                </div>

                {{-- Submit Button --}}
                <div style="margin-top: 28px;">
                    <button type="submit" class="btn primary full"
                        style="width: 100%; background: #2563EB; color: #FFFFFF; border: none; border-radius: 8px; padding: 14px 20px; font-size: 15px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35); transition: all 0.2s ease;">
                        Daftar Akun Sekarang
                    </button>
                </div>

                <div style="margin-top: 20px; text-align: center; font-size: 13.5px; color: #94A3B8;">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" style="color: #38BDF8; font-weight: 600; text-decoration: underline; margin-left: 4px;">
                        Masuk ke sini
                    </a>
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
                    tabMahasiswa.style.background = '#2563EB';
                    tabMahasiswa.style.color = '#FFFFFF';
                    tabMahasiswa.style.boxShadow = '0 2px 8px rgba(37,99,235,0.4)';

                    tabDosen.style.background = 'transparent';
                    tabDosen.style.color = '#94A3B8';
                    tabDosen.style.boxShadow = 'none';

                    nimBox.style.display = 'block';
                    nidnBox.style.display = 'none';
                    nimField.required = true;
                    nidnField.required = false;
                    nidnField.value = '';
                } else {
                    tabDosen.style.background = '#2563EB';
                    tabDosen.style.color = '#FFFFFF';
                    tabDosen.style.boxShadow = '0 2px 8px rgba(37,99,235,0.4)';

                    tabMahasiswa.style.background = 'transparent';
                    tabMahasiswa.style.color = '#94A3B8';
                    tabMahasiswa.style.boxShadow = 'none';

                    nimBox.style.display = 'none';
                    nidnBox.style.display = 'block';
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