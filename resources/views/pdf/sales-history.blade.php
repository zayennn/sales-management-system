<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sales History Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h4 {
            margin: 0;
        }

        .w-full {
            width: 100%;
        }

        .w-half {
            width: 50%;
        }

        .margin-top {
            margin-top: 1.25rem;
        }

        .footer {
            font-size: 0.875rem;
            padding: 1rem;
            background-color: rgb(241 245 249);
            text-align: center;
            margin-top: 2rem;
        }

        table {
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
        }

        table.products {
            font-size: 0.875rem;
            margin-top: 1.5rem;
        }

        table.products tr.header-row {
            background-color: rgb(96 165 250);
        }

        table.products th {
            color: #ffffff;
            padding: 0.75rem;
            text-align: left;
            font-weight: bold;
        }

        table tr.items {
            background-color: rgb(241 245 249);
        }

        table tr.items td {
            padding: 0.75rem;
            border-bottom: 1px solid #ddd;
        }

        .total {
            text-align: right;
            margin-top: 1.5rem;
            font-size: 1rem;
            font-weight: bold;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 5px;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #3b82f6;
        }

        .company-info {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1.5rem;
        }

        .report-info {
            background-color: #eef2ff;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1.5rem;
        }

        .text-blue {
            color: #3b82f6;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h1 class="text-blue">SALES HISTORY REPORT</h1>
        <p>Sales Management System</p>
    </div>

    <!-- Company Information -->
    <div class="company-info">
        <table class="w-full">
            <tr>
                <td class="w-half">
                    <div>
                        <h4>From:</h4>
                    </div>
                    <div><strong>Sales Management System</strong></div>
                    {{-- <div>123 Business Street</div> --}}
                    <img src="{{ asset('images/logo.png') }}" alt="">
                    <div>Depok, </div>
                    <div>Phone: (62) 123-4567</div>
                    <div>Instagram: @3.vours</div>
                </td>
                <td class="w-half text-right">
                    <div>
                        <h4>Report Information:</h4>
                    </div>
                    <div><strong>Report Date:</strong> {{ date('d M Y H:i') }}</div>
                    <div><strong>Period:</strong> All Time</div>
                    <div><strong>Report Type:</strong> Sales History</div>
                    <div><strong>Status:</strong> Completed Sales Only</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Sales Summary -->
    <div class="report-info">
        <table class="w-full">
            <tr>
                <td class="w-half">
                    <div>
                        <h4>Sales Summary:</h4>
                    </div>
                    <div><strong>Total Transactions:</strong> {{ $sales->count() }}</div>
                    <div><strong>Total Items Sold:</strong> {{ $sales->sum('quantity') }}</div>
                </td>
                <td class="w-half text-right">
                    <div>
                        <h4>Financial Summary:</h4>
                    </div>
                    <div><strong>Total Revenue:</strong> Rp {{ number_format($sales->sum('total_price'), 0, ',', '.') }}
                    </div>
                    <div><strong>Average per Transaction:</strong> Rp
                        {{ number_format($sales->avg('total_price'), 0, ',', '.') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Sales Details Table -->
    <div class="margin-top">
        <table class="products">
            <tr class="header-row">
                <th>Transaction ID</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
                <th>Date</th>
            </tr>

            @foreach ($sales as $sale)
                <tr class="items">
                    <td>#{{ $sale->id }}</td>
                    <td>{{ $sale->product->name }}</td>
                    <td class="text-center">{{ $sale->quantity }}</td>
                    <td>Rp {{ number_format($sale->product->price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                    <td>{{ $sale->created_at->format('d M Y H:i') }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- Total Summary -->
    <div class="total">
        <div><strong>Total Revenue: Rp {{ number_format($sales->sum('total_price'), 0, ',', '.') }}</strong></div>
        <div><strong>Total Transactions: {{ $sales->count() }}</strong></div>
        <div><strong>Total Items Sold: {{ $sales->sum('quantity') }}</strong></div>
    </div>

    <!-- Footer -->
    <div class="footer margin-top">
        <div><strong>Thank you for using Sales Management System</strong></div>
        <div>This is an automated report generated on {{ date('d M Y H:i') }}</div>
        <div>&copy; {{ date('Y') }} Sales Management System. All rights reserved.</div>
    </div>
</body>

</html>
