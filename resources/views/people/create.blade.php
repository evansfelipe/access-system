@extends('layouts.app')

@section('content')
    <div class="row">
        @panel(['size' => 'col offset-lg-1 col-lg-10'])
            @slot('header', 'Información personal')
            <form enctype="multipart/form-data" action="{{ route('person-creation.personal-information.store') }}" method="POST" class="no-select">
                @include('forms.save-person')
                <hr>
                <div class="form-row">
                    <div class="col-6">
                        <button type="button" class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i> Cancelar</button>
                    </div>
                    <div class="col-6 text-right">
                        @submitbutton(['id' => 'create-person-submit']) Siguiente <i class="fas fa-angle-double-right"></i> @endsubmitbutton
                    </div>
                </div> 
            </form>
        @endpanel
    </div>
@endsection
{{-- @input(['type' => 'file', 'label' => 'Foto', 'name' => 'picture'])@endinput --}}