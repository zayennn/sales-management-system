@extends('layouts.app')

@section('title', 'Sales Data')

@section('content')
    <div class="sales-data-page">
        <div class="page-header">
            <h2>Sales Data</h2>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Nama Produk</th>
                        <th>Quantity</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        <tr>
                            <td>#{{ $sale->id }}</td>
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
                                @if (Auth::user()->isPetugas() || Auth::user()->isAdmin())
                                    <button class="btn-delete" onclick="deleteSale({{ $sale->id }})">Delete</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                }
            });
        }

        function deleteSale(saleId) {
            if (confirm('Are you sure you want to delete this sale?')) {
                fetch(`/sales/${saleId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                });
            }
        }
    </script>
@endsection