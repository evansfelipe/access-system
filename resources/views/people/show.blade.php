@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col offset-lg-2 col-lg-8">
            <person-show personjson="{{ $person }}"></person-show>
        </div>
    </div>
@endsection