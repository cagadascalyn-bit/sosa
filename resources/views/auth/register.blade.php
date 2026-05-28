@extends('layouts.auth')
@section('title', 'Register')

@section('content')
<h4>Create your account 🍽️</h4>
<p class="sub">Join RecipeBook and start saving your recipes</p>

<form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Full Name</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
            <input type="text" name="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name') }}" placeholder="Juan dela Cruz" required autofocus>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
            <input type="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="you@example.com" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
            <input type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Min. 6 characters" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label">Confirm Password</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
            <input type="password" name="password_confirmation"
                   class="form-control" placeholder="Repeat password" required>
        </div>
    </div>

    <button type="submit" class="btn-auth">
        <i class="bi bi-person-plus-fill me-2"></i>Create Account
    </button>
</form>

<div class="divider"><span>or</span></div>
<p class="text-center mb-0" style="font-size:.88rem">
    Already have an account? <a href="{{ route('login') }}" class="auth-link">Sign in here</a>
</p>
@endsection
