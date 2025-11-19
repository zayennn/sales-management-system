@extends('layouts.app')

@section('title', 'Sales History')

@section('content')
    <div class="sales-history-page">
        <div class="page-header">
            <h2>Sales History</h2>
            <a href="{{ route('sales.export.pdf') }}?{{ http_build_query(request()->query()) }}" class="btn-primary">
                Export PDF
            </a>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('sales.history') }}" class="filter-form">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="start_date">Start Date:</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                            onchange="this.form.submit()">
                    </div>

                    <div class="filter-group">
                        <label for="end_date">End Date:</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                            onchange="this.form.submit()">
                    </div>

                    <div class="filter-group">
                        <button type="button" class="btn-secondary" onclick="resetFilters()">Reset Filters</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Customer Name</th>
                        <th>Produk</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td>#{{ $sale->id }}</td>
                            <td>
                                <strong>{{ $sale->customer_name ?? 'N/A' }}</strong>
                                <br><small>By: {{ $sale->user->name }}</small>
                            </td>
                            <td>{{ $sale->product->name }}</td>
                            <td>{{ $sale->quantity }}</td>
                            <td>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                            <td>
                                <small>{{ $sale->created_at->format('d M Y') }}</small><br>
                                <small>{{ $sale->created_at->format('H:i') }}</small>
                            </td>
                            <td><span class="status-complete">{{ ucfirst($sale->status) }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No sales history found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            @if ($sales->hasPages())
                <div class="pagination">
                    {{ $sales->links() }}
                </div>
            @endif

            <!-- Summary -->
            <div class="summary-section">
                <div class="summary-card">
                    <h4>Total Completed Sales: {{ $sales->total() }}</h4>
                    <p>Total Revenue: Rp {{ number_format($sales->sum('total_price'), 0, ',', '.') }}</p>
                    <p>Showing {{ $sales->firstItem() ?? 0 }} - {{ $sales->lastItem() ?? 0 }} of {{ $sales->total() }}
                        records</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function resetFilters() {
            document.getElementById('start_date').value = '';
            document.getElementById('end_date').value = '';

            document.querySelector('.filter-form').submit();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('start_date').max = today;
            document.getElementById('end_date').max = today;

            document.getElementById('start_date').addEventListener('change', function() {
                document.getElementById('end_date').min = this.value;
            });
        });
    </script>
@endsection
