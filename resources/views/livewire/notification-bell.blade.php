<div class="notification-bell-widget">
    {{-- Notification Bell Icon --}}
    <div class="position-relative d-inline-block">
        <a href="#" class="dropdown-toggle text-dark" data-toggle="dropdown" aria-expanded="false" style="text-decoration: none;">
            <i class="icon-bell"></i>
            @if($unreadCount > 0)
                <span class="badge badge-danger position-absolute" style="top: -5px; right: -10px; font-size: 10px; border-radius: 50%; min-width: 18px; height: 18px; line-height: 18px; padding: 0 4px;">
                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                </span>
            @endif
        </a>

        <div class="dropdown-menu dropdown-menu-right" style="width: 320px; max-height: 400px; overflow-y: auto;">
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                <h6 class="mb-0">Notifications</h6>
                @if($unreadCount > 0)
                    <a href="#" wire:click.prevent="markAllAsRead" class="text-muted small">Mark all read</a>
                @endif
            </div>

            @forelse($notifications as $notification)
                <a href="{{ $notification->data['url'] ?? '#' }}"
                   wire:click="markAsRead('{{ $notification->id }}')"
                   class="dropdown-item py-2 {{ $notification->read_at ? '' : 'bg-light' }}"
                   style="white-space: normal; border-bottom: 1px solid #f4f4f4;">
                    <div class="d-flex align-items-start">
                        <div class="mr-2 mt-1">
                            @if(($notification->data['type'] ?? '') === 'order_status')
                                <i class="icon-bag text-primary"></i>
                            @else
                                <i class="icon-bell text-muted"></i>
                            @endif
                        </div>
                        <div>
                            <p class="mb-0 small {{ $notification->read_at ? 'text-muted' : 'text-dark' }}">
                                {{ $notification->data['message'] ?? 'New notification' }}
                            </p>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </a>
            @empty
                <div class="dropdown-item text-center py-3 text-muted">
                    <i class="icon-bell-off mb-2" style="font-size: 24px;"></i>
                    <p class="mb-0 small">No notifications yet</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
