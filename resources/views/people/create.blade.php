@extends('layouts.app')

@section('content')
    <div class="row">
        @panel(['size' => 'col offset-lg-2 col-lg-8'])
            @slot('header')
                Nueva Persona
            @endslot

            <form action="{{route('people.store')}}" method="POST">
                @csrf
                <div class="form-row">
                    <div class="col-12 col-md-6">
                        @input(['type' => 'text', 'label' => 'Apellido', 'name' => 'last_name'])@endinput
                    </div>
                    <div class="col-12 col-md-6">
                        @input(['type' => 'text', 'label' => 'Nombre', 'name' => 'name'])@endinput
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="col-12 col-md-6">
                        @input(['type' => 'text', 'label' => 'CUIL', 'name' => 'cuil'])@endinput
                    </div>
                    <div class="col-12 col-md-6">
                        @select([
                            'label' => 'Sexo',
                            'name' => 'sex',
                            'options' => [
                                ['value' => 'F', 'text' => 'Femenino'],
                                ['value' => 'M', 'text' => 'Masculino'],
                                ['value' => 'O', 'text' => 'Otro'],
                            ]
                        ])
                        @endselect
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="col-12 col-md-6">
                        @select(['label' => 'Empresa', 'name' => 'company_id', 'options' => $companies_data])@endselect
                    </div>
                    
                    <div class="col-12 col-md-6">
                        @input(['type' => 'date', 'label' => 'Fecha de nacimiento', 'name' => 'birthday'])@endinput
                    </div>
                </div>

                <br>

                @submitbutton(['color' => 'success']) Guardar @endsubmitbutton
            </form>
        @endpanel
    </div>
@endsection