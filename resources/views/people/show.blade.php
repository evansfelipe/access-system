@extends('layouts.components.card')

@section('card-size', 'offset-md-2 col col-md-8')

@section('card-title', $person->fullName())

@section('card-body')
    <div class="row">
        <div class="col-xs-12 col-md-4 text-center">
            <img src="https://blog.searchofficespace.com/wp-content/uploads/2017/10/no-image.jpg" class="img-fluid rounded-circle" alt="{{ $person->last_name }} image">
            <strong>{{ $person->sexToString() }}</strong> 
        </div>
        <div class="col-xs-12 offset-md-1 col-md-6">
            @if($person->getActiveCard())
                <div class="active-card">
                    <h6>Tarjeta de acceso</h6>
                        {{ str_pad($person->getActiveCard()->id, 15, "0", STR_PAD_LEFT) }}
                    </div>
                </div>
            @else
                <div class="text-center">
                    <h4>No hay tarjeta activa</h4>
                    <form action="{{route('cards.store')}}" method="POST">
                        @csrf
                        <input name="person_id" type="hidden" value={{$person->id}}>
                        <button type="submit" class="btn btn-primary btn-sm text-uppercase font-weight-bold">Crear</button>
                    </form>
                </div>
            @endif
    </div>

    <hr>
@endsection

@section('css')
<style>
    div.active-card {
        width: 100%;
        border: 1px solid #4285F4;
        padding: 10px;
        border-radius: 5px;
        border-left-width: 10px;
    }
</style>
@endsection