<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Country;
use App\Models\Product;
use App\Models\OrderItem;

use Illuminate\Http\Request;
use App\Models\CustomerAddress;
<<<<<<< HEAD
=======
use Exception;
>>>>>>> origin/master
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Validator;

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
                session()->flash('error', $message = 'Requested qty(' . $qty . ') not available in stock');
            } else {

                $status = false;
                Cart::update($rowId, $qty);
                $message = "Cart Updated Successfully";
                $status = true;
                session()->flash('success', $message);
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
    public function deleteItem(Request $request)
    {

        $iteminfo = Cart::get($request->rowId);
        if ($iteminfo == null) {
            session()->flash('error', 'Item Not Found in Cart');
            return response()->json([
                'status' => false,
                'message' => 'Item Not Found'
            ]);
        }
        Cart::remove($request->rowId);
        session()->flash('success', 'Item Remove  Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Item Remove  Successfully'
        ]);
    }

    public function checkout()
    {
        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }

        if (Auth::check() == false) {
            if (!session()->has('url.intended')) {

                session(['url.intended' => url()->current()]);
            }
            return redirect()->route('account.login');
        }

        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();
        session()->forget('url.intended');

        $countries = Country::orderBy('name', 'ASC')->get();
        return view('front.checkout', get_defined_vars());
    }



    public function processCheckout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'country' => 'required',
            'address' => 'required|min:10',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }


        $user = Auth::user();
        CustomerAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'country_id' => $request->country,
                'address' => $request->address,
                'apartment' => $request->appartment,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'notes' => $request->order_notes,
            ]
        );

        if ($request->payment_method == 'cod') {
            $shipping = 0;
            $subTotal = Cart::subtotal(2, '.', '');
            $grandTotal = $subTotal + $shipping;

            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->user_id = $user->id;
<<<<<<< HEAD
=======
            $order->payment_status = 'not paid';
            $order->status = 'pending';
>>>>>>> origin/master

            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->address = $request->address;
            $order->apartment = $request->appartment;
            $order->city = $request->city;
            $order->state = $request->state;
            $order->zip = $request->zip;
            $order->city = $request->city;
            $order->notes = $request->notes;
            $order->country_id = $request->country;
            $order->save();

            foreach (Cart::content() as $item) {
                $orderItem = new OrderItem;
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price * $item->qty;
                $orderItem->save();
            }

<<<<<<< HEAD
=======
            orderEmail($order->id,'customer');

>>>>>>> origin/master
            session()->flash('success', 'You have successfully placed your order');
            Cart::destroy();
            return response()->json([
                'message' => 'Order Save Successfully',
                'orderId' => $order->id,
                'status' => true
            ]);
<<<<<<< HEAD
=======

>>>>>>> origin/master
        } else {
        }
    }


    public function thanks($id)
    {
        return view('front.thanks', ['id' => $id]);
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> origin/master
