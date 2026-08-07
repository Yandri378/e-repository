@extends('layouts.app')

@section('title', 'Tambah Repository')

@section('content')
    <section class="auth-shell @if($isPublic) public-upload-shell @endif" @if($isPublic) data-actor="{{ $actor }}"
    data-kategori="{{ $kategori }}" @endif>
        @php
            $currentUser = auth()->user();
            $identityLabel = ($actor ?? 'admin') === 'dosen' ? 'NIDN / Identitas Dosen' : 'NIM';
            $identityName = ($actor ?? 'admin') === 'dosen' ? 'nidn' : 'nim';
            $canUploadProject = in_array($kategori, ['skripsi', 'magang', 'penelitian', 'pkm'], true);
        @endphp
        <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="auth-card wide"
            id="uploadUserForm">
            @csrf
            <p class="eyebrow">Input {{ ucfirst($kategori) }}</p>
            <h1>{{ $isPublic ? 'Upload Dokumen' : 'Tambah Data Repository' }}</h1>
            @if($isPublic)
                <div class="upload-indicator">
                    <span class="upload-status-label">Sesi: <strong>Dibuka</strong></span>
                    <small class="upload-last-update">Terakhir diperiksa: --</small>
                </div>
            @endif
            {{-- SUCCESS NOTIFICATION --}}
            @if (session('status'))
                <div class="upload-alert-box upload-alert-success" role="alert">
                    <div class="alert-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </div>
                    <div class="alert-body">
                        <strong>Pemberitahuan Berhasil:</strong>
                        <p>{{ session('status') }}</p>
                    </div>
                </div>
            @endif

            {{-- ERROR NOTIFICATION WITH SPECIFIC REASONS --}}
            @if (session('error'))
                <div class="upload-alert-box upload-alert-danger" role="alert">
                    <div class="alert-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                    </div>
                    <div class="alert-body">
                        <strong>Upload / Proses Gagal:</strong>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="upload-alert-box upload-alert-danger" role="alert">
                    <div class="alert-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                    </div>
                    <div class="alert-body">
                        <strong>Upload Gagal! Terdapat kesalahan pada input form:</strong>
                        <ul class="error-reasons-list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            <div class="form-grid">
                @if (!$isPublic && $currentUser?->role === 'admin' && $kategori === 'magang')
                    <label>Jenis Input
                        <select name="jenis_input">
                            <option value="upload">Data dengan Upload PDF</option>
                            <option value="arsip">Data Arsip Tanpa File</option>
                        </select>
                    </label>
                @endif
                <label>Nama
                    <input type="text" name="nama"
                        value="{{ old('nama', ($isAdminManualSkripsi ?? false) ? '' : $currentUser?->name) }}" required>
                </label>
                <label>{{ $identityLabel }}
                    <input type="text" name="{{ $identityName }}"
                        value="{{ old($identityName, $identityName === 'nim' ? $currentUser?->nim : $currentUser?->nidn) }}"
                        @required($isPublic)>
                </label>
                <label>Program Studi
                    <select name="program_studi_id" @required($isPublic)>
                        <option value="">Pilih prodi</option>
                        @foreach ($programStudi as $prodi)
                            <option value="{{ $prodi->id }}" @selected(old('program_studi_id', $currentUser?->program_studi_id) == $prodi->id)>{{ $prodi->nama }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Jenis Dokumen
                    <select name="jenis_dokumen_id">
                        <option value="">Pilih jenis</option>
                        @foreach ($jenisDokumen as $jenis)
                            <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                        @endforeach
                    </select>
                </label>
                @if (($currentUser?->role === 'mahasiswa' || ($isPublic && ($actor ?? null) === 'mahasiswa')) && in_array($kategori, ['skripsi', 'magang'], true))
                    @php
                        $selectedDosen = null;
                        if (old('dosen_pembimbing_id')) {
                            $selectedDosen = collect($dosenPembimbing ?? [])->firstWhere('id', old('dosen_pembimbing_id'));
                        }
                    @endphp
                    <label>Dosen Pembimbing
                        <input type="text" id="dosen_search" list="dosen-list" placeholder="Ketik nama dosen pembimbing..."
                            autocomplete="off"
                            value="{{ $selectedDosen ? $selectedDosen->name . ($selectedDosen->nidn ? ' - ' . $selectedDosen->nidn : '') : '' }}"
                            required>
                        <datalist id="dosen-list">
                            @foreach ($dosenPembimbing ?? [] as $dosen)
                                <option value="{{ $dosen->name }}{{ $dosen->nidn ? ' - ' . $dosen->nidn : '' }}"
                                    data-id="{{ $dosen->id }}" data-name="{{ $dosen->name }}" data-nidn="{{ $dosen->nidn }}">
                                </option>
                            @endforeach
                        </datalist>
                        <input type="hidden" name="dosen_pembimbing_id" id="dosen_pembimbing_id"
                            value="{{ old('dosen_pembimbing_id') }}">
                        @if (empty($dosenPembimbing) || count($dosenPembimbing) === 0)
                            <small style="color: var(--error-color, #ef4444); display: block; margin-top: 5px;">
                                * Belum ada dosen pembimbing aktif yang terdaftar di sistem. Silakan hubungi admin.
                            </small>
                        @endif
                    </label>
                @endif
                <label>Tahun
                    <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" required>
                </label>
                <label>Judul
                    <input type="text" name="judul" value="{{ old('judul') }}" required>
                </label>
                @if ($kategori === 'magang')
                    <label>Tempat Magang
                        <input type="text" name="tempat_magang" value="{{ old('tempat_magang') }}">
                    </label>
                @endif
                <label>Jumlah Halaman
                    <input type="number" name="jumlah_halaman" value="{{ old('jumlah_halaman') }}">
                </label>
                <label>Status Penelitian
                    <select name="status_penelitian">
                        <option value="">-</option>
                        <option value="berjalan">Berjalan</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </label>
                <label class="full-field">Abstrak
                    <textarea name="abstrak" rows="5">{{ old('abstrak') }}</textarea>
                </label>
                <label class="full-field">Upload Dokumen PDF
                    <input type="file" name="file_dokumen" id="file_dokumen_input" accept="application/pdf"
                        @required($isPublic || (auth()->user()?->role !== 'admin'))>
                </label>
                @if ($canUploadProject)
                    <label class="full-field">Upload File Project ZIP/RAR
                        <input type="file" name="file_project" id="file_project_input"
                            accept=".zip,.rar,application/zip,application/x-rar-compressed">
                        <small>Opsional. File project akan ikut dicek dosen pembimbing saat proses ACC.</small>
                    </label>
                @endif
            </div>

            {{-- PDF Completeness Declaration (required for skripsi/magang) --}}
            @if (in_array($kategori, ['skripsi', 'magang'], true) && ($currentUser?->role === 'mahasiswa' || ($isPublic && ($actor ?? null) === 'mahasiswa')))
                <div class="pdf-declaration-box" id="pdf-declaration-section" style="display:none">
                    <div class="pdf-declaration-header">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 11l3 3L22 4" />
                            <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" />
                        </svg>
                        <strong>Deklarasi Kelengkapan PDF Skripsi</strong>
                    </div>
                    <p class="pdf-declaration-desc">Pastikan file PDF yang Anda upload sudah dilengkapi dengan scan
                        halaman-halaman berikut (disertai tanda tangan):</p>
                    <ul class="pdf-declaration-list">
                        <li>
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            <span><strong>Halaman Pengesahan</strong> — harus ada tanda tangan dosen dan pejabat kampus</span>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            <span><strong>Halaman Persetujuan</strong> — harus ada tanda tangan dosen pembimbing</span>
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            <span><strong>Pernyataan Orisinalitas</strong> — harus ada tanda tangan mahasiswa</span>
                        </li>
                    </ul>
                    <label class="pdf-declaration-checkbox">
                        <input type="checkbox" name="pdf_kelengkapan_deklarasi" value="1" id="pdf_kelengkapan_deklarasi"
                            required>
                        <span>Saya menyatakan bahwa file PDF yang saya upload telah memuat scan <strong>halaman pengesahan,
                                halaman persetujuan, dan pernyataan orisinalitas</strong> yang disertai tanda tangan yang sah.
                            Jika halaman tersebut tidak ada atau tidak ada tanda tangan, proses bebas pustaka dapat
                            ditolak.</span>
                    </label>
                    <div class="pdf-signature-warning" id="pdf-sig-warning" style="display:none">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <span>Perhatian: file PDF yang Anda pilih terdeteksi memiliki jumlah halaman yang sedikit. Pastikan scan
                            halaman pengesahan, persetujuan, dan orisinalitas sudah tergabung dalam satu file PDF.</span>
                    </div>
                </div>
            @endif

            <div id="upload-progress-panel"
                class="hidden mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                <div class="flex items-center justify-between gap-4 text-slate-800">
                    <span>Progress Upload</span>
                    <span id="upload-progress-text" class="font-semibold">0%</span>
                </div>
                <div class="mt-3 h-2 w-full overflow-hidden rounded-full bg-slate-200">
                    <div id="upload-progress-bar" class="h-full rounded-full bg-gradient-to-r from-blue-500 to-indigo-600"
                        style="width: 0%;"></div>
                </div>
            </div>
            <button type="submit" class="btn primary full">Simpan Data</button>
        </form>
    </section>

    <style>
        .submit-loading-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px;
            background: rgba(59, 130, 246, 0.08);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 8px;
            margin-top: 15px;
            font-weight: 500;
            color: var(--primary-color, #3b82f6);
        }

        .submit-loading-indicator .spinner {
            width: 18px;
            height: 18px;
            border: 3px solid rgba(59, 130, 246, 0.1);
            border-radius: 50%;
            border-top-color: var(--primary-color, #3b82f6);
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dosenSearch = document.getElementById('dosen_search');
            const dosenHidden = document.getElementById('dosen_pembimbing_id');
            const dosenList = document.getElementById('dosen-list');

            // Helper function to search for matching lecturer
            function findDosen(val) {
                val = val.trim().toLowerCase();
                if (!val) return null;

                const options = dosenList ? dosenList.options : [];
                // First try exact match
                for (let i = 0; i < options.length; i++) {
                    const optVal = options[i].value.trim().toLowerCase();
                    const optName = (options[i].getAttribute('data-name') || '').trim().toLowerCase();
                    const optNidn = (options[i].getAttribute('data-nidn') || '').trim().toLowerCase();

                    if (optVal === val || optName === val || optNidn === val) {
                        return {
                            id: options[i].getAttribute('data-id'),
                            fullText: options[i].value
                        };
                    }
                }
                // Fallback: partial match
                for (let i = 0; i < options.length; i++) {
                    const optVal = options[i].value.trim().toLowerCase();
                    const optName = (options[i].getAttribute('data-name') || '').trim().toLowerCase();
                    if (optVal.includes(val) || (optName && optName.includes(val))) {
                        return {
                            id: options[i].getAttribute('data-id'),
                            fullText: options[i].value
                        };
                    }
                }
                return null;
            }

            // 1. Dosen Search Sync & Match
            if (dosenSearch && dosenHidden && dosenList) {
                dosenSearch.addEventListener('input', function () {
                    const match = findDosen(this.value);
                    if (match) {
                        dosenHidden.value = match.id;
                        this.setCustomValidity('');
                    } else {
                        dosenHidden.value = '';
                    }
                });

                dosenSearch.addEventListener('change', function () {
                    const match = findDosen(this.value);
                    if (match) {
                        this.value = match.fullText;
                        dosenHidden.value = match.id;
                        this.setCustomValidity('');
                    } else if (this.value.trim() !== '') {
                        this.setCustomValidity('Silakan pilih dosen pembimbing yang valid dari daftar.');
                    } else {
                        this.setCustomValidity('');
                    }
                });
            }

            // 2. Smooth Scroll to Error on Validation Failure
            const errorBox = document.querySelector('.error-box');
            if (errorBox) {
                errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            // 3. PDF File: Show Declaration Section & Detect Page Count
            const pdfInput = document.querySelector('input[name="file_dokumen"]');
            const declarationSection = document.getElementById('pdf-declaration-section');
            const sigWarning = document.getElementById('pdf-sig-warning');
            const dekCheckbox = document.getElementById('pdf_kelengkapan_deklarasi');

            /**
             * Count PDF pages by scanning for /Type /Page in binary content.
             * This is a best-effort client-side count; the actual count is validated server-side.
             */
            function countPdfPages(arrayBuffer) {
                const bytes = new Uint8Array(arrayBuffer);
                let text = '';
                // Read only first 64KB to keep it fast
                const limit = Math.min(bytes.length, 65536);
                for (let i = 0; i < limit; i++) {
                    text += String.fromCharCode(bytes[i]);
                }
                // Match /Type /Page occurrences (not /Pages which is the catalog)
                const matches = text.match(/\/Type\s*\/Page[^s]/g);
                return matches ? matches.length : 0;
            }

            if (pdfInput && declarationSection) {
                pdfInput.addEventListener('change', function () {
                    const file = this.files[0];
                    if (!file || file.type !== 'application/pdf') {
                        declarationSection.style.display = 'none';
                        return;
                    }

                    // Show the declaration box
                    declarationSection.style.display = 'block';
                    declarationSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

                    // Reset checkbox & warning on new file selection
                    if (dekCheckbox) dekCheckbox.checked = false;
                    if (sigWarning) sigWarning.style.display = 'none';

                    // Read first bytes for page count detection
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const pageCount = countPdfPages(e.target.result);
                        // Warn if detected pages < 5 (too few for a proper skripsi with required scanned pages)
                        if (sigWarning && pageCount > 0 && pageCount < 5) {
                            sigWarning.style.display = 'flex';
                        } else if (sigWarning) {
                            sigWarning.style.display = 'none';
                        }
                    };
                    // Read only first 100KB for speed
                    reader.readAsArrayBuffer(file.slice(0, 102400));
                });
            }

            // 4. Form Submit Handler & Loading Indicator
            const form = document.getElementById('uploadUserForm');
            if (form) {
                form.addEventListener('submit', function (e) {
                    if (!form.checkValidity()) {
                        return;
                    }

                    // Custom validation for file sizes
                    const docInput = form.querySelector('input[name="file_dokumen"]');
                    const projInput = form.querySelector('input[name="file_project"]');
                    if (docInput && docInput.files.length > 0 && docInput.files[0].size > 10 * 1024 * 1024) {
                        e.preventDefault();
                        alert('Ukuran file PDF dokumen (' + (docInput.files[0].size / (1024 * 1024)).toFixed(2) + ' MB) melebihi batas maksimum 10 MB.');
                        return;
                    }
                    if (projInput && projInput.files.length > 0 && projInput.files[0].size > 100 * 1024 * 1024) {
                        e.preventDefault();
                        alert('Ukuran file Project ZIP/RAR (' + (projInput.files[0].size / (1024 * 1024)).toFixed(2) + ' MB) melebihi batas maksimum 100 MB.');
                        return;
                    }

                    // Custom validation for declaration checkbox visibility
                    if (declarationSection && declarationSection.style.display !== 'none') {
                        if (dekCheckbox && !dekCheckbox.checked) {
                            e.preventDefault();
                            dekCheckbox.setCustomValidity('Anda harus mencentang pernyataan kelengkapan PDF terlebih dahulu.');
                            dekCheckbox.reportValidity();
                            dekCheckbox.setCustomValidity('');
                            return;
                        }
                    }

                    // Custom validation for Dosen Pembimbing
                    if (dosenSearch && dosenSearch.required) {
                        const match = findDosen(dosenSearch.value);
                        if (!match) {
                            e.preventDefault();
                            dosenSearch.focus();
                            dosenSearch.setCustomValidity('Silakan pilih dosen pembimbing yang valid dari daftar.');
                            dosenSearch.reportValidity();
                            return;
                        } else {
                            dosenHidden.value = match.id;
                            dosenSearch.setCustomValidity('');
                        }
                    }

                    // Prepare upload progress UI
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const progressPanel = document.getElementById('upload-progress-panel');
                    const progressBar = document.getElementById('upload-progress-bar');
                    const progressText = document.getElementById('upload-progress-text');
                    if (progressPanel) {
                        progressPanel.classList.remove('hidden');
                    }
                    if (progressBar) {
                        progressBar.style.width = '0%';
                    }
                    if (progressText) {
                        progressText.textContent = '0%';
                    }
                    if (submitBtn) {
                        submitBtn.style.pointerEvents = 'none';
                        submitBtn.style.opacity = '0.75';
                        submitBtn.innerText = 'Mengunggah...';
                    }

                    e.preventDefault();
                    const formData = new FormData(form);
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', form.action);
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    const csrfToken = document.querySelector('input[name="_token"]')?.value;
                    if (csrfToken) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                    }

                    xhr.upload.onprogress = function (event) {
                        if (!event.lengthComputable) return;
                        const percent = Math.round((event.loaded / event.total) * 100);
                        if (progressBar) progressBar.style.width = percent + '%';
                        if (progressText) progressText.textContent = percent + '%';
                    };

                    xhr.onload = function () {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            window.location.href = xhr.responseURL || window.location.href;
                            return;
                        }
                        showUploadError('Terjadi kesalahan saat mengunggah. Silakan coba lagi.');
                    };

                    xhr.onerror = function () {
                        showUploadError('Gagal mengunggah. Periksa koneksi dan coba lagi.');
                    };

                    xhr.onabort = function () {
                        showUploadError('Unggah dibatalkan.');
                    };

                    xhr.send(formData);
                });
            }

            function showUploadError(message) {
                const progressPanel = document.getElementById('upload-progress-panel');
                const progressBar = document.getElementById('upload-progress-bar');
                const progressText = document.getElementById('upload-progress-text');
                const submitBtn = form.querySelector('button[type="submit"]');
                if (progressPanel) {
                    progressPanel.classList.add('hidden');
                }
                if (progressBar) {
                    progressBar.style.width = '0%';
                }
                if (progressText) {
                    progressText.textContent = '0%';
                }
                if (submitBtn) {
                    submitBtn.style.pointerEvents = '';
                    submitBtn.style.opacity = '';
                    submitBtn.innerText = 'Simpan Data';
                }
                alert(message);
            }
        });
    </script>

@endsection