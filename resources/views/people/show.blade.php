@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col offset-lg-1 col-lg-10">
            {{\Debugbar::info($person)}}
            <person-show personjson="{{ $person }}"/>
        </div>
    </div>
@endsection