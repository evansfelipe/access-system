@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col">
            <people-index dusk="people-index-component" initialpeople="{{ $people }}"></people-index>
        </div>
    </div>
@endsection