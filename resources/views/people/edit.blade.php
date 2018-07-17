@extends('layouts.app')

@section('content')
    <div class="row">
        @panel(['size' => 'col offset-lg-1 col-lg-10'])
            @slot('header', 'Edición de información personal')
            @include('people._partials.save-person-form', ['route' => route('people.update', $person->id), 'method' => 'PUT'])
        @endpanel
    </div>
@endsection