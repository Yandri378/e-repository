<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDocumentController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosenApprovalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RepositoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/repository/{kategori?}', [PublicController::class, 'repository'])->name('repository.index');
Route::get('/panduan-template', [PublicController::class, 'guides'])->name('guides.index');
Route::get('/api/upload-statuses', [PublicController::class, 'uploadStatuses'])->name('api.upload.statuses');
Route::get('/kontak', fn() => redirect()->route('home'))->name('contact');
Route::get('/upload/{actor}/{kategori}', [RepositoryController::class, 'publicCreate'])
    ->name('public.upload.create');
Route::post('/upload/{actor}/{kategori}', [RepositoryController::class, 'publicStore'])
    ->name('public.upload.store');
Route::get('/submission/{document}/{token}', [RepositoryController::class, 'submissionDetail'])->name('public.upload.detail');
Route::post('/repository/dokumen/{document}/request-download', [RepositoryController::class, 'requestDownload'])->name('repository.request.download');
Route::get('/repository/dokumen/{document}/bebas-pustaka', [RepositoryController::class, 'downloadBebasPustaka'])->name('repository.bebas-pustaka');
Route::get('/repository/dokumen/{document}/download-approved', [PublicController::class, 'downloadRequested'])->name('repository.download.approved');
Route::get('/mahasiswa', function () {
    if (!auth()->check()) {
        return app(PublicController::class)->mahasiswaHome();
    }

    return redirect()->route(auth()->user()->role . '.dashboard');
})->name('public.mahasiswa.home');
Route::get('/dosen', function () {
    if (!auth()->check()) {
        return app(PublicController::class)->dosenHome();
    }

    return redirect()->route(auth()->user()->role . '.dashboard');
})->name('public.dosen.home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::get('/register/{role?}', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'account.active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::get('/repository/dokumen/{document}', [PublicController::class, 'showDocument'])->name('repository.show');
    Route::get('/repository/dokumen/{document}/download-file', [RepositoryController::class, 'download'])->name('repository.file.download');
    Route::get('/repository/dokumen/{document}/download', [RepositoryController::class, 'download'])->name('repository.download');
    Route::get('/repository/dokumen/{document}/download-project', [RepositoryController::class, 'downloadProject'])->name('repository.project.download');
    Route::get('/repository/dokumen/{document}/meta', [PublicController::class, 'metadata'])->name('repository.meta');
    Route::get('/repository/dokumen/{document}/file', [PublicController::class, 'streamDocument'])->name('repository.stream');
    Route::get('/repository/dokumen/{document}/file/signed', [PublicController::class, 'streamSigned'])->name('repository.stream.signed');

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/dosen/dashboard', [DashboardController::class, 'dosen'])
        ->middleware('role:dosen')
        ->name('dosen.dashboard');

    Route::get('/mahasiswa/dashboard', [DashboardController::class, 'mahasiswa'])
        ->middleware('role:mahasiswa')
        ->name('mahasiswa.dashboard');

    Route::get('/repository/{kategori}/tambah', [RepositoryController::class, 'create'])
        ->name('repository.create');
    Route::post('/repository/{kategori}', [RepositoryController::class, 'store'])
        ->name('repository.store');

    Route::middleware('role:dosen')->prefix('dosen/bimbingan')->name('dosen.approvals.')->group(function () {
        Route::get('/', [DosenApprovalController::class, 'index'])->name('index');
        Route::patch('/{document}/approve', [DosenApprovalController::class, 'approve'])->name('approve');
        Route::patch('/{document}/reject', [DosenApprovalController::class, 'reject'])->name('reject');
    });

    Route::middleware('role:admin')->prefix('laporan')->name('reports.')->group(function () {
        Route::get('/export/{format}', [ReportController::class, 'export'])->name('export');
        Route::post('/', [ReportController::class, 'store'])->name('store');
        Route::patch('/{document}', [ReportController::class, 'update'])->name('update');
        Route::delete('/{document}', [ReportController::class, 'destroy'])->name('destroy');
        Route::get('/{kategori?}', [ReportController::class, 'index'])->name('index');
    });

    Route::patch('/admin/settings/upload-session', [AdminSettingController::class, 'updateUploadSession'])
        ->middleware('role:admin')
        ->name('admin.settings.upload-session');

    // Admin settings page: contact (WA / email)
    Route::get('/admin/settings', [AdminSettingController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.settings.index');

    Route::post('/admin/settings', [AdminSettingController::class, 'updateContact'])
        ->middleware('role:admin')
        ->name('admin.settings.update');

    Route::get('/admin/data-mahasiswa', [AdminDocumentController::class, 'mahasiswa'])
        ->middleware('role:admin')
        ->name('admin.data.mahasiswa');
    Route::get('/admin/data-mahasiswa/export/{format}', [AdminDocumentController::class, 'exportMahasiswa'])
        ->middleware('role:admin')
        ->name('admin.data.mahasiswa.export');
    Route::get('/admin/data-dosen/{kategori?}', [AdminDocumentController::class, 'dosen'])
        ->middleware('role:admin')
        ->name('admin.data.dosen');
    Route::get('/admin/data-dosen/{kategori?}/export/{format}', [AdminDocumentController::class, 'exportDosen'])
        ->middleware('role:admin')
        ->name('admin.data.dosen.export');

    Route::middleware('role:admin')->prefix('admin/users')->name('admin.users.')->group(function () {
        Route::get('/verifikasi', [AdminUserController::class, 'pending'])->name('pending');
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::post('/', [AdminUserController::class, 'store'])->name('store');
        Route::patch('/{user}/status', [AdminUserController::class, 'updateStatus'])->name('status');
        Route::delete('/{user}', [AdminUserController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('role:admin')->prefix('admin/uploads')->name('admin.documents.')->group(function () {
        Route::get('/verifikasi', [AdminDocumentController::class, 'pending'])->name('pending');
        Route::get('/', [AdminDocumentController::class, 'index'])->name('index');
        Route::get('/import', [AdminDocumentController::class, 'showImport'])->name('import');
        Route::post('/import', [AdminDocumentController::class, 'import'])->name('import.store');
        Route::get('/import/template/{kategori}', [AdminDocumentController::class, 'downloadTemplate'])->name('import.template');
        Route::patch('/verify-all', [AdminDocumentController::class, 'verifyAll'])->name('verify-all');
        Route::get('/{document}/download', [AdminDocumentController::class, 'download'])->name('download');
        Route::patch('/{document}/status', [AdminDocumentController::class, 'updateStatus'])->name('status');
        Route::patch('/{document}/bebas-pustaka', [AdminDocumentController::class, 'updateBebasPustakaStatus'])->name('bebas-pustaka');
        Route::post('/{document}/bebas-pustaka/approve', [AdminDocumentController::class, 'approveBebasPustaka'])->name('bebas-pustaka.approve');
        Route::post('/{document}/bebas-pustaka/revoke', [AdminDocumentController::class, 'revokeBebasPustaka'])->name('bebas-pustaka.revoke');
        Route::delete('/{document}', [AdminDocumentController::class, 'destroy'])->name('destroy');
    });

    // Admin download requests
    Route::middleware('role:admin')->get('/admin/download-requests', [AdminDocumentController::class, 'downloadRequests'])->name('admin.download.requests');
    Route::middleware('role:admin')->patch('/admin/download-requests/{downloadRequest}/approve', [AdminDocumentController::class, 'approveDownloadRequest'])->name('admin.download.requests.approve');
});
