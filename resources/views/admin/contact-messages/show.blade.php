@extends('layouts.admin')

@section('title', 'View Message')
@section('breadcrumb')
<li><a href="{{ route('admin.contact-messages.index') }}">Contact Messages</a></li>
<li class="active">View Message</li>
@endsection

@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Message Details</h4>
    <div>
        <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to Messages
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="card-header py-3 bg-light">
        <h5 class="mb-0">{{ $contactMessage->subject ?: 'No Subject' }}</h5>
    </div>
    <div class="card-body">
        <div class="row border-bottom pb-4 mb-4">
            <div class="col-md-6">
                <p class="mb-1"><strong class="text-dark">From:</strong> {{ $contactMessage->name }}</p>
                <p class="mb-1"><strong class="text-dark">Email:</strong> <a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a></p>
                <p class="mb-0"><strong class="text-dark">Phone:</strong> {{ $contactMessage->phone ?: 'Not provided' }}</p>
            </div>
            <div class="col-md-6 text-md-right">
                <p class="mb-1"><strong class="text-dark">Received at:</strong> {{ $contactMessage->created_at->format('M d, Y h:i A') }}</p>
                <p class="mb-0"><strong class="text-dark">Status:</strong> 
                    @if($contactMessage->is_read)
                        <span class="badge badge-secondary">Read</span>
                    @else
                        <span class="badge badge-primary">Unread</span>
                    @endif
                </p>
            </div>
        </div>

        <div class="message-content">
            <strong class="d-block mb-3 text-dark">Message:</strong>
            <div class="p-4 bg-light rounded border">
                {!! nl2br(e($contactMessage->message)) !!}
            </div>
        </div>
    </div>
    <div class="card-footer bg-white text-right">
        <button type="button" class="btn btn-danger" onclick="if(confirm('Delete this message?')) { document.getElementById('delete-form').submit(); }">
            <i class="fas fa-trash mr-1"></i> Delete Message
        </button>
    </div>
</div>

<form id="delete-form" action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST" class="d-none">
    @csrf @method('DELETE')
</form>
@endsection
