@extends('layouts.person-creation', ['step' => 3, 'route' => route('person-creation.first-card.store')])

@section('form')
    <div class="form-row d-flex align-items-center">
        <div class="offset-3 col-6 offset-lg-1 col-lg-4">
            @input(['type' => 'text', 'name' => 'number', 'placeholder' => 'Ingrese el número de la tarjeta'])@endinput
            <br>
            <div class="active-card">
                <div class="title">
                    <h4>Tarjeta de acceso</h4>
                </div>
                <hr>
                <div class="info">
                    <h3 id="display">xxxx-xxxx</h3>
                    <small><b>{{ $person_name }}</b></small>
                    <br>
                    <small><b>{{ $company_name }}</b></small>
                    <br>
                </div>
            </div>
        </div>
        <div class="col-12 offset-lg-1 col-lg-5">
            <div class="form-row">
                @formitem(['label' => 'Riesgo'])
                    <div class="col">
                        @select([
                            'name' => 'risk',
                            'placeholder' => 'Seleccione nivel de riesgo',
                            'options' => [
                                ['value' => '1', 'text' => 'Nivel 1'],
                                ['value' => '2', 'text' => 'Nivel 2']
                            ]
                        ])
                        @endselect
                    </div>
                @endformitem
            </div>
            <div class="form-row">
                @formitem(['label' => 'Validez'])
                    <div class="col-6">
                        <small>Desde:</small>
                        @input(['type' => 'date', 'name' => 'from'])@endinput
                    </div>
                    <div class="col-6">
                        <small>Hasta:</small>
                        @input(['type' => 'date', 'name' => 'until'])@endinput
                    </div>
                @endformitem
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    $(window).ready(function() {
        $('input[name=number]').on('change paste keyup', function() {
            let number = $(this).val();
            if(number) {
                $('#display').html(number);
            }
            else {
                $('#display').html("xxxx-xxxx");
            }
        });
    })
</script>
@endsection

@section('css')
@parent
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

    div.active-card > div.title {
        padding: 5%;
        padding-bottom: 5px;
    }

    div.active-card > div.title > h4 {
        display: inline-block;
    }

    div.active-card > div.info {
        padding: 5%;
        padding-top: 0;
    }
</style>
@endsection