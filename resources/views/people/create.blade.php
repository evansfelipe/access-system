@extends('layouts.app')

@section('content')
    <div class="row">
        @panel(['size' => 'col offset-lg-1 col-lg-10'])
            @slot('header')
                Nueva Persona
            @endslot

            <form enctype="multipart/form-data" action="{{route('people.store')}}" method="POST">
                @csrf
                <div class="form-row d-flex align-items-center">
                    <div class="col-md-4 text-center">
                        <div class="offset-1 col-10">
                            <img src="{{ asset('/pictures/no-image.jpg') }}" class="img-fluid rounded-circle" alt="Cargar image">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <!-- Last name & name -->
                        <div class="form-row">
                            <div class="col-12 col-md-6">
                                @input(['type' => 'text', 'label' => 'Apellido', 'name' => 'last_name'])@endinput
                            </div>
                            <div class="col-12 col-md-6">
                                @input(['type' => 'text', 'label' => 'Nombre', 'name' => 'name'])@endinput
                            </div>
                        </div>

                        <!-- Document & Cuil/Cuit -->
                        <div class="form-row">
                            <div class="col-12 col-md-3">
                                @select([
                                    'label' => 'Documento',
                                    'name' => 'document_type',
                                    'options' => [
                                        ['value' =>  'dni' , 'text' => 'DNI'],
                                        ['value' =>  'passport' , 'text' => 'Pasaporte']
                                    ]
                                ])
                                @endselect
                            </div>
                            <div class="col-12 col-md-3">                            
                                @input(['type' => 'text', 'label' => '_blank', 'name' => 'document_number'])@endinput
                            </div>
                            <div class="col-12 col-md-6">
                                @input(['type' => 'text', 'label' => 'CUIL / CUIT', 'name' => 'cuil'])@endinput
                            </div>
                        </div>

                        <!-- Birthday, sex & blood type -->
                        <div class="form-row">
                                <div class="col-12 col-md-6">
                                    @input(['type' => 'date', 'label' => 'Fecha de nacimiento', 'name' => 'birthday'])@endinput
                                </div>
                                <div class="col-12 col-md-3">
                                    @select([
                                        'label' => 'Género',
                                        'name' => 'sex',
                                        'options' => [
                                            ['value' => 'F', 'text' => 'Femenino'],
                                            ['value' => 'M', 'text' => 'Masculino'],
                                            ['value' => 'O', 'text' => 'Otro'],
                                        ]
                                    ])
                                    @endselect
                                </div>
                                <div class="col-12 col-md-3">
                                    @select([
                                        'label' => 'Grupo sanguíneo',
                                        'name' => 'blood_type',
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
                            </div>
                    </div>
                </div>

                <hr>

                <!-- Street, apartment & cp -->
                <div class="form-row">
                    <div class="col-md-4">
                        @input(['type' => 'text', 'label' => 'Domicilio', 'name' => 'street', 'required' => false])@endinput
                    </div>
                    <div class="col-md-4">
                        @input(['type' => 'text', 'label' => 'Piso y departamento', 'name' => 'apartment', 'required' => false])@endinput                        
                    </div>
                    <div class="col-md-4">
                        @input(['type' => 'text', 'label' => 'Código postal', 'name' => 'cp', 'required' => false])@endinput                                                
                    </div>
                </div>
                
                <!-- country, province/state & city -->
                <div class="form-row">
                    <div class="col-md-4">
                        @select([
                            'label' => 'País',
                            'name' => 'country',
                            'options' => [
                                        ['value' => 'arg', 'text' => 'Argentina']
                                    ]
                        ])
                        @endselect
                    </div>
                    <div class="col-md-4">
                        @select([
                            'label' => 'Provincia / Estado',
                            'name' => 'province',
                            'options' => [
                                        ['value' => 'baires', 'text' => 'Buenos Aires']
                                    ]
                        ])
                        @endselect
                    </div>
                    <div class="col-md-4">
                        @select([
                            'label' => 'Ciudad',
                            'name' => 'city',
                            'options' => [
                                        ['value' => 'mdp', 'text' => 'Mar del Plata']
                                    ]
                        ])
                        @endselect
                    </div>
                </div>
                
                <hr>

                <div class="form-row">
                    <div class="col-md-6">
                        <div class="form-row">
                            <div class="col-4">
                                @select([
                                    'label' => 'Teléfono',
                                    'name' => 'phone_type',
                                    'options' => [
                                        ['value' => 'home', 'text' => 'Fijo'],
                                        ['value' => 'mobile', 'text' => 'Móvil'],
                                        ['value' => 'fax', 'text' => 'Fax'],
                                    ]
                                ])
                                @endselect
                            </div>
                            <div class="col-7">
                                @input(['type' => 'text', 'label' => '_blank', 'name' => 'phone', 'required' => false])@endinput
                            </div>
                            <div class="col-1">
                                <label for="">&nbsp;</label>
                                <button type="button" class="btn btn-sm btn-outline-unique btn-circle"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-row">
                            <div class="col-11">
                                    @input(['type' => 'text', 'label' => 'Mail', 'name' => 'mail', 'required' => false])@endinput
                            </div>
                            <div class="col-1">
                                <label for="">&nbsp;</label>
                                <button type="button" class="btn btn-sm btn-outline-unique btn-circle"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                
                {{-- @input(['type' => 'file', 'label' => 'Foto', 'name' => 'picture', 'required' => false])@endinput --}}
                
                <br>

                @submitbutton(['id' => 'create-person-submit', 'color' => 'success']) Guardar @endsubmitbutton
            </form>
        @endpanel
    </div>
@endsection