@extends('layouts.app')

@section('content')
    <div class="row">
        @panel(['size' => 'col offset-lg-1 col-lg-10'])
            @slot('header', 'Información laboral de ' . $person_name )
            <form action="{{route('person-creation.working-information.store')}}" method="POST" class="no-select">
                @include('forms.save-people-companies')

                <hr>
                <div class="form-row">
                    <div class="col-6">
                        <button type="button" class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i> Cancelar</button>
                    </div>
                    <div class="col-6 text-right">
                        @submitbutton(['id' => 'create-person-company-submit']) Siguiente <i class="fas fa-angle-double-right"></i> @endsubmitbutton
                    </div>
                </div>
            </form>
        @endpanel
    </div>
@endsection