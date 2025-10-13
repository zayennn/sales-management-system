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
    public function index(): View
    {
        $sales = Sale::with(['product', 'user'])->get();
        return view('dashboard.sales-data', compact('sales'));
    }

    public function history(): View
    {
        $sales = Sale::with(['product', 'user'])
            ->where('status', 'complete')
            ->get();
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

    public function exportPDF()
    {
        $sales = Sale::with(['product', 'user'])
            ->where('status', 'complete')
            ->get();

        $pdf = Pdf::loadView('pdf.sales-history', compact('sales'));
        return $pdf->download('sales-history-' . date('Y-m-d') . '.pdf');
    }
}