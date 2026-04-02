@extends('layouts.account')

@section('meta_title', 'My Addresses - ' . Setting::get('general.site_name', 'Porto Shop'))

@php $title = 'Addresses'; @endphp

@section('account-content')
<div class="tab-pane fade show active">
    <h3 class="account-sub-title d-none d-md-block mb-3">Addresses</h3>

    <div class="row">
        @foreach($addresses as $address)
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <h6 class="card-subtitle mb-2 text-muted text-uppercase">{{ $address->label }}</h6>
                        <div>
                            @if($address->is_default_shipping) <span class="badge badge-info">Default Shipping</span> @endif
                            @if($address->is_default_billing) <span class="badge badge-success">Default Billing</span> @endif
                        </div>
                    </div>
                    <p class="card-text mb-2">
                        <strong>{{ $address->first_name }} {{ $address->last_name }}</strong><br>
                        {{ $address->address_line1 }}<br>
                        @if($address->address_line2) {{ $address->address_line2 }}<br> @endif
                        {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}<br>
                        @if($address->phone) Phone: {{ $address->phone }} @endif
                    </p>
                    <div class="d-flex flex-wrap">
                        @if(!$address->is_default_shipping)
                            <a href="{{ route('account.addresses.default', [$address, 'shipping']) }}" class="btn btn-sm btn-outline-info mr-1 mb-1">Set Default Shipping</a>
                        @endif
                        @if(!$address->is_default_billing)
                            <a href="{{ route('account.addresses.default', [$address, 'billing']) }}" class="btn btn-sm btn-outline-success mr-1 mb-1">Set Default Billing</a>
                        @endif
                        <form action="{{ route('account.addresses.destroy', $address) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this address?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger mb-1">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Add New Address Form --}}
    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">Add New Address</h5>
            <form action="{{ route('account.addresses.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Label <span class="required">*</span></label>
                        <select name="label" class="form-control" required>
                            <option value="home">Home</option>
                            <option value="work">Work</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>First Name <span class="required">*</span></label>
                        <input type="text" class="form-control" name="first_name" value="{{ old('first_name', Auth::user()->first_name) }}" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Last Name <span class="required">*</span></label>
                        <input type="text" class="form-control" name="last_name" value="{{ old('last_name', Auth::user()->last_name) }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Address Line 1 <span class="required">*</span></label>
                    <input type="text" class="form-control" name="address_line1" value="{{ old('address_line1') }}" required>
                </div>
                <div class="form-group">
                    <label>Address Line 2</label>
                    <input type="text" class="form-control" name="address_line2" value="{{ old('address_line2') }}">
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>City <span class="required">*</span></label>
                        <input type="text" class="form-control" name="city" value="{{ old('city') }}" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>State <span class="required">*</span></label>
                        <input type="text" class="form-control" name="state" value="{{ old('state') }}" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Postal Code <span class="required">*</span></label>
                        <input type="text" class="form-control" name="postal_code" value="{{ old('postal_code') }}" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" class="form-control" name="phone" value="{{ old('phone') }}">
                </div>
                <button type="submit" class="btn btn-dark">Save Address</button>
            </form>
        </div>
    </div>
</div>
@endsection
