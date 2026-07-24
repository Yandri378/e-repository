@extends('layouts.app')

@section('title', 'Login Admin')

@section('content')
<section class="auth-shell admin-auth-shell">
    <form method="POST" action="{{ route('admin.login.store') }}" class="auth-card admin-login-card">
        @csrf
        <p class="eyebrow">Akses Internal</p>
        <h1>Login Admin</h1>
        <p class="auth-intro">Halaman ini khusus admin repository kampus.</p>
        @if ($errors->any())
            <div class="error-box">{{ $errors->first() }}</div>
        @endif
        <label>Email Admin
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
        </label>
        <label>Password
            <input type="password" name="password" required>
        </label>
        <label class="check-line">
            <input type="checkbox" name="remember" value="1"> Ingat saya
        </label>
        <button type="submit" class="btn primary full">Masuk Panel Admin</button>
    </form>
</section>
@endsection
