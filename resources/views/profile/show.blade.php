@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="row g-4">

    {{-- Left: Profile Card --}}
    <div class="col-lg-4">
        <div class="card overflow-hidden">
            {{-- Cover --}}
            <div style="height:90px;background:linear-gradient(135deg,#1c1c2e,#3d2200);position:relative">
                <div style="position:absolute;right:16px;bottom:-20px;font-size:3rem;opacity:.15">🍳</div>
            </div>
            {{-- Avatar --}}
            <div class="px-4 pb-4">
                <div style="margin-top:-36px;margin-bottom:12px">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/'.$user->profile_picture) }}"
                             style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:4px solid #fff;box-shadow:0 4px 16px rgba(0,0,0,.12)">
                    @else
                        <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#ff6b35,#ff9a3c);color:#fff;font-weight:800;font-size:1.6rem;display:flex;align-items:center;justify-content:center;border:4px solid #fff;box-shadow:0 4px 16px rgba(255,107,53,.3)">
                            {{ strtoupper(substr($user->name,0,1)) }}
                        </div>
                    @endif
                </div>
                <div style="font-weight:800;font-size:1.1rem">{{ $user->name }}</div>
                <div style="color:#888;font-size:.85rem;margin-bottom:10px">{{ $user->email }}</div>
                <span style="background:{{ $user->role==='admin' ? '#fff3ee' : '#f0f0f0' }};color:{{ $user->role==='admin' ? '#ff6b35' : '#666' }};padding:4px 12px;border-radius:20px;font-size:.72rem;font-weight:800">
                    @if($user->role === 'admin')<i class="bi bi-shield-fill me-1"></i>@else<i class="bi bi-person-fill me-1"></i>@endif
                    {{ ucfirst($user->role) }}
                </span>

                <hr style="border-color:#f0ebe5;margin:16px 0">

                <div class="profile-info-list">
                    <div class="pi-item">
                        <i class="bi bi-telephone-fill"></i>
                        <span>{{ $user->phone ?? 'Not set' }}</span>
                    </div>
                    <div class="pi-item">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>{{ $user->address ?? 'Not set' }}</span>
                    </div>
                    <div class="pi-item">
                        <i class="bi bi-gender-ambiguous"></i>
                        <span>{{ $user->gender ?? 'Not set' }}</span>
                    </div>
                    <div class="pi-item">
                        <i class="bi bi-calendar-check-fill"></i>
                        <span>Joined {{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="pi-item">
                        <i class="bi bi-journal-richtext" style="color:var(--primary)"></i>
                        <span><strong style="color:var(--primary)">{{ $user->recipes()->count() }}</strong> recipes added</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Edit Form --}}
    <div class="col-lg-8">
        <div class="card p-4">
            <div style="font-weight:800;font-size:1rem;margin-bottom:20px">
                <i class="bi bi-pencil-square me-2" style="color:var(--primary)"></i>Edit Profile
            </div>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="row g-3">
                    <div class="col-sm-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ old('phone', $user->phone) }}" placeholder="+63 9XX XXX XXXX">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select gender...</option>
                            <option {{ $user->gender==='Male'   ? 'selected':'' }}>Male</option>
                            <option {{ $user->gender==='Female' ? 'selected':'' }}>Female</option>
                            <option {{ $user->gender==='Other'  ? 'selected':'' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control"
                               value="{{ old('address', $user->address) }}" placeholder="Street, City, Province">
                    </div>

                    {{-- Profile Picture --}}
                    <div class="col-12">
                        <label class="form-label">Profile Picture</label>
                        <div class="d-flex align-items-center gap-3">
                            @if($user->profile_picture)
                                <img src="{{ asset('storage/'.$user->profile_picture) }}"
                                     style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid #f0ebe5">
                            @else
                                <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#ff6b35,#ff9a3c);color:#fff;font-weight:800;display:flex;align-items:center;justify-content:center">
                                    {{ strtoupper(substr($user->name,0,1)) }}
                                </div>
                            @endif
                            <input type="file" name="picture" class="form-control" accept="image/*" style="flex:1">
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="col-12">
                        <div style="border-top:1px solid #f0ebe5;padding-top:16px;font-weight:800;font-size:.85rem;color:#888">
                            <i class="bi bi-lock-fill me-2"></i>Change Password <span style="font-weight:400">(leave blank to keep current)</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Min. 6 characters">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password">
                    </div>

                    <div class="col-12 mt-2">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-2"></i>Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .profile-info-list { display: flex; flex-direction: column; gap: 10px; }
    .pi-item {
        display: flex; align-items: flex-start; gap: 10px;
        font-size: .85rem; color: #555;
    }
    .pi-item i { color: #bbb; margin-top: 2px; flex-shrink: 0; }
</style>
@endsection
