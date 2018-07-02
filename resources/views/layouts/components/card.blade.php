@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="@yield('card-size')">
            <div class="card">
                <div class="card-header">
                    <big><b>@yield('card-title')</b></big>
                </div>
                <div class="card-body">
                    @yield('card-body')
                </div>
            </div>
        </div>
    </div>
@endsection