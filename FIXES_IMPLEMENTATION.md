# 🔧 RECOMMENDED FIXES & IMPLEMENTATION

## CRITICAL FIX #1: Admin Status Verification

### Current Code (VULNERABLE)
**File**: `app/Http/Middleware/EnsureAccountIsActive.php`

```php
if ($user && $user->role !== 'admin' && $user->status_akun !== 'aktif') {
    Auth::logout();
    // ...
}
// PROBLEM: Admin tidak di-check status-nya!
```

### Fixed Code
```php
if ($user && $user->status_akun !== 'aktif') {
    Auth::logout();
    return redirect()->route('login')->withErrors([
        'login' => 'Akun Anda masih menunggu verifikasi Admin. Silakan tunggu atau hubungi Admin.',
    ]);
}
```

### Implementation Steps
1. Open `app/Http/Middleware/EnsureAccountIsActive.php`
2. Replace the condition di line 16
3. Test dengan admin user yang status-nya tidak aktif

---

## CRITICAL FIX #2: Add Controller Authorization

### Option A: Using Policy (Recommended)

#### Step 1: Create AdminPolicy
**File**: `app/Policies/AdminPolicy.php`

```php
<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    public function admin(User $user): bool
    {
        return $user->role === 'admin' && $user->status_akun === 'aktif';
    }

    public function viewDashboard(User $user): bool
    {
        return $this->admin($user);
    }

    public function manageUsers(User $user): bool
    {
        return $this->admin($user);
    }

    public function manageDocuments(User $user): bool
    {
        return $this->admin($user);
    }

    public function viewReports(User $user): bool
    {
        return $this->admin($user);
    }
}
```

#### Step 2: Register Policy di AppServiceProvider
**File**: `app/Providers/AppServiceProvider.php`

```php
<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\AdminPolicy;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => AdminPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerPolicies();
    }

    private function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            \Illuminate\Support\Facades\Gate::policy($model, $policy);
        }
    }
}
```

#### Step 3: Use in Controllers
**File**: `app/Http/Controllers/DashboardController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Policies\AdminPolicy;
use App\Models\ProgramStudi;
use App\Models\RepositoryDocument;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        // ADD THIS LINE - Double check authorization
        $this->authorize('admin', Auth::user());

        $statusCounts = RepositoryDocument::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('dashboards.admin', [
            // ... existing code
        ]);
    }

    public function dosen()
    {
        // Verify user is dosen
        abort_unless(Auth::user()->role === 'dosen', 403);

        return view('dashboards.dosen', [
            // ... existing code
        ]);
    }

    public function mahasiswa()
    {
        // Verify user is mahasiswa
        abort_unless(Auth::user()->role === 'mahasiswa', 403);

        return view('dashboards.mahasiswa', [
            // ... existing code
        ]);
    }
}
```

### Option B: Using Simple abort_unless (Quick Fix)

```php
public function admin()
{
    // Quick authorization check
    abort_unless(
        auth()->user()?->role === 'admin' && auth()->user()?->status_akun === 'aktif',
        403,
        'Anda tidak memiliki akses ke halaman ini.'
    );

    // ... rest of method
}
```

---

## HIGH FIX #3: Add View-Level Authorization

### Before (VULNERABLE)
```php
<!-- resources/views/layouts/admin.blade.php -->
<nav class="admin-menu">
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('admin.data.mahasiswa') }}">Data Mahasiswa</a>
</nav>
```

### After (PROTECTED)
```php
<!-- resources/views/layouts/admin.blade.php -->
@can('admin', auth()->user())
    <nav class="admin-menu">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.data.mahasiswa') }}">Data Mahasiswa</a>
    </nav>
@endcan
```

### Alternative using middleware in layout
```php
@unless(auth()->check() && auth()->user()->role === 'admin')
    <!-- Redirect or error message -->
    @php abort(403, 'Access Denied'); @endphp
@endunless
```

---

## MEDIUM FIX #4: Add Audit Logging

### Create Audit Service
**File**: `app/Services/AuditLogger.php`

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class AuditLogger
{
    public static function logAdminAccess($user, $action, $details = [])
    {
        Log::channel('audit')->info('Admin Access', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toDateTimeString(),
            'details' => $details,
        ]);
    }

    public static function logLogin($email, $success, $role = null)
    {
        Log::channel('audit')->info('Login Attempt', [
            'email' => $email,
            'success' => $success,
            'role' => $role,
            'ip_address' => request()->ip(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    public static function logDocumentAction($user, $document, $action)
    {
        Log::channel('audit')->info('Document Action', [
            'user_id' => $user->id,
            'document_id' => $document->id,
            'action' => $action,
            'timestamp' => now()->toDateTimeString(),
        ]);
    }
}
```

### Configure Log Channel
**File**: `config/logging.php`

```php
'channels' => [
    'audit' => [
        'driver' => 'single',
        'path' => storage_path('logs/audit.log'),
        'level' => 'info',
    ],
],
```

### Use in Controller
**File**: `app/Http/Controllers/AdminUserController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Services\AuditLogger;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function updateStatus(Request $request, User $user)
    {
        abort_if($user->role === 'admin', 403, 'Admin tidak diverifikasi dari halaman publik.');

        $oldStatus = $user->status_akun;
        $data = $request->validate([...]);

        $user->update([...]);

        // Log the action
        AuditLogger::logAdminAccess(auth()->user(), 'update_user_status', [
            'target_user_id' => $user->id,
            'old_status' => $oldStatus,
            'new_status' => $user->status_akun,
        ]);

        return back()->with('status', 'Status akun '.$user->name.' berhasil diperbarui.');
    }
}
```

---

## Implementation Checklist

```markdown
## Phase 1: Critical Fixes (2-3 hours)
- [ ] Fix EnsureAccountIsActive middleware
- [ ] Add abort_unless in DashboardController
- [ ] Test with unverified admin

## Phase 2: Proper Authorization (2-4 hours)
- [ ] Create AdminPolicy class
- [ ] Register policy in AppServiceProvider
- [ ] Update all admin controllers to use policy
- [ ] Add tests for authorization

## Phase 3: View & Logging (1-2 hours)
- [ ] Add @can checks in views
- [ ] Create AuditLogger service
- [ ] Configure audit log channel
- [ ] Log admin actions

## Phase 4: Testing & Verification (1-2 hours)
- [ ] Write authorization tests
- [ ] Test all role combinations
- [ ] Verify audit logs
- [ ] Security review
```

---

## Testing the Fixes

### Test Admin Status Check
```php
// Create test
public function test_unverified_admin_cannot_access_dashboard()
{
    $admin = User::create([
        'email' => 'admin@test.com',
        'role' => 'admin',
        'status_akun' => 'menunggu_verifikasi', // Unverified
        'password' => bcrypt('password'),
    ]);

    $response = $this->actingAs($admin)->get('/admin/dashboard');
    $response->assertRedirect('/admin/login');
}
```

### Test Controller Authorization
```php
public function test_non_admin_cannot_access_admin_dashboard()
{
    $user = User::create([
        'email' => 'dosen@test.com',
        'role' => 'dosen',
        'status_akun' => 'aktif',
        'password' => bcrypt('password'),
    ]);

    $response = $this->actingAs($user)->get('/admin/dashboard');
    $response->assertStatus(403);
}
```

---

## Quick Reference

| Issue | Severity | File | Fix |
|-------|----------|------|-----|
| Admin status not checked | 🔴 CRITICAL | EnsureAccountIsActive.php | Remove role check from condition |
| No controller auth | 🟠 HIGH | DashboardController.php | Add $this->authorize() or abort_unless() |
| No view auth | 🟡 MEDIUM | layouts/admin.blade.php | Add @can checks |
| No audit logging | 🟡 MEDIUM | Various controllers | Create AuditLogger service |

---

**Estimated Implementation Time**: 6-10 hours  
**Testing Time**: 2-3 hours  
**Total**: 8-13 hours for full implementation
