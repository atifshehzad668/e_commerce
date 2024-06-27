<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {
        $categorySelected = '';
        $subCategorySelected = '';

        $brandsArray = [];

        $categories = Category::orderBy('name', 'ASC')->with('subCategories')->where('status', 1)->get();
        $brands =  Brand::orderBY('name', 'ASC')->where('status', 1)->get();
        $products = Product::where('status', 1);
        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $products->where('category_id', $category->id);
                $categorySelected = $category->id;
            }
        }

        // Handle subcategory selection
        if (!empty($subCategorySlug)) {
            $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
            if ($subCategory) {
                $products->where('sub_category_id', $subCategory->id);
                $subCategorySelected = $subCategory->id;
            }
        }

        if (!empty($request->get('brand'))) {
            $brandsArray = explode(',', $request->get('brand'));
            $products = $products->whereIn('brand_id', $brandsArray);
        }
        if ($request->get('price_max') != '' && $request->get('price_min') != '') {
            if ($request->get('price_max') == 1000) {
                $products = $products->whereBetween('price', [intval($request->get('price_min')), 10000000]);
            } else {

                $products = $products->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);
            }
        }
        $price_max = (intval($request->get('price_max')) == 0) ? 1000 : $request->get('price_max');
        $price_min = intval($request->get('price_min'));

        if ($request->get('sort') != '') {
            if ($request->get('sort') == 'latest') {
                $products = $products->orderBy('id', 'DESC');
            } else if ($request->get('sort') == 'price_desc') {
                $products = $products->orderBy('price', 'DESC');
            } else {
                $products = $products->orderBy('price', 'ASC');
            }
        } else {
            $products = $products->orderBy('id', 'DESC');
        }
        $sort = $request->get('sort');
        $products = $products->paginate(6);
        return view('front.shop', get_defined_vars());
    }



    public function product($slug)
    {
        $product = Product::where('slug', $slug)->first();
        if ($product == null) {
            abort(404);
        }
        return view('front.product', get_defined_vars());
    }
}
