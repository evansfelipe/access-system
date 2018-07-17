@extends('layouts.app')

@section('content')
    <div class="row">
        @panel(['size' => 'col offset-lg-1 col-lg-10'])
            @slot('header', 'Información personal')
            @include('people._partials.save-person-form', ['route' => route('people.store'), 'button_text' => 'Siguiente'])
        @endpanel
    </div>
@endsection
{{-- @input(['type' => 'file', 'label' => 'Foto', 'name' => 'picture'])@endinput --}}