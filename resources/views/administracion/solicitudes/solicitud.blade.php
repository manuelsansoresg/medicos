@extends('layouts.template')

@section('content')
<div class="container bg-white py-2">
    <div class="row mt-3 card">
       
        <div class="card-body">

            <div class="col-12 mt-3">
                <p class="h6 color-secondary">SOLICITUD</p>
                <p>
                     <span class="color-secondary"> PAQUETE :</span>  {{ $solicitud->nombre }}  <br>
                     <span class="color-secondary"> CANTIDAD :</span>  {{ $solicitud->cantidad }}  <br>
                     <span class="color-secondary"> PRECIO :</span>  {{ $solicitud->precio }}  <br>
                     <span class="color-secondary"> TOTAL :</span>  {{ $solicitud->precio }}  <br>
                </p>
                <p class="h6 color-secondary">DATOS PARA LA TRANSFERENCIA</p>
                <span class="color-secondary"> NOMBRE :</span> JOSE VAZQUEZ  <br>
                <span class="color-secondary"> CLABE :</span> 0123348458585858  <br>
                <span class="color-secondary"> BANCO :</span> BANAMEX  <br>
                
                <p class="h6 color-secondary mt-3">SUBIR COMPROBANTE DE LA TRANSFERENCIA</p>
                <form method="post" action="/admin/solicitudes/1/adjuntarComprobante" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3 mt-3">
                        <label for="inputNombre" class="form-label">COMPROBANTE</label>
                        <input type="file" name="comprobante" class="form-control">
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    
                    <div class="col-md-12 text-end">
                        <div class="mb-3">
                            <input type="hidden" name="solicitudId" value="">
                            <button class="btn btn-primary">Adjuntar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection