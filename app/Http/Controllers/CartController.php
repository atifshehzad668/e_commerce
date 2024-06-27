<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Product::find($request->id);
        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'record not found'
            ]);
        }
        if (Cart::count() > 0) {

            return response()->json([
                'status' => false,
                'message' => 'product in already in cart'
            ]);
        } else {

            Cart::add(
                $product->id,
                $product->name,
                1,
                $product->price,
                ['productImage' => $product->getMedia('images')->isNotEmpty() ? $product->getMedia('images')->first()->getUrl() : ''],


            );
        }
        return response()->json([
            'status' => true,
            'message' => 'product added to cart'
        ]);
    }
    public function Cart()
    {
        dd(Cart::content());
        // return view('front.cart');
    }
}
