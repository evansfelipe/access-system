@extends('layouts.person-creation', ['step' => 1, 'route' => route('person-creation.working-information.store')])

@section('form')
    @include('forms.save-people-companies')
@endsection