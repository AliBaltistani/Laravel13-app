@extends('layouts.account')

@section('meta_title', 'Account Details - ' . Setting::get('general.site_name', 'Porto Shop'))

@php $title = 'Account Details'; @endphp

@section('account-content')
<div class="tab-pane fade show active">
    <h3 class="account-sub-title d-none d-md-block mb-3">Account Details</h3>

    {{-- Profile Form --}}
    <form action="{{ route('account.profile.update') }}" method="POST">
        @csrf @method('PUT')
        <div class="row">
            <div class="col-md-6 form-group">
                <label>First Name <span class="required">*</span></label>
                <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                @error('first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-6 form-group">
                <label>Last Name <span class="required">*</span></label>
                <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                @error('last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label>Email Address <span class="required">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-6 form-group">
                <label>Phone</label>
                <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $user->phone) }}">
                @error('phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
        </div>
        <button type="submit" class="btn btn-dark">Save Changes</button>
    </form>

    <hr class="my-4">

    {{-- Password Change Form --}}
    <h3 class="account-sub-title mt-3 mb-3">Change Password</h3>
    <form action="{{ route('account.password.update') }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Current Password <span class="required">*</span></label>
            <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required>
            @error('current_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>
        <div class="row">
            <div class="col-md-6 form-group">
                <label>New Password <span class="required">*</span></label>
                <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" required>
                @error('new_password') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-6 form-group">
                <label>Confirm New Password <span class="required">*</span></label>
                <input type="password" class="form-control" name="new_password_confirmation" required>
            </div>
        </div>
        <button type="submit" class="btn btn-dark">Change Password</button>
    </form>
</div>
@endsection
