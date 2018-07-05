@extends('layouts.app')

@section('content')
    <div class="row">
        @panel(['size' => 'col offset-lg-2 col-lg-8'])
            @slot('header')
                Editar Persona
            @endslot

            <form enctype="multipart/form-data" action="{{route('people.update', $person->id)}}" method="POST">
                @method('PUT')
                @csrf
                <div class="form-row">
                    <div class="col-12 col-md-6">
                        @input(['type' => 'text', 'label' => 'Apellido', 'name' => 'last_name', 'value' => $person->last_name])@endinput
                    </div>
                    <div class="col-12 col-md-6">
                        @input(['type' => 'text', 'label' => 'Nombre', 'name' => 'name', 'value' => $person->name])@endinput
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="col-12 col-md-6">
                        @input(['type' => 'text', 'label' => 'CUIL', 'name' => 'cuil', 'value' => $person->cuil])@endinput
                    </div>
                    <div class="col-12 col-md-6">
                        @select([
                            'label' => 'Sexo',
                            'name' => 'sex',
                            'value' => $person->sex,
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
                        @select(['label' => 'Empresa', 'name' => 'company_id', 'value' => $person->company_id, 'options' => $companies_data])@endselect
                    </div>
                    
                    <div class="col-12 col-md-6">
                        @input(['type' => 'date', 'label' => 'Fecha de nacimiento', 'name' => 'birthday', 'value' => date('Y-m-d', strtotime($person->birthday))])@endinput
                    </div>
                </div>


                <div class="form-row">
                    <div class="col-12 col-md-6">
                        @input(['type' => 'file', 'label' => 'Foto', 'name' => 'picture', 'required' => false])@endinput
                    </div>
                </div>

                <br>

                @submitbutton(['id' => 'edit-person-submit', 'color' => 'success']) Guardar @endsubmitbutton
            </form>
        @endpanel
    </div>
@endsection