@extends('layouts.app')

@section('content')
    <div class="row mt-3">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item active" aria-current="page"><a href="/">INICIO</a></li>
                  <li class="breadcrumb-item"><a href="/">ADMINISTRACIÓN</a></li>
                  <li class="breadcrumb-item">LISTA DE CLINICAS</li>
                </ol>
              </nav>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <a href="/admin/clinica/create" class="btn btn-primary float-end"><i class="fa-solid fa-plus"></i></a>
            </div>
            
            
        </div>
    </div>
@endsection