<div class="col-12">
    <div class="border-0 h-100">
        <div class="bg-white border-bottom-0">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Notificaciones</h6>
                <div class="d-flex align-items-center">
                    <span class="badge bg-primary rounded-pill me-2">{{ $notifications->total() }}</span>
                    <a href="{{ route('notifications.all') }}" class="btn btn-sm btn-outline-primary">
                        Ver todos
                    </a>
                </div>
            </div>
        </div>
        <div class="py-0">
            <div class="notification-list">
                @forelse($notifications as $notification)
                    <div class="notification-item d-flex align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="notification-icon {{ $notification->leido ? 'bg-secondary' : 'bg-primary' }} rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px">
                            <i class="fas fa-bell text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-0 small">{{ $notification->msg }}</p>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                        @if($notification->leido == 1)
                            <button wire:click="markAsRead({{ $notification->id }})" class="btn btn-sm btn-link text-primary">
                                <i class="fas fa-check"></i>
                            </button>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-3">
                        <p class="text-muted mb-0">No hay notificaciones</p>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="card-footer bg-white border-top-0 text-center">
            <div class="d-flex justify-content-center">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div> 