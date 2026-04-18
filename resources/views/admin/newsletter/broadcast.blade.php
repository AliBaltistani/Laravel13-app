@extends('layouts.admin')
@section('title', 'Broadcast')
@section('breadcrumb')<li><a href="{{ route('admin.newsletter.index') }}">Newsletter</a></li><li class="active">Broadcast</li>@endsection
@section('admin-content')
<div class="row"><div class="col-lg-8"><div class="admin-card"><div class="card-header"><h5>Send Broadcast to {{ $count }} subscribers</h5></div><div class="card-body">
<form method="POST" action="{{ route('admin.newsletter.send-broadcast') }}">@csrf
    <div class="form-group"><label>Subject <span class="text-danger">*</span></label><input type="text" name="subject" class="form-control" required></div>
    <div class="form-group"><label>Message <span class="text-danger">*</span></label><textarea name="message" class="form-control richtext-editor" rows="10" required></textarea></div>
    <div class="alert alert-warning"><i class="fas fa-exclamation-triangle mr-1"></i> This will send an email to <strong>{{ $count }}</strong> active subscribers.</div>
    <button type="submit" class="btn btn-primary" onclick="return confirm('Send broadcast to {{ $count }} subscribers?')"><i class="fas fa-paper-plane mr-1"></i> Send Broadcast</button>
    <a href="{{ route('admin.newsletter.index') }}" class="btn btn-outline-secondary ml-2">Cancel</a>
</form></div></div></div></div>
@endsection
