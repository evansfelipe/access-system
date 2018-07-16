@extends('layouts.app')

@section('content')
    <div class="row">
        @include('people.show._partials.tabs', ['person_id' => $person['id'], 'active' => 1])
        @panel(['size' => 'col offset-lg-2 col-lg-8'])
            Working information
        @endpanel
    </div>
@endsection