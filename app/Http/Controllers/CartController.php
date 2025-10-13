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
        
        // Check if product has sufficient stock
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

    public function confirmOrder(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['success' => false, 'message' => 'Cart is empty!'], 400);
        }

        DB::beginTransaction();
        try {
            foreach ($cart as $item) {
                $product = Product::find($item['id']);
                if (!$product || $product->qty < $item['quantity']) {
                    DB::rollBack();
                    return response()->json(['success' => false, 'message' => "Insufficient stock for {$item['id']}"], 400);
                }
                Sale::create([
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'total_price' => $product->price * $item['quantity'],
                    'status' => 'pending',
                ]);
                $product->decrement('qty', $item['quantity']);
            }
            DB::commit();
            session()->forget('cart');
            return response()->json(['success' => true, 'message' => 'Order confirmed!', 'redirect_url' => route('sales.data')]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Confirm order error: '.$e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error while confirming order'], 500);
        }
    }
}