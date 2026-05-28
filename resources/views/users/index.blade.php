@extends('layouts.app')
@section('title', 'Users')
@section('page-title', 'Users Management')

@section('content')
<div class="card">
    <div class="p-4">
        <div class="card-header-bar">
            <div>
                <h6 style="margin:0;font-weight:800"><i class="bi bi-people-fill me-2" style="color:var(--primary)"></i>All Users</h6>
                <div style="font-size:.78rem;color:#888;margin-top:2px">{{ $users->count() }} total accounts</div>
            </div>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-plus-lg me-1"></i>Add User
            </button>
        </div>

        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $user)
                    <tr>
                        <td style="color:#bbb;font-size:.8rem">{{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                @if($user->profile_picture)
                                    <img src="{{ asset('storage/'.$user->profile_picture) }}"
                                         style="width:38px;height:38px;border-radius:50%;object-fit:cover;border:2px solid #f0ebe5">
                                @else
                                    <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#ff6b35,#ff9a3c);color:#fff;font-weight:800;font-size:.85rem;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                        {{ strtoupper(substr($user->name,0,1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight:700;font-size:.88rem">{{ $user->name }}</div>
                                    @if($user->id === auth()->id())
                                        <div style="font-size:.72rem;color:var(--primary);font-weight:700">You</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td style="color:#666;font-size:.85rem">{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span style="background:#fff3ee;color:#ff6b35;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:800">
                                    <i class="bi bi-shield-fill me-1"></i>Admin
                                </span>
                            @else
                                <span style="background:#f0f0f0;color:#666;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:800">
                                    <i class="bi bi-person-fill me-1"></i>User
                                </span>
                            @endif
                        </td>
                        <td style="color:#888;font-size:.82rem">{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm"
                                    style="background:#f0f7ff;color:#2980b9;border:none;border-radius:8px;padding:5px 10px"
                                    onclick="openEdit({{ $user->id }},'{{ addslashes($user->name) }}','{{ $user->email }}','{{ $user->role }}')">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline"
                                      onsubmit="return confirm('Delete {{ addslashes($user->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm" style="background:#fff0f0;color:#e74c3c;border:none;border-radius:8px;padding:5px 10px">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5" style="color:#bbb">
                            <div style="font-size:2.5rem">👥</div>
                            <div style="font-weight:700;margin-top:8px">No users found</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add User Modal --}}
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ route('users.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus-fill me-2"></i>Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Maria Santos" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="user@example.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Min. 6 characters" required minlength="6">
                </div>
                <div class="mb-1">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm" style="background:#f0ebe5;border:none;border-radius:8px;font-weight:700;padding:8px 18px" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-check-lg me-1"></i>Add User
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Edit User Modal --}}
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" id="editUserForm" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-fill me-2"></i>Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" id="editName" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" id="editEmail" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">New Password <span style="color:#bbb;font-weight:400">(leave blank to keep)</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Min. 6 characters" minlength="6">
                </div>
                <div class="mb-1">
                    <label class="form-label">Role</label>
                    <select name="role" id="editRole" class="form-select">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm" style="background:#f0ebe5;border:none;border-radius:8px;font-weight:700;padding:8px 18px" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-check-lg me-1"></i>Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openEdit(id, name, email, role) {
    document.getElementById('editUserForm').action = '/users/' + id;
    document.getElementById('editName').value  = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editRole').value  = role;
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}
@if($errors->any())
new bootstrap.Modal(document.getElementById('addUserModal')).show();
@endif
</script>
@endsection
