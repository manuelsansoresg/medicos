
    <form action="{{ route('formularios.store', ['id' => $configuration->id, 'consultaId' => $consultaId]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @foreach($configuration->fields as $field)
            <div>
                <label for="field_{{ $field->id }}">{{ $field->field_name }}</label>
                @if($field->field_type == 'text')
                    <input type="text" class="form-control" name="field_{{ $field->id }}" @if($field->is_required) required @endif>
                @elseif($field->field_type == 'date')
                    <input type="date" class="form-control" name="field_{{ $field->id }}" @if($field->is_required) required @endif>
                @elseif($field->field_type == 'textarea')
                    <textarea name="field_{{ $field->id }}" @if($field->is_required) class="form-control" required @endif></textarea>
                @elseif($field->field_type == 'select')
                    <select name="field_{{ $field->id }}" class="form-control" @if($field->is_required) required @endif>
                        @foreach(explode(',', $field->options) as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                @elseif($field->field_type == 'image')
                    <input type="file" class="form-control" name="field_{{ $field->id }}" @if($field->is_required) required @endif>
                @endif
            </div>
        @endforeach
        <input type="hidden" name="user_cita_id" id="user_cita_id" value="{{ isset($userCitaId) ? $userCitaId : null }}">
        <input type="hidden"  id="user_cita_id_origin" value="{{ isset($userCitaId) ? $userCitaId : null }}">
        <input type="hidden" name="paciente_id" id="paciente_id" value="{{ isset($paciente) && $paciente != null ? $paciente->id : null }}">
        <input type="hidden" name="consulta_id" id="consulta_id" value="">
        
        <div class="col-12 text-right mt-3">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
