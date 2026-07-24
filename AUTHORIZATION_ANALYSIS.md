# 📋 AUTHORIZATION & RBAC ANALYSIS REPORT

**Tanggal**: 9 Juli 2026  
**Aplikasi**: Repository Akademik - Laravel Application  
**Fokus**: Dashboard Routes, Middleware, dan Role-Based Access Control

---

## 📊 RINGKASAN EKSEKUTIF

Aplikasi mengimplementasikan **Role-Based Access Control (RBAC)** dengan 3 role utama:
- **Admin** - Akses penuh ke semua fitur admin
- **Dosen** - Dashboard dosen, upload PKM & penelitian
- **Mahasiswa** - Dashboard mahasiswa, upload skripsi & magang

Authorization **didominasi oleh middleware route-level**, dengan **MINIMUN layer protection di controller dan view**.

---

## 🔍 FINDINGS DETAIL

### 1. DASHBOARD ROUTES & MIDDLEWARE

#### A. Admin Dashboard
**File**: [routes/web.php](routes/web.php#L39-L41)
```php
Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
    ->middleware('role:admin')
    ->name('admin.dashboard');
```
- **Controller**: [DashboardController@admin()](app/Http/Controllers/DashboardController.php#L12)
- **Middleware Stack**: `auth` → `account.active` → `role:admin`
- **View**: Extends [layouts/admin](resources/views/layouts/admin.blade.php) (admin-specific layout)

#### B. Dosen Dashboard
**File**: [routes/web.php](routes/web.php#L42-L44)
```php
Route::get('/dosen/dashboard', [DashboardController::class, 'dosen'])
    ->middleware('role:dosen')
    ->name('dosen.dashboard');
```
- **Controller**: [DashboardController@dosen()](app/Http/Controllers/DashboardController.php#L55)
- **Middleware Stack**: `auth` → `account.active` → `role:dosen`
- **View**: Extends [layouts/app](resources/views/layouts/app.blade.php) (public layout)

#### C. Mahasiswa Dashboard
**File**: [routes/web.php](routes/web.php#L45-L47)
```php
Route::get('/mahasiswa/dashboard', [DashboardController::class, 'mahasiswa'])
    ->middleware('role:mahasiswa')
    ->name('mahasiswa.dashboard');
```
- **Controller**: [DashboardController@mahasiswa()](app/Http/Controllers/DashboardController.php#L62)
- **Middleware Stack**: `auth` → `account.active` → `role:mahasiswa`
- **View**: Extends [layouts/app](resources/views/layouts/app.blade.php) (public layout)

---

### 2. MIDDLEWARE ANALYSIS

#### A. RoleMiddleware (`role:admin|dosen|mahasiswa`)
**File**: [app/Http/Middleware/RoleMiddleware.php](app/Http/Middleware/RoleMiddleware.php)

**Implementasi**:
```php
public function handle(Request $request, Closure $next, string ...$roles): Response
{
    if (! $request->user() || ! in_array($request->user()->role, $roles, true)) {
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
    return $next($request);
}
```

**Analisis**:
- ✅ Menggunakan `in_array(..., true)` - strict type comparison
- ✅ Mengecek authenticated user ada (`$request->user()`)
- ✅ Bekerja dengan multiple roles (variadic parameters)
- ⚠️ **Tidak ada authorization logic di controller** - semua bergantung middleware

**Contoh Penggunaan di Routes**:
```php
// Single role
Route::get('/admin/dashboard', [...])
    ->middleware('role:admin');

// Multiple roles (tidak digunakan dalam app ini)
Route::get('/route', [...])
    ->middleware('role:admin,moderator');
```

---

#### B. EnsureAccountIsActive (`account.active`)
**File**: [app/Http/Middleware/EnsureAccountIsActive.php](app/Http/Middleware/EnsureAccountIsActive.php)

**Implementasi**:
```php
public function handle(Request $request, Closure $next): Response
{
    $user = $request->user();

    if ($user && $user->role !== 'admin' && $user->status_akun !== 'aktif') {
        Auth::logout();
        return redirect()->route('login')
            ->withErrors([
                'login' => 'Akun Anda masih menunggu verifikasi Admin. Silakan tunggu atau hubungi Admin.',
            ]);
    }

    return $next($request);
}
```

**Analisis**:
- ✅ Logout user jika status tidak aktif (non-admin)
- ⚠️ **CRITICAL ISSUE**: Admin **TIDAK dicek status akunnya**
  - Kondisi: `$user->role !== 'admin' && $user->status_akun !== 'aktif'`
  - Artinya: Jika role adalah admin, middleware SKIP check status
  - **Risk**: Admin dengan status tidak aktif tetap bisa akses semua

**Table**: User Status vs Middleware Behavior
| Role | Status | Result | Behavior |
|------|--------|--------|----------|
| admin | aktif | ✅ | Allowed (role bypass) |
| admin | menunggu_verifikasi | ✅ | Allowed (role bypass) |
| dosen | aktif | ✅ | Allowed |
| dosen | menunggu_verifikasi | ❌ | Logout & Redirect |
| mahasiswa | aktif | ✅ | Allowed |
| mahasiswa | menunggu_verifikasi | ❌ | Logout & Redirect |

---

#### C. Auth Middleware (Laravel Default)
- **Fungsi**: Verifikasi user sudah authenticated
- **Behavior**: Redirect ke login jika tidak authenticated
- **Registrasi**: Di bootstrap/app.php sebagai middleware global/named

---

### 3. MIDDLEWARE REGISTRATION

**File**: [bootstrap/app.php](bootstrap/app.php)

```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'role' => App\Http\Middleware\RoleMiddleware::class,
        'account.active' => App\Http\Middleware\EnsureAccountIsActive::class,
    ]);
})
```

**Analisis**:
- ✅ Middleware di-alias dengan benar
- ✅ Tersedia untuk semua routes yang membutuhkan
- ✅ Route group `auth` & `account.active` di-apply secara global di group

**Route Group Structure**:
```php
Route::middleware(['auth', 'account.active'])->group(function () {
    Route::get('/admin/dashboard', ...) ->middleware('role:admin');
    Route::get('/dosen/dashboard', ...) ->middleware('role:dosen');
    Route::get('/mahasiswa/dashboard', ...) ->middleware('role:mahasiswa');
});
```

---

### 4. AUTHENTICATION FLOW

#### A. Standard Login (Mahasiswa/Dosen)
**File**: [app/Http/Controllers/AuthController.php - login()](app/Http/Controllers/AuthController.php#L23-L60)

**Flow**:
```
1. Validasi input (role harus 'mahasiswa' atau 'dosen')
2. Cari user berdasarkan email/NIM/NIDN
3. Verifikasi: user->role === form->role ✅
4. Auth::attempt dengan email & password
5. Regenerate session
6. Verifikasi: status_akun === 'aktif' ✅
7. Redirect ke {role}.dashboard
```

**Analisis**:
- ✅ Role validation di form
- ✅ Role matching check saat login
- ✅ Status verification sebelum redirect
- ✅ Session regeneration dilakukan

#### B. Admin Login
**File**: [app/Http/Controllers/AuthController.php - adminLogin()](app/Http/Controllers/AuthController.php#L61-L82)

**Flow**:
```
1. Validasi input (email & password)
2. Query khusus: where('role', 'admin')
3. Auth::attempt dengan email & password
4. Regenerate session
5. Redirect ke admin.dashboard ❌ NO STATUS CHECK
```

**Analisis**:
- ⚠️ **TIDAK ada status verification** untuk admin
- ⚠️ Admin dengan status menunggu_verifikasi TETAP bisa login
- ✅ Query khusus role='admin' mencegah user lain login sebagai admin

---

### 5. CONTROLLER AUTHORIZATION

#### A. DashboardController
**File**: [app/Http/Controllers/DashboardController.php](app/Http/Controllers/DashboardController.php)

**Observations**:
- ❌ **Tidak ada authorization check di dalam method**
- ❌ Tidak ada `$this->authorize()` atau Gate check
- ❌ Tidak ada `abort_if()` untuk double-check role
- Semua bergantung middleware di routes

**Potential Risk**:
```php
public function admin()
{
    // NO AUTHORIZATION CHECK HERE
    // Jika middleware bypass, method ini tetap eksekusi
    
    $statusCounts = RepositoryDocument::query()->selectRaw(...);
    return view('dashboards.admin', [...]);
}
```

#### B. AdminUserController & AdminDocumentController
- ⚠️ **Middleware-only protection** di routes level
- ❌ Tidak ada controller-level authorization
- Contoh: `AdminUserController@updateStatus()` tidak verify admin role lagi

---

### 6. VIEW AUTHORIZATION

#### A. Admin Layout
**File**: [resources/views/layouts/admin.blade.php](resources/views/layouts/admin.blade.php)

**Observations**:
- ❌ **Tidak ada @can/@cannot check**
- Navbar dan menu dibuild tanpa authorization protection
- Jika middleware gagal, layout tetap di-render

**Code**:
```php
<nav class="admin-menu">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.data.mahasiswa') }}">Data Mahasiswa</a>
    <!-- NO @can('admin') CHECK -->
</nav>
```

#### B. Public Navigation
**File**: [resources/views/layouts/app.blade.php](resources/views/layouts/app.blade.php#L21-L30)

**Code**:
```php
@auth
    <a href="{{ route(auth()->user()->role.'.dashboard') }}">Dashboard</a>
    @if (auth()->user()->role === 'admin')
        <a href="{{ route('admin.users.pending') }}">Verifikasi Akun</a>
    @endif
@endauth
```

**Observations**:
- ✅ Menggunakan `@auth` untuk user check
- ⚠️ Role check hanya untuk HIDING UI, bukan protecting data
- ❌ Tidak ada authorization protection jika akses langsung via URL

---

### 7. ROLE-BASED AUTHORIZATION MODEL

#### Current Implementation
**Type**: String-based Role Model (NOT Policy-based)

```
users.role (varchar) → 'admin' | 'dosen' | 'mahasiswa'
                      ↓
                 RoleMiddleware → in_array($role, $allowed)
                      ↓
                  Abort(403) atau Allow
```

**Characteristics**:
- ❌ Tidak menggunakan Laravel Policy
- ❌ Tidak menggunakan Gate::define
- ❌ Tidak ada centralized authorization logic
- ✅ Simple string comparison
- ⚠️ Tidak flexible untuk complex permission rules

#### Missing Authorization Features
- ❌ Permissions (fine-grained access control)
- ❌ Resource-based authorization (ability check per resource)
- ❌ Action-based authorization (who can do what action)
- ❌ Audit logging untuk access attempts

---

## ⚠️ SECURITY ISSUES IDENTIFIED

### Issue #1: CRITICAL - Admin Status Not Verified
**Severity**: 🔴 HIGH  
**Location**: [EnsureAccountIsActive.php](app/Http/Middleware/EnsureAccountIsActive.php#L16)

**Problem**:
```php
if ($user && $user->role !== 'admin' && $user->status_akun !== 'aktif')
```
Admin tidak di-check status akunnya.

**Impact**:
- ❌ Unverified admin bisa login
- ❌ Unverified admin bisa access all admin features
- ❌ No audit trail untuk admin status verification

**Fix Required**:
```php
if ($user && $user->status_akun !== 'aktif') {
    if ($user->role === 'admin') {
        // Require additional verification for admin
        abort(403, 'Admin akun belum diverifikasi.');
    }
    Auth::logout();
    // ...
}
```

---

### Issue #2: HIGH - No Controller-Level Authorization
**Severity**: 🟠 HIGH  
**Location**: All controllers (DashboardController, AdminUserController, etc.)

**Problem**:
- Semua authorization hanya di middleware
- Tidak ada double-check di controller method
- Jika middleware di-bypass, akses tetap granted

**Impact**:
- ❌ Vulnerability terhadap middleware bypass
- ❌ Tidak ada authorization audit trail
- ❌ Developer dapat forget authorization check

**Fix Required**:
```php
// Di controller method
public function admin()
{
    $this->authorize('admin'); // Using Policy
    // atau
    abort_unless(auth()->user()->isAdmin(), 403);
    
    // method logic
}
```

---

### Issue #3: MEDIUM - No View-Level Authorization
**Severity**: 🟡 MEDIUM  
**Location**: All blade views

**Problem**:
- Views tidak punya @can/@cannot check
- Hanya rely pada middleware
- If data accidentally passed, non-admin dapat see data

**Impact**:
- ⚠️ Potential data leak jika logic error
- ⚠️ No defense-in-depth

**Fix Required**:
```php
@can('admin')
    <section class="admin-panel">
        <!-- admin content -->
    </section>
@endcan
```

---

### Issue #4: HIGH - Missing Audit Logging
**Severity**: 🟠 HIGH  
**Location**: No audit logging found

**Problem**:
- Tidak ada logging untuk akses admin
- Tidak ada tracking siapa access apa
- Tidak ada audit trail untuk verification process

**Impact**:
- ❌ No accountability
- ❌ No forensic trail untuk security incident

---

## ✅ SECURE IMPLEMENTATIONS

### 1. Role Validation di Login ✅
- Both standard & admin login validate role
- Prevent user login sebagai role lain

### 2. Middleware Registration ✅
- Properly aliased di bootstrap/app.php
- Applied di route groups

### 3. RoleMiddleware Implementation ✅
- Strict type comparison dengan in_array(..., true)
- Proper abort(403) response

### 4. Session Regeneration ✅
- Di-regenerate setelah login
- Prevent session fixation

---

## 🎯 RECOMMENDATIONS

### Priority 1: CRITICAL (Do Immediately)
1. **Fix Admin Status Verification**
   - Force status_akun check untuk semua roles, including admin
   - Create separate admin verification flow

2. **Add Controller Authorization**
   - Implement Laravel Policies atau Gate
   - Add `$this->authorize()` di setiap admin method

3. **Add Audit Logging**
   - Log semua admin access & actions
   - Log login attempts (success & failure)
   - Log data modifications

### Priority 2: HIGH (Do Soon)
4. **Add View-Level Authorization**
   - Wrap admin content dengan @can/@cannot
   - Implement Blade authorization helpers

5. **Implement Laravel Policies**
   - Create AdminPolicy, UserPolicy, DocumentPolicy
   - Centralize authorization logic

6. **Add Permission System** (Optional)
   - Move from role-based ke permission-based
   - Allow fine-grained access control

### Priority 3: MEDIUM
7. **Add CSRF Protection**
   - Ensure @csrf di semua forms

8. **API Security**
   - If API endpoints ada, implement proper auth

9. **Rate Limiting**
   - Add rate limiting untuk login endpoints

---

## 📝 IMPLEMENTATION ROADMAP

### Step 1: Immediate Fixes (1-2 jam)
```bash
# 1. Fix admin status check
# File: app/Http/Middleware/EnsureAccountIsActive.php

# 2. Add controller authorization
# File: app/Http/Controllers/AdminUserController.php
# File: app/Http/Controllers/AdminDocumentController.php
# File: app/Http/Controllers/DashboardController.php

# 3. Create Policies
# File: app/Policies/AdminPolicy.php

# 4. Add audit logging
# File: app/Services/AuditLogger.php
```

### Step 2: Enhance Authorization (2-4 jam)
```bash
# 1. Implement full Policy system
# 2. Add permission checking
# 3. Add view authorization
```

### Step 3: Monitoring & Testing (ongoing)
```bash
# 1. Add authorization tests
# 2. Add security tests
# 3. Add load testing
```

---

## 📚 CODE REFERENCES

| File | Line | Issue |
|------|------|-------|
| [EnsureAccountIsActive.php](app/Http/Middleware/EnsureAccountIsActive.php#L16) | 16 | Admin status not checked |
| [DashboardController.php](app/Http/Controllers/DashboardController.php#L12) | 12 | No controller authorization |
| [AdminUserController.php](app/Http/Controllers/AdminUserController.php#L7) | 7+ | No controller authorization |
| [layouts/admin.blade.php](resources/views/layouts/admin.blade.php#L20) | 20+ | No view authorization |
| [AuthController.php](app/Http/Controllers/AuthController.php#L61) | 61 | Admin login no status check |

---

## 🔐 SECURITY CHECKLIST

- [ ] Admin status verification implemented
- [ ] Controller-level authorization checks added
- [ ] Policies created and registered
- [ ] View-level authorization (@can) implemented
- [ ] Audit logging implemented
- [ ] Authorization tests written
- [ ] Security tests passing
- [ ] Rate limiting implemented
- [ ] CSRF protection verified
- [ ] API endpoints protected (if applicable)

---

**Report Generated**: 9 July 2026  
**Analyzed By**: Code Analyzer  
**Status**: Ready for Implementation
