@extends('layouts.app')

@section('content')
    <div class="row">
        @panel(['size' => 'col offset-lg-1 col-lg-10'])
            @slot('header')
                Información laboral de {{ $person_name }}
            @endslot

            <form action="{{route('people.companies.store', $person_id)}}" method="POST" class="no-select">
                @csrf

                <div class="form-row">
                    @formitem(['label' => 'Empresa'])
                        <div class="col-md-6">
                            @select([
                                'name' => 'company_id',
                                'required' => true,
                                'options' => $companies_data
                            ])
                            @endselect
                        </div>
                        <div class="col-md-1 text-center">
                            <strong>o</strong>
                        </div>
                        <div class="col-md-5">
                            @formbutton Crear nueva empresa @endformbutton
                        </div>
                        <div class="col">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input">
                                <label class="form-check-label">Monotributista</label>
                            </div>
                        </div>
                    @endformitem                     
                </div>
               
                <div class="form-row">
                    @formitem(['label' => 'Actividad / Categoría'])
                        <div class="col-md-6">
                            @select([
                                'name' => 'activity_id',
                                'required' => true,
                                'options' => [
                                    ['value' => 'pesquero', 'text' => 'Pesquero'],
                                    ['value' => 'calidad', 'text' => 'Control de calidad'],
                                ]
                            ])
                            @endselect
                        </div>
                        <div class="col-md-1 text-center">
                            <strong>o</strong>
                        </div>
                        <div class="col-md-5">
                            @formbutton Nueva actividad @endformbutton
                        </div>
                    @endformitem
                </div>

                <div class="form-row">
                    @formitem(['label' => 'ART', 'col' => 'col-md-6'])
                        <div class="col">
                            @input(['type' => 'text', 'name' => 'art', 'required' => true])@endinput                        
                        </div>
                    @endformitem                    
                </div>

                <div class="form-row">
                    @formitem(['label' => 'Vencimiento PBIP', 'col' => 'col-md-6'])
                        <div class="col">
                            @input(['type' => 'date', 'name' => 'pbip'])@endinput                        
                        </div>
                    @endformitem
                </div>
                
                @submitbutton(['id' => 'create-person-company-submit', 'color' => 'success']) Guardar @endsubmitbutton
            </form>
        @endpanel
    </div>
@endsection