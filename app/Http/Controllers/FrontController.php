<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        $products = Product::where('is_featured', 'YES')->where('status', 1)->get();
        $latest_products = Product::orderBy('id', 'DESC')->where('status', 1)->take(8)->get();
        return view('front.home', get_defined_vars());
    }
}