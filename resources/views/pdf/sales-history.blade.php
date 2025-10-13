<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sales History Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Sales History Report</h1>
        <p>Generated on: {{ date('d M Y H:i') }}</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID Transaksi</th>
                <th>Produk</th>
                <th>Quantity</th>
                <th>Total Harga</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sales as $sale)
                <tr>
                    <td>#{{ $sale->id }}</td>
                    <td>{{ $sale->product->name }}</td>
                    <td>{{ $sale->quantity }}</td>
                    <td>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                    <td>{{ $sale->created_at->format('d M Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total Sales: {{ $sales->count() }}<br>
        Total Revenue: Rp {{ number_format($sales->sum('total_price'), 0, ',', '.') }}
    </div>
</body>

</html>