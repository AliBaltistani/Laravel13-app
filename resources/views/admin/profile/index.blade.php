@extends('layouts.admin')
@section('title', 'My Profile')
@section('breadcrumb')<li class="active">My Profile</li>@endsection

@section('admin-content')
<div class="row">
    <div class="col-lg-4">
        {{-- Avatar Card --}}
        <div class="admin-card mb-4">
            <div class="card-body text-center py-4">
                <div class="mb-3">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="Avatar"
                             style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #eef0f3;">
                    @else
                        <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #0d6efd 0%, #6366f1 100%);
                                    display: inline-flex; align-items: center; justify-content: center; font-size: 48px; color: #fff; font-weight: 700;">
                            {{ strtoupper(substr($user->first_name ?? $user->name ?? 'A', 0, 1)) }}
                        </div>
                    @endif
                </div>
                <h5 class="mb-1" style="color: #1e2a3a; font-weight: 600;">{{ $user->full_name }}</h5>
                <p class="text-muted mb-2" style="font-size: 13px;">{{ $user->email }}</p>
                <span class="badge" style="background: rgba(13,110,253,0.1); color: #0d6efd; padding: 5px 14px; border-radius: 20px; font-size: 12px; font-weight: 600;">
                    {{ ucfirst(str_replace('_', ' ', $user->roles->first()?->name ?? 'Admin')) }}
                </span>

                @if($user->avatar)
                    <form method="POST" action="{{ route('admin.profile.remove-avatar') }}" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Remove your avatar?')">
                            <i class="fas fa-trash mr-1"></i> Remove Avatar
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="admin-card">
            <div class="card-header"><h5>Quick Links</h5></div>
            <div class="card-body p-0">
                <a href="{{ route('admin.profile.password') }}" class="d-flex align-items-center px-3 py-3 border-bottom text-decoration-none" style="color: #495057;">
                    <i class="fas fa-key mr-3 text-muted"></i>
                    <span>Change Password</span>
                    <i class="fas fa-chevron-right ml-auto text-muted" style="font-size: 11px;"></i>
                </a>
                <a href="{{ route('admin.settings.index') }}" class="d-flex align-items-center px-3 py-3 text-decoration-none" style="color: #495057;">
                    <i class="fas fa-cog mr-3 text-muted"></i>
                    <span>Site Settings</span>
                    <i class="fas fa-chevron-right ml-auto text-muted" style="font-size: 11px;"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        {{-- Profile Form --}}
        <div class="admin-card">
            <div class="card-header">
                <h5><i class="fas fa-user-edit mr-2"></i>Profile Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name" class="font-weight-bold">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                       id="first_name" name="first_name"
                                       value="{{ old('first_name', $user->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name" class="font-weight-bold">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                       id="last_name" name="last_name"
                                       value="{{ old('last_name', $user->last_name) }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="font-weight-bold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="font-weight-bold">Phone Number</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                               id="phone" name="phone"
                               value="{{ old('phone', $user->phone) }}"
                               placeholder="+1 234 567 890">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="avatar" class="font-weight-bold">Profile Picture</label>
                        <input type="file" class="form-control-file @error('avatar') is-invalid @enderror"
                               id="avatar" name="avatar" accept="image/*">
                        <small class="text-muted">Max 2MB. Accepted: JPG, PNG, WebP</small>
                        @error('avatar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Account Info --}}
        <div class="admin-card mt-4">
            <div class="card-header"><h5><i class="fas fa-info-circle mr-2"></i>Account Information</h5></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p class="text-muted mb-1" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Member Since</p>
                        <p class="font-weight-bold">{{ $user->created_at?->format('M d, Y') ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted mb-1" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Last Updated</p>
                        <p class="font-weight-bold">{{ $user->updated_at?->format('M d, Y h:i A') ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted mb-1" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">Account Status</p>
                        <p>
                            @if($user->is_active)
                                <span class="badge badge-delivered">Active</span>
                            @else
                                <span class="badge badge-cancelled">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
