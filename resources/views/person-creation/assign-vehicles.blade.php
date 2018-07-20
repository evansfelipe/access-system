@extends('layouts.person-creation', ['step' => 2, 'route' => route('person-creation.assign-vehicles.store')])

@section('form')
    <assign-person-vehicles vehicles="{{ $vehicles }}"></assign-person-vehicles>
@endsection
