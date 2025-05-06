<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Todas las Notificaciones</h5>
                        <div class="d-flex gap-2">
                            <button wire:click="markAllAsRead" class="btn btn-sm btn-outline-primary">
                                Marcar todas como leídas
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <div class="btn-group" role="group">
                            <button wire:click="setFilter('all')" 
                                class="btn btn-outline-primary {{ $filter === 'all' ? 'active' : '' }}">
                                Todas
                            </button>
                            <button wire:click="setFilter('unread')" 
                                class="btn btn-outline-primary {{ $filter === 'unread' ? 'active' : '' }}">
                                No leídas
                            </button>
                            <button wire:click="setFilter('read')" 
                                class="btn btn-outline-primary {{ $filter === 'read' ? 'active' : '' }}">
                                Leídas
                            </button>
                        </div>
                    </div>

                    <div class="notification-list">
                        @forelse($notifications as $notification)
                            <div class="notification-item d-flex align-items-center py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <div class="notification-icon {{ $notification->leido ? 'bg-secondary' : 'bg-primary' }} rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px">
                                    <i class="fas fa-bell text-white"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $notification->title }}</h6>
                                            <p class="mb-1">{{ $notification->msg }}</p>
                                            <small class="text-muted">{{ $notification->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                        @if(!$notification->leido)
                                            <button wire:click="markAsRead({{ $notification->id }})" 
                                                class="btn btn-sm btn-link text-primary">
                                                <i class="fas fa-check"></i> Marcar como leída
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <p class="text-muted mb-0">No hay notificaciones</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 