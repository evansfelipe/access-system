@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col offset-lg-1 col-lg-10">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link no-select {{ $step == 0 ? 'active' : ($step <= 0 ? 'unrecheable' : '') }}" 
                        @if($step > 0)
                            href="{{ route('person-creation.personal-information.create') }}">
                            <i class="fas fa-check-circle green-text"></i>
                        @else
                            >
                            <i class="fas fa-user"></i>
                        @endif
                        Información personal
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link no-select {{ $step == 1 ? 'active' : ($step <= 1 ? 'unrecheable' : '') }}"
                        @if($step > 1)
                            href="{{ route('person-creation.working-information.create') }}">
                            <i class="fas fa-check-circle green-text"></i>
                        @else
                            >
                            <i class="fas fa-briefcase"></i>
                        @endif
                        Información laboral
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link no-select {{ $step == 2 ? 'active' : ($step <= 2 ? 'unrecheable' : '') }}"
                        @if($step > 2)
                            href="{{ route('person-creation.assign-vehicles.create') }}">
                            <i class="fas fa-check-circle green-text"></i>
                        @else
                            >
                            <i class="fas fa-car"></i> 
                        @endif
                        Vehículos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link no-select {{ $step == 3 ? 'active' : ($step <= 3 ? 'unrecheable' : '') }}"
                        @if($step > 3)
                            href="{{ route('person-creation.first-card.create') }}">
                            <i class="fas fa-check-circle green-text"></i>
                        @else
                            >
                            <i class="fas fa-id-card"></i>
                        @endif
                        Tarjeta
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link no-select {{ $step == 4 ? 'active' : ($step <= 4 ? 'unrecheable' : '') }}"
                        @if($step > 4)
                            href="http://www.google.com">
                            <i class="fas fa-check-circle green-text"></i>
                        @else
                            >
                            <i class="fas fa-file-alt"></i>
                        @endif
                        Documentación
                    </a>
                </li>
            </ul>
        </div>
    </div>
        
    <div class="row">
        @panel(['size' => 'col offset-lg-1 col-lg-10', 'classes' => 'squared-top'])
            <form action="{{ $route }}" method="POST" class="no-select">
                @csrf
                @yield('form')
                <hr>
                <div class="form-row">
                    <div class="col-6">
                        <a href="{{ route('person-creation.cancel') }}" class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i> Cancelar</a>
                    </div>
                    <div class="col-6 text-right">
                        @submitbutton(['id' => 'create-person.first-card']) Siguiente <i class="fas fa-angle-double-right"></i> @endsubmitbutton
                    </div>
                </div> 
            </form>
        @endpanel
    </div>
@endsection

@section('css')
<style>
    .green-text {
        color: green;
    }

    .nav-tabs > .nav-item {
        cursor: pointer;
    }

    .nav-tabs > .nav-item > a {
        color: green;
    }

    .nav-item + .nav-item {
        margin-left: 1px;
    }

    .nav-tabs > .nav-item > a.active {
        background-color: white;
        border-bottom-color: white;
        cursor: auto;
        font-weight: bold;
        color: black;
    }

    .nav-tabs > .nav-item > a.unrecheable {
        background-color: transparent;
        border-color: transparent;
    }

    .nav-tabs > .nav-item > a.unrecheable:hover {
        border-color: transparent;
    }

    .squared-top {
        border-top: 0;
        border-top-right-radius: 0;
        border-top-left-radius: 0;
    }

    a {
        color: black;
    }

    a.unrecheable {
        cursor: not-allowed;
        color: grey !important;
    }
</style>
@endsection