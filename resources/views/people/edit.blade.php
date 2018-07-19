@extends('layouts.app')

@section('content')
    <div class="row">
        @panel(['size' => 'col offset-lg-1 col-lg-10'])
            <form enctype="multipart/form-data" action="{{ route('people.update', $person->id) }}" method="POST" class="no-select">
                @method('PUT')
                @include('forms.save-person')
                {{-- Submit --}}
                @submitbutton(['id' => 'create-person-submit', 'color' => 'success']) Guardar @endsubmitbutton
            </form>
        @endpanel
    </div>
@endsection