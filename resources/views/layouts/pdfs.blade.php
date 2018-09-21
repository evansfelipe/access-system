<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        /* body { font-family: Arial, Helvetica, sans-serif }
        div.section {
            border-radius: 3px;
            border: 1px solid rgb(222,222,222);
            margin-bottom: 2em;
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
        } */

        div.panel + div.panel {
            page-break-after: always;
        }
        div.panel-body {
            padding: 0;
        }

        table.table {
            margin-bottom: 0;
        }
        table td.small {
            width: 35%;
        }

        table td.strong {
            width: 65%;
            font-weight: bold;
        }
    </style>
</head>
    <body>
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>