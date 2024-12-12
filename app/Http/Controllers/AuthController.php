<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        return view('front.userauth.login');
    }
    public function register_user()
    {
        return view('front.userauth.register');
    }


    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->save();
        session()->flash('success', 'You Have Been Registerd Successfully.');
        return response()->json([
            'status' => true,
        ]);
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
                if (session()->has('url.intended')) {
                    return redirect(session()->get('url.intended'));
                }
                return redirect()->route('account.profile');
            } else {
                session()->flash('error', 'Either email/passoword is in correct.');
                return redirect()->route('account.login');
            }
        } else {
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

    public function profile()
    {
        return view('front.userauth.profile');
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('account.login')->with('success', 'You Successfully Logged Out!');
    }
    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id',$user->id)->orderBy('created_at','DESC')->get();
        return view('front.userauth.order',get_defined_vars());
    }
    public function orderDetail($id)
    {
        $user = Auth::user();
        $order = Order::where('user_id',$user->id)->where('id',$id)->first();
        $orderItems = OrderItem::where('order_id',$id)->with('products')->get();
        $orderItemsCount = OrderItem::where('order_id',$id)->with('products')->count();
        return view('front.userauth.order-detail',get_defined_vars());
    }
}
