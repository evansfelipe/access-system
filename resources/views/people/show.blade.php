@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col offset-lg-1 col-lg-10">
            <person-show personjson="{{ $person }}"></person-show>
        </div>
    </div>
@endsection