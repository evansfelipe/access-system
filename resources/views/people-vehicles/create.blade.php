@extends('layouts.app')

@section('content')
    <div class="row">
        @panel(['size' => 'col offset-lg-1 col-lg-10'])
            @slot('header', 'Asignación de vehículos existentes')
            <assign-person-vehicles vehicles="{{ ($vehicles) }}" 
                                    path="{{ route('person-creation.assign-vehicles.create') }}">
            </assign-person-vehicles>
        @endpanel
    </div>
@endsection
