@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="offset-1 col-10">
            <person-creation    companies="{{ $companies }}"
                                activities="{{ $activities }}"
                                vehicles="{{ $vehicles }}"
            >
            </person-creation>
        </div>
    </div>
@endsection