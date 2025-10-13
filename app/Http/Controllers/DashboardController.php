<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalProducts = Product::count();
        $totalSalesCompleted = Sale::where('status', 'complete')->count();
        $totalPendingTransactions = Sale::where('status', 'pending')->count();

        return view('dashboard.home', compact(
            'totalProducts',
            'totalSalesCompleted',
            'totalPendingTransactions'
        ));
    }
}