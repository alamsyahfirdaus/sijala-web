<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SIJALA | Laporan {{ ucwords($title) }}</title>
    <link rel="icon" href="{{ url('image/logo.png') }}">
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: DejaVu Sans;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 24px;
        }

        .period {
            text-align: center;
            margin-bottom: 15px;
            font-size: 11px;
        }

        .title,
        .period {
            page-break-after: avoid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        tbody {
            display: table-row-group;
        }

        tr {
            page-break-inside: avoid;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: middle;
        }

        th {
            text-align: center;
            font-weight: bold;
            background-color: #d9d9d9;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .no-column {
            width: 35px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="title">
        LAPORAN {{ strtoupper($title) }}
    </div>

    @if ($startDate && $endDate)
        <div class="period">
            Periode:
            {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}
            s.d.
            {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
        </div>
    @endif

    <table>

        <thead>
            <tr>

                @foreach (array_keys($data->first() ?? []) as $heading)
                    @if ($heading == 'No')
                        <th class="no-column">
                            {{ $heading }}
                        </th>
                    @else
                        <th>
                            {{ $heading }}
                        </th>
                    @endif
                @endforeach

            </tr>
        </thead>

        <tbody>

            @foreach ($data as $row)
                <tr>

                    @foreach ($row as $key => $value)
                        @if ($key == 'No')
                            <td class="text-center">
                                {{ $value }}
                            </td>
                        @elseif (is_numeric($value))
                            <td class="text-center">
                                {{ $value }}
                            </td>
                        @else
                            <td class="text-left">
                                {{ $value }}
                            </td>
                        @endif
                    @endforeach

                </tr>
            @endforeach

        </tbody>

    </table>

</body>

</html>
