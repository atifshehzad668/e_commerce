<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    // public function addToCart(Request $request)
    // {
    //     $product = Product::find($request->id);
    //     if ($product == null) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'record not found'
    //         ]);
    //     }
    //     if (Cart::count() > 0) {


    //         echo "Product Already In Cart";
    //     } else {

    //         Cart::add(
    //             $product->id,
    //             $product->name,
    //             1,
    //             $product->price,
    //             ['productImage' => $product->getMedia('images')->isNotEmpty() ? $product->getMedia('images')->first()->getUrl() : ''],

    //         );
    //         $status = true;
    //         $message = $product->name . 'added in cart';
    //     }
    //     return response()->json([
    //         'status' => $status,
    //         'message' => $message
    //     ]);
    // }

    public function addToCart(Request $request)
    {
        $product = Product::find($request->id);
        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found'
            ]);
        }

        $cartItem = Cart::search(function ($cartItem, $rowId) use ($product) {
            return $cartItem->id === $product->id;
        });

        if ($cartItem->isNotEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Product already in cart'
            ]);
        }

        Cart::add(
            $product->id,
            $product->name,
            1,
            $product->price,
            ['productImage' => $product->getMedia('images')->isNotEmpty() ? $product->getMedia('images')->first()->getUrl() : '']
        );

        $status = true;
        $message = $product->name . ' added to cart';

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }











    public function Cart()
    {
        $cartContents  = Cart::content();


        return view('front.cart', get_defined_vars());
    }

    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;
        $iteminfo = Cart::get($rowId);
        $product = Product::find($iteminfo->id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ]);
        }

        if ($product->track_qty == 'YES') {


            if ($product->qty < $qty) {
                $status = true;
                $message = 'Requested qty(' . $qty . ') not available in stock';
            } else {

                $status = false;
                Cart::update($rowId, $qty);
                $message = "Cart Updated Successfully";
                $status = true;
            }
        } else {
            Cart::update($rowId, $qty);
            $message = "Cart Updated Successfully";
            $status = true;
        }

        session()->flash('success', $message);
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function clearCart(Request $request)
    {
        Cart::destroy();

        $message = "Cart Cleared Successfully";
        session()->flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
