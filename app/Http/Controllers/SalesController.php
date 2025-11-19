<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesController extends Controller
{
    public function index(Request $request): View
    {
        $query = Sale::with(['product', 'user'])
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Filter by customer name
        if ($request->has('customer_name') && $request->customer_name != '') {
            $query->where('customer_name', 'like', '%' . $request->customer_name . '%');
        }

        $sales = $query->paginate(10);
        $statuses = ['pending', 'complete', 'canceled'];

        return view('dashboard.sales-data', compact('sales', 'statuses'));
    }

    public function history(Request $request): View
    {
        $query = Sale::with(['product', 'user'])
            ->where('status', 'complete')
            ->orderBy('created_at', 'desc');

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $sales = $query->paginate(10);

        return view('dashboard.sales-history', compact('sales'));
    }

    public function store(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);

        foreach ($cart as $item) {
            $product = Product::find($item['id']);
            
            if ($product) {
                Sale::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'total_price' => $item['price'] * $item['quantity'],
                    'status' => 'pending',
                ]);
            }
        }

        session()->forget('cart');

        return redirect()->route('sales.data')->with('success', 'Order confirmed successfully!');
    }

    public function update(Request $request, Sale $sale): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:pending,complete,canceled',
        ]);

        $sale->update([
            'quantity' => $validated['quantity'],
            'total_price' => $sale->product->price * $validated['quantity'],
            'status' => $validated['status'],
        ]);

        return redirect()->route('sales.data')->with('success', 'Sale updated successfully!');
    }

    public function destroy(Sale $sale): RedirectResponse
    {
        $sale->delete();
        return redirect()->route('sales.data')->with('success', 'Sale deleted successfully!');
    }

    public function exportPDF(Request $request)
    {
        $query = Sale::with(['product', 'user'])
            ->where('status', 'complete')
            ->orderBy('created_at', 'desc');
    
        // Filter by date range for export
        $start_date = $request->start_date;
        $end_date = $request->end_date;
    
        if ($start_date && $start_date != '') {
            $query->whereDate('created_at', '>=', $start_date);
        }
    
        if ($end_date && $end_date != '') {
            $query->whereDate('created_at', '<=', $end_date);
        }
    
        $sales = $query->get();
    
        // Format dates for display
        $formatted_start_date = $start_date ? \Carbon\Carbon::parse($start_date)->format('d M Y') : null;
        $formatted_end_date = $end_date ? \Carbon\Carbon::parse($end_date)->format('d M Y') : null;
    
        $pdf = Pdf::loadView('pdf.sales-history', compact('sales', 'start_date', 'end_date', 'formatted_start_date', 'formatted_end_date'));
        return $pdf->download('sales-report-' . date('Y-m-d') . '.pdf');
    }
}