@extends('layouts.admin')
@section('title', 'Change Password')
@section('breadcrumb')
    <li><a href="{{ route('admin.profile') }}">Profile</a></li>
    <li class="active">Change Password</li>
@endsection

@section('admin-content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="admin-card">
            <div class="card-header">
                <h5><i class="fas fa-key mr-2"></i>Change Password</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <div style="background: rgba(13,110,253,0.05); border-radius: 10px; padding: 16px; border: 1px solid rgba(13,110,253,0.1);">
                        <p class="mb-0" style="color: #495057; font-size: 13px;">
                            <i class="fas fa-info-circle text-primary mr-1"></i>
                            Make sure your new password is at least 8 characters long and includes a mix of letters and numbers for better security.
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="current_password" class="font-weight-bold">Current Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                   id="current_password" name="current_password" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="toggleField('current_password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        @error('current_password')
                            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="font-weight-bold">New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required minlength="8">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="toggleField('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        @error('password')
                            <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="font-weight-bold">Confirm New Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control"
                                   id="password_confirmation" name="password_confirmation" required minlength="8">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="toggleField('password_confirmation')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.profile') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Profile
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-lock mr-1"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleField(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = field.nextElementSibling.querySelector('i');
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endpush
@endsection
