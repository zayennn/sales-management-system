<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function addToCart(Request $request, Product $product): JsonResponse
    {
        $cart = session()->get('cart', []);
        
        if ($product->qty < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Product is out of stock!'
            ], 400);
        }

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image,
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'cart_count' => count($cart),
            'message' => 'Product added to cart!'
        ]);
    }

    public function updateCart(Request $request, $productId): JsonResponse
    {
        $cart = session()->get('cart', []);
        $action = $request->input('action');
        
        if (isset($cart[$productId])) {
            if ($action === 'increment') {
                $cart[$productId]['quantity']++;
            } elseif ($action === 'decrement') {
                if ($cart[$productId]['quantity'] > 1) {
                    $cart[$productId]['quantity']--;
                } else {
                    unset($cart[$productId]);
                }
            }
            
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cart_count' => count($cart),
            'cart' => $cart
        ]);
    }

    public function removeFromCart(Request $request, $productId): JsonResponse
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cart_count' => count($cart),
            'message' => 'Product removed from cart!'
        ]);
    }

    public function getCart(): JsonResponse
    {
        $cart = session()->get('cart', []);
        return response()->json($cart);
    }

    public function confirmOrder(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);
    
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Cart is empty!');
        }
    
        $request->validate([
            'customer_name' => 'required|string|max:255'
        ]);
    
        foreach ($cart as $item) {
            $product = Product::find($item['id']);
            
            if ($product) {
                // Check stock availability
                if ($product->qty < $item['quantity']) {
                    return redirect()->back()->with('error', "Insufficient stock for {$product->name}");
                }
    
                // Create sale record with customer name
                Sale::create([
                    'user_id' => auth()->id(),
                    'customer_name' => $request->customer_name, // Tambahkan customer name
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'total_price' => $item['price'] * $item['quantity'],
                    'status' => 'pending',
                ]);
    
                // Update product stock
                $product->decrement('qty', $item['quantity']);
            }
        }
    
        // Clear cart
        session()->forget('cart');
    
        return redirect()->route('sales.data')->with('success', 'Order confirmed successfully!');
    }
}