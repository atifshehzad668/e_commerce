<?php

use App\Mail\OrderEmail;
use App\Models\Category;
use App\Models\Country;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;

function getCategories()
{
    return Category::orderBy('name', 'ASC')->where('showHome', 'Yes')->with('subCategories')->get();
}

function orderEmail($orderId ,$userType="customer"){
    $order = Order::where('id',$orderId)->with('items')->first();

    if($userType=="customer"){
        $subject = "Thanks for your order";
        $email =$order->email;
    }else{

        $subject = "You received an order";
        $email = env('ADMIN_EMAIL');
    }

    $mailData=[
        'subject' => $subject,
        'order' => $order,
        'userType'=>$userType
    ];

    Mail::to($email)->send(new OrderEmail($mailData));

}
function getCountryInfo($id){
 return Country::where('id',$id)->first();


}
