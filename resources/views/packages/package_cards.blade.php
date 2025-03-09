@if(count($packages) > 0)
    @foreach($packages as $package)
        <div class="col-md-4">
            <div class="package-card" id="{{ $package->id }}" data-package-name="{{ $package->nombre }}" data-package-price="{{ $package->precio }}">
                <div class="package-header">
                    <h4 class="text-center">{{ $package->nombre }}</h4>
                </div>
                <div class="package-body">
                    @if(count($package->items) > 0)
                        @foreach($package->items as $item)
                            <div class="package-feature">
                                <i class="fas fa-check"></i> {{ $item->catalogPrice->nombre }}: 
                                {{ $item->max ? $item->max : 'Ilimitado' }}
                            </div>
                        @endforeach
                    @else
                        <div class="package-feature">
                            <i class="fas fa-exclamation-circle"></i> No hay características disponibles
                        </div>
                    @endif
                   @if ($package->tipoReporte != null)
                    <div class="package-feature">
                        <i class="fas fa-check"></i> {{ isset(config('enums.soporte')[$package->tipoReporte])?config('enums.soporte')[$package->tipoReporte] : null }} 
                    </div>
                   @endif
                    
                </div>
            </div>
        </div>
    @endforeach
    <div class="text-center mt-3">
        <h5>${{ $package->precio }}/mes</h5>
    </div>
@else
    <div class="col-12 text-center">
        <p>No hay paquetes disponibles para este tipo de registro.</p>
    </div>
@endif