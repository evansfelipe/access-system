@extends('layouts.app')
@section('content')
    <div class="row">


        @include('people.show._partials.tabs', ['person_id' => $person['id'], 'active' => 0])
        
        @panel(['size' => 'col offset-lg-2 col-lg-8'])

            <div class="row d-flex align-items-center">
                {{-- Photo loader section --}}
                <div class="col-md-4 text-center">
                    <div class="offset-1 col-10">
                        <img src="{{ asset('/pictures/no-image.jpg') }}" class="img-fluid rounded-circle" alt="Cargar image">
                    </div>
                </div>
                {{-- Identification information section --}}
                <div class="col-md-8">
                    <div class="row">
                        <div class="col">
                            <h3>{{ $person['full_name'] }}</h3>
                            <table style="width: 100%;">
                                <tbody>
                                    {{-- Document --}}
                                    <tr>
                                        <td>{{ $person['document_type'] }}</td>
                                        <td><strong>{{ $person['document_number'] }}</strong></td>
                                    </tr>
                                    {{-- Cuil/Cuit --}}
                                    <tr>
                                        <td>CUIL / CUIT</td>
                                        <td>{{ $person['cuil'] }}</td>
                                    </tr>
                                    {{-- Birthday --}}
                                    <tr>
                                        <td>Fecha de nacimiento</td>
                                        <td><strong>{{ $person['birthday'] }}</strong></td>
                                    </tr>
                                    {{-- Sex --}}
                                    <tr>
                                        <td>Género</td>
                                        <td><strong>{{ $person['sex'] }}</strong></td>
                                    </tr>
                                    {{-- Blood type --}}
                                    <tr>
                                        <td>Grupo sanguíneo</td>
                                        <td><strong>{{ $person['blood_type'] }}</strong></td>
                                    </tr>
                                    {{-- PNA --}}
                                    <tr>
                                        <td>Prontuario PNA</td>
                                        <td><strong>{{ $person['pna'] }}</strong></td>
                                    </tr>
                                    {{-- Mail --}}
                                    <tr>
                                        <td>Mail</td>
                                        <td><strong>{{ $person['email'] }}</strong></td>
                                    </tr>
                                    {{-- Phone --}}
                                    <tr>
                                        <td>Teléfono fijo</td>
                                        <td><strong>{{ $person['phone'] }}</strong></td>
                                    </tr>
                                    {{-- Mobile phone --}}
                                    <tr>
                                        <td>Teléfono móvil</td>
                                        <td><strong>{{ $person['mobile_phone'] }}</strong></td>
                                    </tr>
                                    {{-- Fax --}}
                                    <tr>
                                        <td>FAX</td>
                                        <td><strong>{{ $person['fax'] }}</strong></td>
                                    </tr>
                                    {{-- Residency --}}
                                    <tr>
                                        <td>Domicilio</td>
                                        <td><strong>{{ $person['residency'] }}</strong></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endpanel
    </div>
@endsection