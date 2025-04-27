@inject('ClinicaUser', 'App\Models\ClinicaUser')
@inject('ConsultorioUser', 'App\Models\ConsultorioUser')
@php
    $my_clinics = $ClinicaUser::myClinics();
    $my_consultories = $ConsultorioUser::myConsultories();
@endphp
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" id="frm-selection">
                    @csrf
                    <div class="row">
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label for="setClinica" class="form-label">*CLINICAS</label>
                                <select name="clinica" id="selectClinic" class="form-control">
                                    <option value="">Seleccione una opción</option>
                                    @foreach ($my_clinics as $my_clinic)
                                        @php
                                            $clinica = $my_clinic->clinica;
                                        @endphp
                                        <option value="{{ $clinica->idclinica }}" {{ Session::get('clinica') == $clinica->idclinica ? 'selected' : '' }}>
                                            {{ $clinica->tnombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-3">
                                <label for="setConsultorio" class="form-label">*CONSULTORIOS</label>
                                <select name="consultorio" id="selectConsultory" class="form-control">
                                    <option value="">Seleccione una opción</option>
                                    @foreach ($my_consultories as $my_consultory)
                                        @php
                                            $consultory = $my_consultory->consultorio;
                                        @endphp
                                        <option value="{{ $consultory->idconsultorios }}" {{ Session::get('consultorio') == $consultory->idconsultorios ? 'selected' : '' }}>
                                            {{ $consultory->vnumconsultorio }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" onclick="aplicarConsultorio()" class="btn btn-primary w-100">Aplicar filtro</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div>