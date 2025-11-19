<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sales Report - 3Vours</title>
    <style>
        @page {
            size: A4;
            margin: 20px;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 10px;
            color: #333;
            line-height: 1.4;
        }

        .container {
            padding: 20px;
            margin: 0 auto;
            max-width: 900px;
        }

        .about-3vours {
            background: #ffe9e0;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border: 1px solid #ffccb9;
            text-align: center;
        }

        .logo-text {
            font-size: 32px;
            font-weight: bold;
            color: #d65839;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .about-3vours h2 {
            margin: 0 0 15px 0;
            font-size: 20px;
            color: #d65839;
        }

        .about-3vours p {
            margin: 8px 0;
            font-size: 14px;
            text-align: justify;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 20px;
            background: linear-gradient(135deg, #d65839 0%, #ff8a65 100%);
            border-radius: 12px;
            color: white;
        }

        .title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .title-2 {
            font-size: 20px;
            font-weight: bold;
            opacity: 0.9;
        }

        /* Period */
        .period {
            background: #ffccb9;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        .separator {
            height: 2px;
            background: #d65839;
            margin: 20px 0;
            opacity: 0.3;
        }

        /* Company Info */
        .report-info {
            background: #f8f8f8;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }

        .report-info h4 {
            margin: 0 0 15px 0;
            color: #d65839;
            border-bottom: 2px solid #ffccb9;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
        }

        th, td {
            padding: 10px 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #ffe0d6;
            font-weight: bold;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
            padding: 15px;
            background: #f8f8f8;
            border-radius: 8px;
            border-top: 2px solid #ffccb9;
        }

        .w-half {
            width: 50%;
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-grid {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            gap: 15px;
        }

        .summary-card {
            flex: 1;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            border-top: 4px solid #d65839;
        }

        .summary-card h3 {
            margin: 0 0 8px 0;
            font-size: 14px;
            color: #666;
        }

        .summary-card .value {
            font-size: 18px;
            font-weight: bold;
            color: #d65839;
        }

        .total-highlight {
            background: #d65839;
            color: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
            font-size: 16px;
        }

        .flavor-tags {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 15px 0;
        }

        .flavor-tag {
            background: #d65839;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="about-3vours">
            <div class="logo-text">3VOURS</div>
            
            <div class="flavor-tags">
                <span class="flavor-tag">&#128293; PEDAS</span>
                <span class="flavor-tag">&#128167; SEGAR</span>
                <span class="flavor-tag">&#127855; MANIS</span>
            </div>            

            <h2 style="text-align: center">Three Flavours Experience</h2>
            <p style="text-align: center">
                3Vours berasal dari konsep "Three Flavours": pedas, segar, dan manis.
                Brand ini merefleksikan kebiasaan makan orang Indonesia—mulai dari makanan pedas,
                disusul minuman penyegar, dan ditutup dessert manis.
            </p>
            <p style="text-align: center">
                Tiga menu utama kami adalah Bakso Mercon yang pedas nendang, Mocktail Non-Alcoholic
                yang colorful dan menyegarkan, serta Stuffed Dessert Roti sebagai penutup yang manis dan lembut.
            </p>
            <p style="text-align: center">
                Visi kami adalah menjadi brand kuliner yang unik dan terbaik melalui bahan berkualitas,
                pelayanan cepat & ramah, inovasi menu, serta menjaga kebersihan & estetika produk.
            </p>
        </div>

        <div class="header">
            <div class="title">SALES REPORT</div>
            <div class="title-2">3VOURS - Three Flavours Experience</div>
        </div>

        <div class="period">
            <strong>REPORT PERIOD:</strong>
            @if ($start_date && $end_date)
                {{ \Carbon\Carbon::parse($start_date)->format('d M Y') }} to {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}
            @else
                FULL RANGE (All Time)
            @endif
        </div>

        <div class="summary-grid">
            <div class="summary-card">
                <h3>TOTAL TRANSACTIONS</h3>
                <div class="value">{{ $sales->count() }}</div>
            </div>
            <div class="summary-card">
                <h3>TOTAL ITEMS SOLD</h3>
                <div class="value">{{ $sales->sum('quantity') }}</div>
            </div>
            <div class="summary-card">
                <h3>TOTAL REVENUE</h3>
                <div class="value">Rp {{ number_format($sales->sum('total_price'), 0, ',', '.') }}</div>
            </div>
            <div class="summary-card">
                <h3>AVG. TRANSACTION</h3>
                <div class="value">Rp {{ number_format($sales->avg('total_price'), 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="separator"></div>

        <div class="report-info">
            <table>
                <tr>
                    <td class="w-half">
                        <h4>Store Information:</h4>
                        <div><strong>Company:</strong> 3VOURS</div>
                        <div><strong>Concept:</strong> Three Flavours Experience</div>
                        <div><strong>Email:</strong> 3vours@gmail.com</div>
                        <div><strong>Address:</strong> Bekasi, Indonesia</div>
                        <div><strong>Phone:</strong> +62 857-1475-4148</div>
                        <div><strong>Instagram:</strong> @3vours</div>
                    </td>
                    <td class="w-half">
                        <h4>Report Information:</h4>
                        <div><strong>Generated:</strong> {{ \Carbon\Carbon::now()->format('d M Y - H:i') }}</div>
                        <div><strong>Report Type:</strong> Sales History Report</div>
                        <div><strong>Status:</strong> Completed Transactions</div>
                        <div><strong>Total Records:</strong> {{ $sales->count() }} transactions</div>
                        <div><strong>Report Period:</strong> 
                            @if ($start_date && $end_date)
                                {{ \Carbon\Carbon::parse($start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d M Y') }}
                            @else
                                All Time
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <h3 style="color: #d65839; border-bottom: 2px solid #ffccb9; padding-bottom: 8px;">
            📊 SALES TRANSACTION DETAILS
        </h3>
        <table>
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th width="12%">Date</th>
                    <th width="18%">Customer</th>
                    <th width="20%">Product</th>
                    <th width="8%" class="text-center">Qty</th>
                    <th width="12%" class="text-right">Unit Price</th>
                    <th width="15%" class="text-right">Total Price</th>
                    <th width="10%">Staff</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sales as $sale)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            <div style="font-weight: bold;">{{ $sale->created_at->format('d M Y') }}</div>
                            <div style="font-size: 11px; color: #666;">{{ $sale->created_at->format('H:i') }}</div>
                        </td>
                        <td>
                            <div style="font-weight: bold;">{{ $sale->customer_name ?? 'Walk-in Customer' }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 500;">{{ $sale->product->name }}</div>
                            <div style="font-size: 11px; color: #666;">
                                {{ Str::limit($sale->product->description, 25) }}
                            </div>
                        </td>
                        <td class="text-center">
                            <span style="background: #ffe0d6; padding: 4px 8px; border-radius: 6px; font-weight: bold;">
                                {{ $sale->quantity }}
                            </span>
                        </td>
                        <td class="text-right">
                            Rp {{ number_format($sale->product->price, 0, ',', '.') }}
                        </td>
                        <td class="text-right" style="font-weight: bold; color: #d65839;">
                            Rp {{ number_format($sale->total_price, 0, ',', '.') }}
                        </td>
                        <td>
                            <div style="font-size: 11px; font-weight: 500;">{{ $sale->user->name }}</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-highlight">
            GRAND TOTAL REVENUE: Rp {{ number_format($sales->sum('total_price'), 0, ',', '.') }} 
            | {{ $sales->count() }} TRANSACTIONS | {{ $sales->sum('quantity') }} ITEMS SOLD
        </div>

        <div class="separator"></div>

        <div class="report-info">
            <table>
                <tr>
                    <td class="w-half">
                        <h4>Transaction Summary:</h4>
                        <div><strong>Total Transactions:</strong> {{ $sales->count() }}</div>
                        <div><strong>Total Items Sold:</strong> {{ $sales->sum('quantity') }}</div>
                        <div><strong>Average per Transaction:</strong> Rp {{ number_format($sales->avg('total_price'), 0, ',', '.') }}</div>
                        <div><strong>Unique Products:</strong> {{ $sales->groupBy('product_id')->count() }}</div>
                        <div><strong>Active Staff:</strong> {{ $sales->groupBy('user_id')->count() }}</div>
                    </td>

                    <td class="w-half">
                        <h4>Financial Summary:</h4>
                        <div><strong>Total Revenue:</strong> Rp {{ number_format($sales->sum('total_price'), 0, ',', '.') }}</div>
                        <div><strong>Highest Transaction:</strong> Rp {{ number_format($sales->max('total_price'), 0, ',', '.') }}</div>
                        <div><strong>Lowest Transaction:</strong> Rp {{ number_format($sales->min('total_price'), 0, ',', '.') }}</div>
                        <div><strong>Average Item Price:</strong> Rp {{ number_format($sales->sum('total_price') / $sales->sum('quantity'), 0, ',', '.') }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div style="font-weight: bold; margin-bottom: 5px;">3VOURS - Three Flavours Experience</div>
            <div>Generated automatically on {{ \Carbon\Carbon::now()->format('d M Y - H:i') }}</div>
            <div style="margin-top: 5px; font-size: 11px; color: #888;">
                &copy; {{ date('Y') }} 3VOURS. All rights reserved. | Confidential Business Document
            </div>
        </div>

    </div>
</body>
</html>