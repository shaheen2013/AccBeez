<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }
    </style>
</head>
<body>

<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>SKU</th>
        @php
            $months = []

        @endphp
        @foreach($distinct_months as $month)
            @php(array_push($months, $month))
            <th>{{$month}}</th>
        @endforeach

    </tr>
    </thead>
    <tbody>
    @foreach ($register_list as $register)
        <tr>
            <td>{{ $register['name'] }}</td>
            <td>{{ $register['sku'] }}</td>
            @foreach($months as $month)
                @php($monthFormat = 'month-'.$month)
                <td>{{$register[$monthFormat]}}</td>
            @endforeach

        </tr>
    @endforeach

    {{--    <tr>--}}
    {{--        <td style="border: none;" colspan="2"></td>--}}
    {{--        <td style="border: none;">Invoice Total</td>--}}
    {{--        <td style="text-align: right;">{{ number_format($bom->invoice_total, 2) }}</td>--}}
    {{--    </tr>--}}
    </tbody>
</table>

</body>
</html>
