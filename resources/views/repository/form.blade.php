@extends('layouts.app')

@section('title', 'Tambah Repository')

@section('content')
<section class="auth-shell @if($isPublic) public-upload-shell @endif" @if($isPublic) data-actor="{{ $actor }}" data-kategori="{{ $kategori }}"@endif>
    @php
        $currentUser = auth()->user();
        $identityLabel = ($actor ?? 'admin') === 'dosen' ? 'NIDN / Identitas Dosen' : 'NIM';
        $identityName = ($actor ?? 'admin') === 'dosen' ? 'nidn' : 'nim';
        $canUploadProject = (($currentUser?->role === 'mahasiswa') || ($isPublic && ($actor ?? null) === 'mahasiswa'))
            && in_array($kategori, ['skripsi', 'magang'], true);
    @endphp
    <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="auth-card wide">
        @csrf
        <p class="eyebrow">Input {{ ucfirst($kategori) }}</p>
        <h1>{{ $isPublic ? 'Upload Dokumen' : 'Tambah Data Repository' }}</h1>
        @if($isPublic)
            <div class="upload-indicator">
                <span class="upload-status-label">Sesi: <strong>Dibuka</strong></span>
                <small class="upload-last-update">Terakhir diperiksa: --</small>
            </div>
        @endif
        @if ($errors->any())
            <div class="error-box">{{ $errors->first() }}</div>
        @endif
        <div class="form-grid">
            @if (! $isPublic && $currentUser?->role === 'admin' && $kategori === 'magang')
                <label>Jenis Input
                    <select name="jenis_input">
                        <option value="upload">Data dengan Upload PDF</option>
                        <option value="arsip">Data Arsip Tanpa File</option>
                    </select>
                </label>
            @endif
            <label>Nama
                <input type="text" name="nama" value="{{ old('nama', $currentUser?->name) }}" required>
            </label>
            <label>Email
                <input type="email" name="email" value="{{ old('email', $currentUser?->email) }}" @required($isPublic)>
            </label>
            <label>{{ $identityLabel }}
                <input type="text" name="{{ $identityName }}" value="{{ old($identityName, $identityName === 'nim' ? $currentUser?->nim : $currentUser?->nidn) }}" @required($isPublic)>
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
                    <input type="text" id="dosen_search" list="dosen-list" placeholder="Ketik nama dosen pembimbing..." autocomplete="off" value="{{ $selectedDosen ? $selectedDosen->name . ($selectedDosen->nidn ? ' - ' . $selectedDosen->nidn : '') : '' }}" required>
                    <datalist id="dosen-list">
                        @foreach ($dosenPembimbing ?? [] as $dosen)
                            <option value="{{ $dosen->name }}{{ $dosen->nidn ? ' - '.$dosen->nidn : '' }}" data-id="{{ $dosen->id }}" data-name="{{ $dosen->name }}" data-nidn="{{ $dosen->nidn }}"></option>
                        @endforeach
                    </datalist>
                    <input type="hidden" name="dosen_pembimbing_id" id="dosen_pembimbing_id" value="{{ old('dosen_pembimbing_id') }}">
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
            <label>Bulan
                <input type="number" name="bulan" min="1" max="12" value="{{ old('bulan', date('n')) }}">
            </label>
            <label>Judul
                <input type="text" name="judul" value="{{ old('judul') }}" required>
            </label>
            <label>Tempat Magang
                <input type="text" name="tempat_magang" value="{{ old('tempat_magang') }}">
            </label>
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
            <label class="full-field">Detail
                <textarea name="detail" rows="4" placeholder="Isi detail tambahan, catatan pustaka, atau keterangan dokumen.">{{ old('detail') }}</textarea>
            </label>
            <label class="full-field">Upload Dokumen PDF
                <input type="file" name="file_dokumen" accept="application/pdf" @required($isPublic || (auth()->user()?->role !== 'admin'))>
            </label>
            @if ($canUploadProject)
                <label class="full-field">Upload File Project ZIP/RAR
                    <input type="file" name="file_project" accept=".zip,.rar,application/zip,application/x-rar-compressed">
                    <small>Opsional. File project akan ikut dicek dosen pembimbing saat proses ACC.</small>
                </label>
            @endif
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
    to { transform: rotate(360deg); }
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
        for (let i = 0; i < options.length; i++) {
            const optVal = options[i].value.trim().toLowerCase();
            const optName = (options[i].getAttribute('data-name') || '').trim().toLowerCase();
            const optNidn = (options[i].getAttribute('data-nidn') || '').trim().toLowerCase();

            // Match if input is equal to full value (name + NIDN), just the name, or just the NIDN
            if (optVal === val || optName === val || optNidn === val) {
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
                this.value = match.fullText; // Auto-complete to the full format
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

    // 3. Form Submit Handler & Loading Indicator
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (!form.checkValidity()) {
                return; // Let native browser validation display alerts
            }

            // Custom validation check for Dosen Pembimbing (only if the element is required)
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

            // Show submit loading notification
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                // To avoid interrupting form submit, disable button using setTimeout
                setTimeout(() => {
                    submitBtn.disabled = true;
                    submitBtn.innerText = 'Memproses & Menyimpan Data...';
                }, 0);

                // Add loading indicator container
                if (!document.querySelector('.submit-loading-indicator')) {
                    const loader = document.createElement('div');
                    loader.className = 'submit-loading-indicator';
                    loader.innerHTML = '<div class="spinner"></div><span>Sedang mengunggah dokumen, mohon tunggu...</span>';
                    submitBtn.parentNode.appendChild(loader);
                }
            }
        });
    }
});
</script>
@endsection

