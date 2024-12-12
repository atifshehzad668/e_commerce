<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::latest('orders.created_at')->select('orders.*', 'users.name', 'users.email');
        $orders = $orders->leftJoin('users', 'users.id', 'orders.user_id');
        if ($request->get('keyword') != "") {
            $orders = $orders->where('users.name', 'like', '%' . $request->keyword . '%');
            $orders = $orders->orwhere('users.email', 'like', '%' . $request->keyword . '%');
            $orders = $orders->orwhere('orders.id', 'like', '%' . $request->keyword . '%');
        }
        $orders = $orders->paginate(10);

        return view('admin.orders.list', get_defined_vars());
    }
    public function detail($orderId)
    {
        $order = Order::select('orders.*', 'countries.name as countryName')
            ->leftJoin('countries', 'countries.id', '=', 'orders.country_id')
            ->where('orders.id', $orderId)
            ->first();
        $orderItems = OrderItem::where('order_id',$orderId)->get();
        return view('admin.orders.detail', get_defined_vars());
    }


    public function changeOrderStatus(Request $request,$orderId){
        $order =    Order::find($orderId);

        $order->status = $request->status;
        $order->shipped_date = $request->shipped_date;
        $order->save();
        session()->flash('success',"Order Status Updated Successfully");
        return response()->json([
            'status'=>true,
            'message' =>'Order Status Updated Successfully'

        ]);


    }

    public function sendInvoiceEmail(Request $request,$orderId)
    {
        orderEmail($orderId,$request->userType);
        session()->flash('success',"Order Email Send Successfully");
        return response()->json([
            'status'=>true,
            'message' =>'Order Email Send Successfully'

        ]);
    }
}
