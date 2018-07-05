@extends('layouts.app')

@section('content')
    <div class="row">
        @panel(['size' => 'col offset-lg-2 col-lg-8'])
            @slot('header')
                {{ $person->fullName() }}
                <a href="{{ route('people.edit', $person->id) }}" class="float-right" title="Editar">
                    <i class="fas fa-user-edit"></i>
                </a>
            @endslot
            
            <div class="row d-flex align-items-center">
                <div class="col col-md-3 text-center">
                    <img src="{{empty($person->picture_name) ? asset('/pictures/no-image.jpg') : asset('/pictures/'.$person->picture_name)}}" class="img-fluid rounded-circle" alt="{{ $person->last_name }} image">
                </div>

                <div class="col col-md-3">

                    <i class="far fa-id-card"></i> {{ $person->cuil }}
                    <br>

                    @switch($person->sex)
                        @case('F') <i class="fas fa-venus"></i> @break
                        @case('M') <i class="fas fa-mars"></i>  @break
                        @case('O') <i class="fas fa-genderless"></i> @break
                    @endswitch
                    {{ $person->sexToString() }} 

                    <br>

                    <i class="far fa-calendar-alt" title="Fecha de nacimiento"></i> {{ date('d-m-Y', strtotime($person->birthday)) }}
                </div>

                <div class="col-12 col-md-6">
                    @if($person->getActiveCard())
                        <div class="active-card">
                            <form class="title" action="{{route('cards.update', $person->getActiveCard()->id)}}" method="POST">
                                @csrf
                                @method("PUT")
                                <h4>Tarjeta de acceso</h4>
                                <input type="hidden" value="0" name="active">
                                @submitbutton(['color' => 'outline-light'])<i class="fas fa-ban"></i>@endsubmitbutton
                            </form>
                            <hr>
                            <div class="info">
                                <h3>{{ str_pad($person->getActiveCard()->id, 15, "0", STR_PAD_LEFT) }}</h3>
                                <small><b>{{ $person->fullName() }}</b></small>
                                <br>
                                <small><b>{{ $person->company->name }}</b></small>
                            </div>
                        </div>
                        <br>
                    @else
                        <div class="text-center">
                            <h4>No hay tarjeta activa</h4>
                            <form action="{{route('cards.store')}}" method="POST">
                                @csrf
                                <input name="person_id" type="hidden" value={{$person->id}}>
                                @submitbutton(['color' => 'primary', 'floatright' => false]) Crear @endsubmitbutton
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <br>

            <div class="row">
                <div class="col">
                    @if(count($person->getInactiveCards()) > 0)
                        <h5 class="text-center">Tarjetas inactivas</h5>
                        <ul class="list-group">
                            @foreach($person->getInactiveCards() as $card)
                                <li class="list-group-item">
                                    {{str_pad($card->id, 15, "0", STR_PAD_LEFT)}}
                                    <small class="float-right">{{ $card->updated_at }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <h5 class="text-center">No hay tarjetas inactivas</h5>
                    @endif
                </div>
            </div>
        @endpanel
    </div>
@endsection

@section('css')
<style>
    div.active-card {
        display: inline-block;
        width: 100%;
        border-radius: 5px;
        color: white;
        background: linear-gradient(to right, #0d47a1 , #4285F4);
    }

    div.active-card > hr {
        height: 12px;
        border: 0;
        box-shadow: inset 0 12px 12px -12px rgba(0, 0, 0, 0.5);
        margin: 0;
    }

    div.active-card > form.title {
        padding: 5%;
        padding-bottom: 5px;
    }

    div.active-card > form.title > h4 {
        display: inline-block;
    }

    div.active-card > div.info {
        padding: 5%;
        padding-top: 0;
    }
</style>
@endsection