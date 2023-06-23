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
<h4>Bills List</h4>

<table>
    <thead>
    <tr>
        <th>Date</th>
        <th>Description</th>
        <th>Invoice total</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($bills as $item)
        <tr>
            <td>{{ $item->created_at->format('Y-m-d') }}</td>
            <td>{{ $item->description }}</td>
            <td>{{ number_format($item->invoice_total, 2, '.', ',') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
