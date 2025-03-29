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
                <form id="frm-paquete">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <label for="labelTarjeta" class="form-label">Aceptar pagos con tarjeta</label>
                            <div class="form-check">
                                
                                <input class="form-check-input" type="checkbox" value="" id="is_payment_card">
                                <label class="form-check-label" for="is_payment_card">
                                    Sí
                                </label>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="labelTarjeta" class="form-label">Aceptar transferencia bancaria</label>
                            <div class="form-check">
                                
                                <input class="form-check-input" type="checkbox" value="" id="is_payment_transfer">
                                <label class="form-check-label" for="is_payment_transfer">
                                    Sí
                                </label>
                            </div>
                            <div id="content-payment-transfer">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="inputTelefono" class="form-label">INFORMACIÓN DE TRANSFERENCIA</label>
                                        <textarea name="transfer_data" id="transfer_data" cols="30" rows="4" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>  
                </form>
            </div>
        </div>
    </div>

</div>
@stop
