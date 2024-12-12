<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        // Fetch featured products
        $products = Product::where('is_featured', 'YES')
            ->where('status', 1)
            ->with('product_images')  // Assuming you have a relationship defined for product_images
            ->get();

        // Fetch the latest products
        $latest_products = Product::orderBy('id', 'DESC')
            ->where('status', 1)
            ->take(8)
            ->get();

        // Pass the data to the view
        return view('front.home', compact('products', 'latest_products'));
    }

    public function addToWishlist(Request $request)
    {
        // Add logic for adding a product to the wishlist
    }
}
