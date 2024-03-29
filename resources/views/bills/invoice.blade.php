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
    <h4>Bill Invoice</h4>
    <p><strong>Description:</strong> {{ $bill->description }}</p>
    <p><strong>Date:</strong> {{ $bill->date }}</p>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bill->billItems as $item)
                <tr>
                    <td>{{ $item->sku }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->rate, 2, '.', ',') }}</td>
                    <td style="text-align: right;">{{ number_format($item->total, 2, '.', ',') }}</td>
                </tr>
            @endforeach

            <tr>
                <td style="border: none;" colspan="2"></td>
                <td style="border: none;">Invoice Total</td>
                <td style="text-align: right;">{{ number_format($bill->invoice_total, 2) }}</td>
            </tr>
        </tbody>
    </table>

</body>
</html>
