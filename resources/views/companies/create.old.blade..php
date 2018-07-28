@extends('layouts.app')

@section('content')

    <company-creation></company-creation>
    <hr>

    <div class="row">
        @panel(['size' => 'col offset-lg-1 col-lg-10'])
            @slot('header', 'Nueva empresa')
            <form action="{{route('companies.store')}}" method="POST" class="no-select">
                @csrf
                {{-- Name & area --}}
                <div class="form-row">
                    @formitem(['label' => 'Razón social', 'col' => 'col-md-6'])
                        <div class="col">
                            @input(['type' => 'text', 'name' => 'name', 'required' => true])@endinput
                        </div>
                    @endformitem
                    @formitem(['label' => 'Rubro', 'col' => 'col-md-6'])
                        <div class="col">
                            @input(['type' => 'text', 'name' => 'area', 'required' => true])@endinput
                        </div>
                    @endformitem
                </div>
                {{--  Cuit & expiration date --}}
                <div class="form-row">
                    @formitem(['label' => 'CUIT', 'col' => 'col-md-6'])
                        <div class="col">
                        @input(['type' => 'text', 'name' => 'cuit', 'required' => true])@endinput
                        </div>
                    @endformitem
                    @formitem(['label' => 'Vencimiento', 'col' => 'col-md-6'])
                        <div class="col">
                        @input(['type' => 'date', 'name' => 'expiration', 'required' => true])@endinput
                        </div>
                    @endformitem
                </div>
                <hr>
                {{-- Phone & fax --}}
                <div class="form-row">
                    @formitem(['label' => 'Teléfono', 'col' => 'col-md-6'])
                        <div class="col">
                        @input(['type' => 'text', 'name' => 'phone', 'required' => true])@endinput
                        </div>
                    @endformitem
                    @formitem(['label' => 'Fax', 'col' => 'col-md-6'])
                        <div class="col">
                        @input(['type' => 'text', 'name' => 'fax'])@endinput
                        </div>
                    @endformitem
                </div>
                {{-- Email & web --}}
                <div class="form-row">
                    @formitem(['label' => 'Mail', 'col' => 'col-md-6'])
                        <div class="col">
                        @input(['type' => 'email', 'name' => 'email', 'required' => true])@endinput
                        </div>
                    @endformitem
                    @formitem(['label' => 'Web', 'col' => 'col-md-6'])
                        <div class="col">
                        @input(['type' => 'text', 'name' => 'web'])@endinput
                        </div>
                    @endformitem
                </div>
                {{-- Residency component with necessaty inputs --}}
                @residency()@endresidency
                {{-- Submit --}}
                @submitbutton(['id' => 'create-company-submit', 'color' => 'success']) Guardar @endsubmitbutton
            </form>
        @endpanel
    </div>
@endsection