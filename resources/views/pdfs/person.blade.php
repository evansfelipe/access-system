@extends('layouts.pdfs')
@section('content')
    {{-- <div class="mb-1">
        <img src="{{ $picture }}">
        <div class="d-inline-block">
            <h1 class="d-inline">{{ $full_name }}</h1>
            <br>
            <h2 class="d-inline">{{ $register_number }}</h2>
        </div>
    </div> --}}

    {{-- Personal information section --}}
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Información Personal</h4>
        </div>
        <div class="panel-body">
            <table class="table">
                <tbody>
                    @foreach($personal_information as $key => $value)
                        <tr>
                            <td class="small">{{ $key }}</td>
                            <td class="strong"><strong>{{ $value }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- Jobs section --}}
    @foreach($jobs as $job)
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>
                {{ $job->company_name }} @if(isset($job->company_name)) | @endif {{ $job->activity_name }}
            </h4>
        </div>
        <div class="panel-body">
                <table class="table">
                    <tbody>
                        @foreach($job->data as $key => $value)
                            <tr>
                                <td class="small">{{ $key }}</td>
                                <td class="strong">{{ $value }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2">Tarjetas</td>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Valida desde</th>
                            <th>Valida hasta</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($job->cards as $card)
                            <tr>
                                <td>{{ $card->number }}</td>
                                <td>{{ $card->from }}</td>
                                <td>{{ $card->until }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>
    @endforeach
@endsection