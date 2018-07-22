@extends('layouts.person-creation', ['step' => 2, 'route' => route('person-creation.assign-vehicles.store')])

@section('form')
    <assign-person-vehicles 
        vehicles="{{ $vehicles }}" 
        @if(isset($company_id) && isset($company_name)) companyname="{{ $company_name }}" companyid={{ $company_id }} @endif>
    </assign-person-vehicles>
@endsection
