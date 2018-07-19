@csrf
{{-- Photo upload & identification information --}}
<div class="form-row d-flex align-items-center">
    {{-- Photo loader section --}}
    <div class="col-md-4 text-center">
        <div class="offset-1 col-10">
            <img src="{{ asset('/pictures/no-image.jpg') }}" class="img-fluid rounded-circle" alt="Cargar image">
        </div>
    </div>
    {{-- Identification information section --}}
    <div class="col-md-8">
        {{-- Last name & name --}}
        <div class="form-row">
            @formitem(['label' => 'Apellido', 'col' => 'col-12 col-md-6'])
                <div class="col">
                    @input(['type' => 'text', 'name' => 'last_name', 'value' => $person->last_name ?? null, 'required' => true])@endinput
                </div>
            @endformitem
            @formitem(['label' => 'Nombre', 'col' => 'col-12 col-md-6'])
                <div class="col">
                    @input(['type' => 'text', 'name' => 'name', 'value' => $person->name ?? null, 'required' => true])@endinput
                </div>
            @endformitem
        </div>
        {{-- Document & Cuil/Cuit --}}
        <div class="form-row">
            @formitem(['label' => 'Documento', 'col' => 'col-12 col-md-6'])
                <div class="col-12 col-md-5">
                    @select([
                        'name' => 'document_type',
                        'required' => true,
                        'placeholder' => 'Tipo',
                        'value' => $person->document_type ?? null,
                        'options' => [
                            ['value' => strval(\App\Person::DNI), 'text' => 'DNI'],
                            ['value' => strval(\App\Person::PASSPORT), 'text' => 'Pasaporte']
                        ]
                    ])
                    @endselect
                </div>
                <div class="col-12 col-md-7">                            
                    @input(['type' => 'text', 'placeholder' => 'Número', 'name' => 'document_number', 'value' => $person->document_number ?? null, 'required' => true])@endinput
                </div>
            @endformitem
            @formitem(['label' => 'CUIL / CUIT', 'col' => 'col-12 col-md-6'])
                <div class="col">
                    @input(['type' => 'text', 'name' => 'cuil', 'value' => $person->cuil ?? null, 'required' => true])@endinput
                </div>
            @endformitem
        </div>

        {{-- Birthday, sex & blood type  --}}
        <div class="form-row">
            {{-- Birthday --}}
            @formitem(['label' => 'Fecha de nacimiento', 'col' => 'col-12 col-md-6'])
                <div class="col">
                    @input(['type' => 'date', 'name' => 'birthday', 'value' => isset($person->birthday) ? date('Y-m-d', strtotime($person->birthday)) : null])@endinput
                </div>
            @endformitem
            {{-- Sex --}}
            @formitem(['label' => 'Género', 'col' => 'col-12 col-md-3'])
                <div class="col">
                    @select([
                        'name' => 'sex',
                        'placeholder' => 'Seleccione género',
                        'value' => $person->sex ?? null,
                        'options' => [
                            ['value' => 'F', 'text' => 'Femenino'],
                            ['value' => 'M', 'text' => 'Masculino'],
                            ['value' => 'O', 'text' => 'Otro'],
                        ]
                    ])
                    @endselect
                </div>
            @endformitem
            {{-- Blood type --}}
            @formitem(['label' => 'Grupo sanguíneo', 'col' => 'col-12 col-md-3'])
                <div class="col">
                    @select([
                        'name' => 'blood_type',
                        'placeholder' => 'Seleccione grupo y factor',
                        'value' => $person->blood_type ?? null,
                        'options' => [
                            ['value' => '0-', 'text' => '0-'],
                            ['value' => '0+', 'text' => '0+'],
                            ['value' => 'A-', 'text' => 'A-'],
                            ['value' => 'A+', 'text' => 'A+'],
                            ['value' => 'B-', 'text' => 'B-'],
                            ['value' => 'B+', 'text' => 'B+'],
                            ['value' => 'AB-', 'text' => 'AB-'],
                            ['value' => 'AB+', 'text' => 'AB+']
                        ]
                    ])
                    @endselect
                </div>
            @endformitem
        </div>
        {{-- PNA & email --}}
        <div class="form-row">
            @formitem(['label' => 'Prontuario PNA', 'col' => 'col-6'])
                <div class="col">
                    @input(['type' => 'text', 'name' => 'pna', 'value' => $person->pna ?? null])@endinput
                </div>
            @endformitem
            @formitem(['label' => 'Mail', 'col' => 'col-6'])
                <div class="col">
                    @input(['type' => 'email', 'name' => 'email', 'value' => $person->email ?? null])@endinput
                </div>
            @endformitem
        </div>
    </div>
</div>
<hr>
{{-- Phone, mobile phone & fax --}}
<div class="form-row">
    {{-- Phone --}}
    @formitem(['label' => 'Teléfono fijo', 'col' => 'col-md-4'])
        <div class="col">
            @input(['type' => 'text', 'name' => 'home_phone', 'value' => $person->home_phone ?? null])@endinput
        </div>
    @endformitem
    {{-- Mobile phone --}}
    @formitem(['label' => 'Teléfono móvil', 'col' => 'col-md-4'])
        <div class="col">
            @input(['type' => 'text', 'name' => 'mobile_phone', 'value' => $person->mobile_phone ?? null])@endinput
        </div>
    @endformitem    
    {{-- Fax --}}
    @formitem(['label' => 'FAX', 'col' => 'col-md-4'])
        <div class="col">
            @input(['type' => 'text', 'name' => 'fax', 'value' => $person->fax ?? null])@endinput
        </div>
    @endformitem    
</div>
{{-- Residency component with necessaty inputs --}}
@residency(['model' => $person->residency ?? null])@endresidency
