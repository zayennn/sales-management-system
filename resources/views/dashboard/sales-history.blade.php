@extends('layouts.app')

@section('title', 'Sales History')

@section('content')
    <div class="sales-history-page">
        <div class="page-header">
            <h2>Sales History</h2>
            <a href="{{ route('sales.export.pdf') }}" class="btn-primary">Export PDF</a>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Produk</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        <tr>
                            <td>#{{ $sale->id }}</td>
                            <td>{{ $sale->product->name }}</td>
                            <td>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                            <td>{{ $sale->created_at->format('d M Y H:i') }}</td>
                            <td><span class="status-complete">{{ ucfirst($sale->status) }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection