<!DOCTYPE html>
<html>
<head>
    <title>Balance sheet</title>
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
    <tr>
        <th colspan="14"><h1>{{$bill_item->name}} ({{$bill_item->sku}})</h1></th>
    </tr>
    <tr>
        <th rowspan="2">Date</th>
        <th colspan="3">Opening inventory</th>
        <th colspan="3">Purchase</th>
        <th colspan="3">Sale</th>
        <th colspan="3">Closing inventory</th>
        <th rowspan="2">Invoice number</th>
    </tr>

    <tr>
        <th>Rate</th>
        <th>Quantity</th>
        <th>Value</th>

        <th>Rate</th>
        <th>Quantity</th>
        <th>Value</th>

        <th>Rate</th>
        <th>Quantity</th>
        <th>Value</th>

        <th>Rate</th>
        <th>Quantity</th>
        <th>Value</th>
    </tr>

    @foreach($mergedItems as $item)
        <tr>
            <td>{{$item['date']}}</td>

            <td>{{$item['opening_date_rate']}}</td>
            <td>{{$item['opening_date_quantity']}}</td>
            <td>{{$item['opening_date_rate'] * $item['opening_date_quantity']}}</td>

            <td>{{$item['bill_item_rate']}}</td>
            <td>{{$item['bill_item_quantity']}}</td>
            <td>{{$item['bill_item_rate'] * $item['bill_item_quantity']}}</td>

            <td>{{$item['sale_item_rate']}}</td>
            <td>{{$item['sale_item_quantity']}}</td>
            <td>{{$item['sale_item_rate'] * $item['sale_item_quantity']}}</td>

            <td>{{$item['closing_date_rate']}}</td>
            <td>{{$item['closing_date_quantity']}}</td>
            <td>{{$item['closing_date_rate'] * $item['closing_date_quantity']}}</td>

            <td>{{$item['invoices']}}</td>
        </tr>
    @endforeach

</table>

</body>
</html>
