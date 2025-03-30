@extends('layouts.template')

@section('content_header')
<div class="container">
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                    <li class="breadcrumb-item">CONFIGURACIÓN</li>
                </ol>
            </nav>
        </div>
    </div>
</div>
@stop
@section('content')
<div class="container bg-white py-2">

    <div class="row mt-3 card">
        
        <div class="card-body">

            <div class="col-12 mt-3">
                <form id="frm-setting">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label for="labelTarjeta" class="form-label">Aceptar transferencia bancaria</label>
                            <div class="form-check">
                                
                                <input class="form-check-input" type="checkbox" value="1" id="is_payment_transfer" name="data[is_payment_transfer]" {{ $setting != null && $setting->is_payment_transfer == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_payment_transfer">
                                    Sí
                                </label>
                            </div>
                            
                        </div>
                        <div class="col-6">
                            <label for="labelTarjeta" class="form-label">Aceptar pagos con tarjeta</label>
                            <div class="form-check">
                                
                                <input class="form-check-input" type="checkbox" value="1" id="is_payment_card" name="data[is_payment_card]" {{ $setting != null && $setting->is_payment_card == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_payment_card">
                                    Sí
                                </label>
                            </div>
                        </div>
                        <div id="content-transfer" style="display: {{ $setting != null && $setting->is_payment_transfer == 1 ? 'block' : 'none' }}">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inputTelefono" class="form-label">Banco</label>
                                    <input type="text" name="data[banco]" class="form-control" value="{{ $setting != null && $setting->banco != null ? $setting->banco : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inputTelefono" class="form-label">Titular</label>
                                    <input type="text" name="data[titular]" class="form-control" value="{{ $setting != null && $setting->titular != null ? $setting->titular : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inputTelefono" class="form-label">Cuenta</label>
                                    <input type="text" name="data[cuenta]" class="form-control" value="{{ $setting != null && $setting->cuenta != null ? $setting->cuenta : '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="inputTelefono" class="form-label">CLABE</label>
                                    <input type="text" name="data[clabe]" class="form-control" value="{{ $setting != null && $setting->clabe != null ? $setting->clabe : '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 text-end">
                            <div class="mb-3">
                                <input type="hidden" id="setting_id" name="setting_id" value="{{ $setting != null ? $setting->id : null }}" >
                                <button class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
                       
                        
                        
                    </div>  
                </form>
            </div>
        </div>
    </div>

</div>
@stop
