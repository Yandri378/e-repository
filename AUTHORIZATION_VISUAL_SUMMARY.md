# 🎯 VISUAL AUTHORIZATION FLOW & SUMMARY

## 1. LOGIN FLOW DIAGRAM

```
┌─────────────────────────────────────────────────────────────────┐
│                    ADMIN LOGIN FLOW                             │
│                  (CURRENT - VULNERABLE)                         │
└─────────────────────────────────────────────────────────────────┘

User Input (email, password)
        ↓
    ✓ Validate format
        ↓
    ✓ Find user where role='admin'
        ↓
    ✓ Attempt auth with email & password
        ↓
    ✓ Regenerate session
        ↓
    ✗ NO STATUS CHECK HERE ← VULNERABILITY!
        ↓
    Redirect to admin.dashboard
        ↓
    [EnsureAccountIsActive Middleware]
        ↓
    ✗ SKIP CHECK because user->role === 'admin' ← VULNERABILITY!
        ↓
    Access Granted to /admin/dashboard
    

┌─────────────────────────────────────────────────────────────────┐
│              MAHASISWA/DOSEN LOGIN FLOW                         │
│                    (SECURE)                                     │
└─────────────────────────────────────────────────────────────────┘

User Input (role, identifier, password)
        ↓
    ✓ Validate role in ['mahasiswa', 'dosen']
        ↓
    ✓ Find user by email/nim/nidn
        ↓
    ✓ Verify user->role === form->role
        ↓
    ✓ Attempt auth with email & password
        ↓
    ✓ Regenerate session
        ↓
    ✓ Verify status_akun === 'aktif'
        ↓
    Redirect to {role}.dashboard
        ↓
    [EnsureAccountIsActive Middleware]
        ↓
    ✓ Check status_akun !== 'aktif'
        ↓
    ✗ If not active → Logout & Redirect to login
        ↓
    Access Granted to {role}.dashboard
```

---

## 2. DASHBOARD ACCESS FLOW

```
Request to /admin/dashboard
        ↓
    [auth middleware] ← Check user authenticated
        ↓ ✓ User logged in
    [account.active middleware] ← Check account status
        ↓
    ⚠️ VULNERABILITY: Admin bypasses status check!
        ↓
    [role:admin middleware] ← Check user role
        ↓
    ✓ User role = 'admin'
        ↓
    DashboardController@admin()
        ↓
    ❌ NO AUTHORIZATION CHECK HERE
        ↓
    return view('dashboards.admin', $data)
        ↓
    [layouts/admin.blade.php]
        ↓
    ❌ NO @can CHECK HERE
        ↓
    Render admin dashboard


┌─────────────────────────────────────────────────────────────────┐
│     PROPOSED FLOW WITH FIXES (MULTI-LAYER)                      │
└─────────────────────────────────────────────────────────────────┘

Request to /admin/dashboard
        ↓
    [auth middleware] ✓
        ↓
    [account.active middleware] ✓ + FIX: Check status for ALL
        ↓
    [role:admin middleware] ✓
        ↓
    DashboardController@admin()
        ↓
    ✓ FIX: $this->authorize('admin', $user)
        ↓
    return view('dashboards.admin', $data)
        ↓
    [layouts/admin.blade.php]
        ↓
    ✓ FIX: @can('admin', auth()->user())
        ↓
    AuditLogger::log('admin_access', [...]) ✓ FIX
        ↓
    Render admin dashboard
```

---

## 3. MIDDLEWARE PROTECTION LAYERS

```
CURRENT IMPLEMENTATION
┌────────────────────────────────────────┐
│  1. Route Middleware                   │
│  ├─ auth                               │
│  ├─ account.active  ⚠️ ISSUE          │
│  └─ role:admin                         │
├────────────────────────────────────────┤
│  2. Controller Authorization           │
│  └─ NONE ❌                            │
├────────────────────────────────────────┤
│  3. View Authorization                 │
│  └─ NONE ❌ (only UI hiding)          │
├────────────────────────────────────────┤
│  4. Audit Logging                      │
│  └─ NONE ❌                            │
└────────────────────────────────────────┘


RECOMMENDED IMPLEMENTATION
┌────────────────────────────────────────┐
│  1. Route Middleware                   │
│  ├─ auth ✓                             │
│  ├─ account.active ✓ FIXED             │
│  └─ role:admin ✓                       │
├────────────────────────────────────────┤
│  2. Controller Authorization           │
│  └─ $this->authorize() ✓ ADDED        │
├────────────────────────────────────────┤
│  3. View Authorization                 │
│  └─ @can() @endcan ✓ ADDED            │
├────────────────────────────────────────┤
│  4. Audit Logging                      │
│  └─ AuditLogger ✓ ADDED               │
└────────────────────────────────────────┘
```

---

## 4. ADMIN STATUS CHECK COMPARISON

### CURRENT (VULNERABLE)
```
User Status Check Decision Tree
├─ Is user admin?
│  ├─ YES → SKIP status check ❌
│  │        └─ Access granted regardless
│  └─ NO → Check if status = 'aktif'
│          ├─ YES → Access granted ✓
│          └─ NO → Logout ✓
```

### FIXED (SECURE)
```
User Status Check Decision Tree
├─ Is status = 'aktif'?
│  ├─ YES → Continue ✓
│  └─ NO → Logout & Redirect ✓
│          (same for ALL roles)
```

---

## 5. AUTHORIZATION MATRIX

### CURRENT STATE
| Action | Admin (verified) | Admin (unverified) | Dosen (verified) | Dosen (unverified) | Mahasiswa (verified) | Mahasiswa (unverified) |
|--------|------------------|-------------------|-----------------|-------------------|----------------------|----------------------|
| Access /admin/dashboard | ✅ | ✅❌ VULNERABILITY | ❌ | ❌ | ❌ | ❌ |
| Access /dosen/dashboard | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ |
| Access /mahasiswa/dashboard | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ |
| Manage users | ✅ | ✅❌ VULNERABILITY | ❌ | ❌ | ❌ | ❌ |
| Manage documents | ✅ | ✅❌ VULNERABILITY | ❌ | ❌ | ❌ | ❌ |
| View reports | ✅ | ✅❌ VULNERABILITY | ❌ | ❌ | ❌ | ❌ |

### AFTER FIX
| Action | Admin (verified) | Admin (unverified) | Dosen (verified) | Dosen (unverified) | Mahasiswa (verified) | Mahasiswa (unverified) |
|--------|------------------|-------------------|-----------------|-------------------|----------------------|----------------------|
| Access /admin/dashboard | ✅ | ❌ FIXED | ❌ | ❌ | ❌ | ❌ |
| Access /dosen/dashboard | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ |
| Access /mahasiswa/dashboard | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ |
| Manage users | ✅ | ❌ FIXED | ❌ | ❌ | ❌ | ❌ |
| Manage documents | ✅ | ❌ FIXED | ❌ | ❌ | ❌ | ❌ |
| View reports | ✅ | ❌ FIXED | ❌ | ❌ | ❌ | ❌ |

---

## 6. CODE PROTECTION LAYERS

```
Without Fixes:
┌──────────────┐
│ Route Filter │ ← ✓ Middleware checked
├──────────────┤
│   Render     │ ← ❌ No check here
│   Response   │
├──────────────┤
│     View     │ ← ❌ No check here
│   Rendered   │
└──────────────┘

With Fixes (Defense in Depth):
┌──────────────────┐
│  Route Middleware │ ← ✓ Checked
├──────────────────┤
│    Controller     │ ← ✓ ADDED: $this->authorize()
├──────────────────┤
│  Business Logic   │
├──────────────────┤
│      View         │ ← ✓ ADDED: @can check
├──────────────────┤
│  Audit Logging    │ ← ✓ ADDED: Log actions
└──────────────────┘
```

---

## 7. RISK ASSESSMENT

### Current Implementation Risk Level: 🔴 HIGH

| Component | Risk | Reason |
|-----------|------|--------|
| **Admin Status Check** | 🔴 CRITICAL | Unverified admin dapat login |
| **Controller Auth** | 🟠 HIGH | No double-check, easy to bypass |
| **View Auth** | 🟡 MEDIUM | Data visible if logic fails |
| **Audit Trail** | 🟡 MEDIUM | No accountability |
| **Role Middleware** | 🟢 LOW | Properly implemented |
| **Route Middleware** | 🟢 LOW | Properly registered |

### After Implementation Risk Level: 🟢 LOW

All components properly protected with multi-layer authorization.

---

## 8. ATTACK SCENARIOS

### Scenario 1: Unverified Admin Login
**Current**: 🔴 VULNERABLE
- Admin account created but status = 'menunggu_verifikasi'
- Admin dapat login
- Admin dapat access /admin/dashboard
- Admin dapat manage users dan documents

**After Fix**: 🟢 PROTECTED
- Unverified admin logout saat access
- Redirect to login with error message

---

### Scenario 2: Middleware Bypass
**Current**: 🟠 RISK
- If middleware somehow bypassed
- No controller-level check
- No view-level check
- Access granted

**After Fix**: 🟢 PROTECTED
- 3 layers protect: middleware + controller + view
- Even if one bypassed, others catch it

---

### Scenario 3: Database Role Manipulation
**Current**: 🟠 RISK
- If attacker change `users.role = 'admin'` in database
- Can access admin dashboard
- Middleware only does string comparison

**After Fix**: 🟢 SAME RISK (requires stronger DB encryption)
- Still vulnerable at DB level
- Recommended: Add encrypted field for role, or use separate permissions table

---

## 9. QUICK REFERENCE TABLE

### What's Checked Where?

| Check | Route Middleware | Controller | View |
|-------|-----------------|-----------|------|
| User Authenticated | ✅ auth | ❌ | ❌ |
| Account Active | ⚠️ (only non-admin) | ❌ | ❌ |
| Role Match | ✅ role:* | ❌ | ❌ |
| Permission | ❌ | ❌ | ❌ |
| **AFTER FIX** |
| User Authenticated | ✅ auth | ❌ | ❌ |
| Account Active | ✅ (all) | ❌ | ❌ |
| Role Match | ✅ role:* | ✅ (double-check) | ✅ |
| Permission | ❌ | ✅ | ✅ |
| Audit Log | ❌ | ✅ | ❌ |

---

## 10. FILES TO MODIFY

| Priority | File | Type | Status |
|----------|------|------|--------|
| 🔴 P1 | app/Http/Middleware/EnsureAccountIsActive.php | Fix | ⏳ TODO |
| 🔴 P1 | app/Http/Controllers/DashboardController.php | Add | ⏳ TODO |
| 🟠 P2 | app/Policies/AdminPolicy.php | Create | ⏳ TODO |
| 🟠 P2 | app/Providers/AppServiceProvider.php | Add | ⏳ TODO |
| 🟠 P2 | app/Http/Controllers/AdminUserController.php | Update | ⏳ TODO |
| 🟠 P2 | app/Http/Controllers/AdminDocumentController.php | Update | ⏳ TODO |
| 🟡 P3 | resources/views/layouts/admin.blade.php | Update | ⏳ TODO |
| 🟡 P3 | app/Services/AuditLogger.php | Create | ⏳ TODO |
| 🟡 P3 | config/logging.php | Update | ⏳ TODO |
| 🟢 P4 | tests/Feature/AuthorizationTest.php | Create | ✅ DONE |

---

## KEY TAKEAWAYS

1. **Single Point of Failure**: All auth in middleware → vulnerability if bypassed
2. **Admin Bypass**: Admin status not checked → unverified admin can access
3. **No Defense in Depth**: Only 1 layer (middleware) protecting access
4. **No Audit Trail**: No logging of admin actions → no accountability
5. **Middleware-Only**: Developers might forget auth checks in new features

**Solution**: Multi-layer authorization (route + controller + view + logging)
