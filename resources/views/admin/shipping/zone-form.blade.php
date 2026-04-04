@extends('layouts.admin')
@section('title', ($isEdit ? 'Edit' : 'Create') . ' Shipping Zone')
@section('breadcrumb')
<li><a href="{{ route('admin.shipping-zones.index') }}">Shipping</a></li>
<li class="active">{{ $isEdit ? 'Edit Zone' : 'New Zone' }}</li>
@endsection

@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">{{ $isEdit ? 'Edit Zone: ' . $zone->name : 'Create Shipping Zone' }}</h4>
    <a href="{{ route('admin.shipping-zones.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
</div>

<form method="POST" action="{{ $isEdit ? route('admin.shipping-zones.update', $zone) : route('admin.shipping-zones.store') }}">
    @csrf
    @if($isEdit) @method('PUT') @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="admin-card">
        <div class="card-header"><h5>Zone Details</h5></div>
        <div class="card-body">
            <div class="form-group">
                <label for="name">Zone Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $zone->name) }}" required placeholder="e.g. Domestic, Europe, Worldwide">
            </div>
            <div class="form-group mb-0">
                <label>Countries <span class="text-danger">*</span></label>
                <p class="text-muted small mb-2">Select the countries that belong to this shipping zone. Hold Ctrl/Cmd to select multiple.</p>
                @php
                    $selectedCountries = old('countries', $isEdit ? (is_array($zone->countries) ? $zone->countries : json_decode($zone->countries, true) ?? []) : []);
                @endphp
                <select name="countries[]" class="form-control" multiple size="12" required>
                    @foreach(\App\Models\Country::orderBy('name')->get() as $country)
                    <option value="{{ $country->iso_code }}" {{ in_array($country->iso_code, $selectedCountries) ? 'selected' : '' }}>
                        {{ $country->name }} ({{ $country->iso_code }})
                    </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i> {{ $isEdit ? 'Update Zone' : 'Create Zone' }}
        </button>
    </div>
</form>
@endsection
