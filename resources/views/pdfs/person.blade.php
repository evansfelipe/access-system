<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }
        div.section {
            border-radius: 3px;
            border: 1px solid rgb(222,222,222);
        }

        div.section + div.section {
            margin-top: 2em;
        }

        div.section > div.section-title {
            font-size: 150%;
            border-bottom: 1px solid rgb(222,222,222);
            padding: .5em 1em;
        }

        div.section > div.section-content {
            padding: 1em;
        }

        img {
            max-width: 200px;
            height: auto;
        }

        table {
            width: 100%;
        }

        .mb-1 {
            margin-bottom: 1em;
        }

        .d-inline {
            display: inline;
        }

        .d-inline-block {
            display: inline-block;
        }

        table.information td {
            width: 50%;
        }

        table.table {
            border-radius: 3px;
            border: 1px solid rgb(222,222,222);
        }

        table.table > thead > tr {
            border-bottom: 1px solid rgb(222,222,222);
        }

        table.table > thead > tr > th {
            padding: 0.75em;
            text-align: left;
        }

        table.table > tbody > tr > td {
            border-bottom: 1px solid rgba(222,222,222, .25);
            padding: 0.5em;
        }

    </style>
</head>
    <body>
        {{-- <div class="mb-1">
            <img src="{{ $picture }}">
            <div class="d-inline-block">
                <h1 class="d-inline">{{ $full_name }}</h1>
                <br>
                <h2 class="d-inline">{{ $register_number }}</h2>
            </div>
        </div> --}}

        {{-- Personal information section --}}
        <div class="section">
            <div class="section-title">
                Información personal
            </div>
            <div class="section-content">
                <table class="information">
                    <tbody>
                        @foreach($personal_information as $key => $value)
                            <tr>
                                <td>{{ $key }}</td>
                                <td><strong>{{ $value }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Jobs section --}}
        @foreach($jobs as $job)
        <div class="section">
            <div class="section-title">
                {{ $job->company_name }} @if(isset($job->company_name)) | @endif {{ $job->activity_name }}
            </div>
            <div class="section-content">
                <table class="information">
                    <tbody>
                        @foreach($job->data as $key => $value)
                            <tr>
                                <td>{{ $key }}</td>
                                <td><strong>{{ $value }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <table class="table">
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

    </body>
</html>