@extends('layouts.person-creation', ['step' => 0, 'route' => route('person-creation.personal-information.store')])

@section('form')
    @include('forms.save-person')
@endsection
{{-- @input(['type' => 'file', 'label' => 'Foto', 'name' => 'picture'])@endinput --}}