<!DOCTYPE html>
<html>
<head>
    <title>Cogs list</title>
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
                <th>Description</th>
                <th>Date</th>
                <th>Invoice total</th>
                <th>Name</th>
                <th>Rate</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Cogs</th>
                <th>Margin</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cogs as $cog)
                <tr>
                    <td>{{$cog['description']}}</td>
                    <td>{{$cog['date']}}</td>
                    <td>{{number_format($cog['invoice_total'], 2)}}</td>
                    <td>{{$cog['name']}}</td>
                    <td>{{$cog['rate']}}</td>
                    <td>{{$cog['unit']}}</td>
                    <td>{{$cog['quantity']}}</td>
                    <td>{{$cog['total']}}</td>
                    <td>{{$cog['cogs']}}</td>
                    <td>{{$cog['margin']}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
