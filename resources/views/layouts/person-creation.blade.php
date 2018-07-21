@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col offset-lg-1 col-lg-10">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link no-select {{ $step == 0 ? 'active' : 'inactive' }}" 
                    href="{{ route('person-creation.personal-information.create') }}">
                    <i class="fas fa-user"></i>
                    Información personal
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link no-select {{ $step == 1 ? 'active' : 'inactive' }}"
                    href="{{ route('person-creation.working-information.create') }}">
                    <i class="fas fa-briefcase"></i>
                    Información laboral
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link no-select {{ $step == 2 ? 'active' : 'inactive' }}"
                    href="{{ route('person-creation.assign-vehicles.create') }}">
                    <i class="fas fa-car"></i> 
                    Vehículos
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link no-select {{ $step == 3 ? 'active' : 'inactive' }}"
                    href="{{ route('person-creation.first-card.create') }}">
                    <i class="fas fa-id-card"></i>
                    Tarjeta
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link no-select {{ $step == 4 ? 'active' : 'inactive' }}"
                    href="{{ route('person-creation.documentation.create') }}">
                    <i class="fas fa-file-alt"></i>
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
        color: black  !important;
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

    a.inactive {
        color: grey !important;
    }
</style>
@endsection