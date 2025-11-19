@extends('layouts.app')

@section('title', 'Sales Data')

@section('content')
    <div class="sales-data-page">
        <div class="page-header">
            <h2>Sales Data</h2>
        </div>

        <div class="filter-section">
            <form method="GET" action="{{ route('sales.data') }}" class="filter-form">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="customer_name">Customer Name:</label>
                        <input type="text" name="customer_name" id="customer_name" value="{{ request('customer_name') }}"
                            placeholder="Search customer...">
                    </div>

                    <div class="filter-group">
                        <label for="status">Status:</label>
                        <select name="status" id="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="complete" {{ request('status') == 'complete' ? 'selected' : '' }}>Complete
                            </option>
                            <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled
                            </option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label for="start_date">Start Date:</label>
                        <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}">
                    </div>

                    <div class="filter-group">
                        <label for="end_date">End Date:</label>
                        <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}">
                    </div>

                    <div class="filter-group">
                        <button type="submit" class="btn-primary">Apply Filters</button>
                        <button type="button" class="btn-secondary" onclick="resetFilters()">Reset</button>
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
                        <th>Nama Produk</th>
                        <th>Quantity</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Waktu</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $sale)
                        <tr>
                            <td>#{{ $sale->id }}</td>
                            <td>
                                <strong>{{ $sale->customer_name ?? 'N/A' }}</strong>
                                @if (Auth::user()->isPetugas() || Auth::user()->isAdmin())
                                    <br><small>By: {{ $sale->user->name }}</small>
                                @endif
                            </td>
                            <td>{{ $sale->product->name }}</td>
                            <td>
                                @if (Auth::user()->isPetugas() || Auth::user()->isAdmin())
                                    <input type="number" value="{{ $sale->quantity }}" min="1"
                                        onchange="updateSale({{ $sale->id }}, this.value, '{{ $sale->status }}')">
                                @else
                                    {{ $sale->quantity }}
                                @endif
                            </td>
                            <td>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                            <td>
                                @if (Auth::user()->isPetugas() || Auth::user()->isAdmin())
                                    <select onchange="updateSale({{ $sale->id }}, {{ $sale->quantity }}, this.value)">
                                        <option value="pending" {{ $sale->status == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="complete" {{ $sale->status == 'complete' ? 'selected' : '' }}>
                                            Complete</option>
                                        <option value="canceled" {{ $sale->status == 'canceled' ? 'selected' : '' }}>
                                            Canceled</option>
                                    </select>
                                @else
                                    <span class="status-{{ $sale->status }}">{{ ucfirst($sale->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $sale->created_at->format('d M Y') }}</small><br>
                                <small>{{ $sale->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                @if (Auth::user()->isPetugas() || Auth::user()->isAdmin())
                                    <button class="btn-delete" onclick="deleteSale({{ $sale->id }})">Delete</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No sales data found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($sales->hasPages())
                <div class="pagination">
                    {{ $sales->links() }}
                </div>
            @endif

            <!-- Summary -->
            <div class="summary-section">
                <div class="summary-card">
                    <h4>Total Sales: {{ $sales->total() }}</h4>
                    <p>Showing {{ $sales->firstItem() ?? 0 }} - {{ $sales->lastItem() ?? 0 }} of {{ $sales->total() }}
                        records</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function updateSale(saleId, quantity, status) {
            fetch(`/sales/${saleId}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    quantity: parseInt(quantity),
                    status: status
                })
            }).then(response => {
                if (response.ok) {
                    location.reload();
                } else {
                    alert('Error updating sale');
                }
            }).catch(error => {
                console.error('Error:', error);
                alert('Error updating sale');
            });
        }

        function deleteSale(saleId) {
            if (confirm('Are you sure you want to delete this sale?')) {
                fetch(`/sales/${saleId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Error deleting sale');
                    }
                }).catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting sale');
                });
            }
        }

        function resetFilters() {
            // Reset form values
            document.getElementById('status').value = '';
            document.getElementById('start_date').value = '';
            document.getElementById('end_date').value = '';

            // Submit the form to reset filters
            document.querySelector('.filter-form').submit();
        }

        // Set max date for end_date to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('start_date').max = today;
            document.getElementById('end_date').max = today;

            // Set end_date min based on start_date
            document.getElementById('start_date').addEventListener('change', function() {
                document.getElementById('end_date').min = this.value;
            });
        });
    </script>
@endsection
