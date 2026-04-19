@extends('layouts.admin')

@section('title', 'Contact Messages')
@section('breadcrumb')
<li class="active">Contact Messages</li>
@endsection

@section('admin-content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Contact Messages ({{ $messages->total() }})</h4>
</div>

<div class="admin-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Sender</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                    <tr class="{{ !$message->is_read ? 'font-weight-bold bg-light' : '' }}">
                        <td>{{ $message->id }}</td>
                        <td>
                            <div>{{ $message->name }}</div>
                            <small class="text-muted"><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></small>
                            @if($message->phone)
                            <br><small class="text-muted"><i class="fas fa-phone mr-1"></i>{{ $message->phone }}</small>
                            @endif
                        </td>
                        <td>{{ Str::limit($message->subject ?? 'No Subject', 50) }}</td>
                        <td>{{ $message->created_at->format('M d, Y h:i A') }}</td>
                        <td>
                            @if($message->is_read)
                                <span class="badge badge-secondary">Read</span>
                            @else
                                <span class="badge badge-primary">Unread</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.contact-messages.show', $message) }}" class="btn btn-sm btn-outline-primary mr-1" title="View Message">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete" onclick="if(confirm('Delete this message?')) { document.getElementById('delete-form-{{ $message->id }}').submit(); }">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <i class="fas fa-envelope-open fa-2x mb-2 d-block"></i>
                            No contact messages found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $messages->links() }}</div>

@foreach($messages as $message)
    <form id="delete-form-{{ $message->id }}" action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST" class="d-none">
        @csrf @method('DELETE')
    </form>
@endforeach
@endsection
