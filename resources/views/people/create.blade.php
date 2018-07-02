@extends('layouts.components.card')

@section('card-size', 'offset-md-2 col col-md-8')

@section('card-title', 'Nueva Persona')

@section('card-body')
    <form action="{{route('people.store')}}" method="POST">
        @csrf
        <div class="form-row">
            <div class="col-xs-12 col-md">
                @include('partials.input',['type' => 'text', 'label' => 'Apellido', 'name' => 'last_name'])
            </div>
            <div class="col-xs-12 col-md">
                @include('partials.input',['type' => 'text', 'label' => 'Nombre', 'name' => 'name'])
            </div>
        </div>
        <div class="form-row">
            <div class="col-xs-12 col-md">
                @include('partials.input',['type' => 'text', 'label' => 'CUIL', 'name' => 'cuil'])
            </div>
            <div class="col-xs-12 col-md">
                @include('partials.select', [
                    'label' => 'Sexo',
                    'name' => 'sex',
                    'options' => [
                        ['value' => 'F', 'text' => 'Femenino'],
                        ['value' => 'M', 'text' => 'Masculino'],
                        ['value' => 'O', 'text' => 'Otro'],
                    ]
                ])
            </div>
        </div>
        <br>
        <button type="submit" class="btn btn-success float-right text-uppercase font-weight-bold">Guardar</button>
    </form>
@endsection