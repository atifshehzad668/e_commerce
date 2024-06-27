<?php

namespace App\Http\Controllers\admin;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\TempImage;
use App\Models\SubCategory;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $products = Product::latest('id')->with('product_images');
        if ($request->get('keyword') != "") {
            $products = $products->where('name', 'like', '%' . $request->keyword . '%');
        }
        $products = $products->paginate();
        return view('admin.product.index', get_defined_vars());
    }

    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $sub_categories = SubCategory::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        return view('admin.product.create', get_defined_vars());
    }


    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required',
            'is_featured' => 'required|in:Yes,No',
        ];

        if ($request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Create the product
        $product = new Product();
        $product->name = $request->title;
        $product->slug = $request->slug;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->compare_price = $request->compare_price;
        $product->sku = $request->sku;
        $product->barcode = $request->barcode;
        $product->track_qty = $request->track_qty;
        $product->qty = $request->qty ?? null; // qty is optional
        $product->status = $request->status;
        $product->category_id = $request->category;
        $product->sub_category_id = $request->sub_category ?? null;
        $product->brand_id = $request->brand;
        $product->is_featured = $request->is_featured;
        $product->short_description = $request->short_description;
        $product->shipping_returns = $request->shipping_returns;
        $product->save();


        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $product->addMedia($file)
                    ->usingName('Spatie Media Library')
                    ->toMediaCollection('images');
            }
        }


        $request->session()->flash('success', 'Product Added Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Product Added successfully'
        ]);
    }


    public function edit($id, Request $request)
    {
        $product = Product::find($id);
        if (empty($product)) {
            $request->session()->flash('error', 'Product Not Found');
            return redirect()->route('products.index')->with('error', 'Product Not Found');
        }

        $productImages = ProductImage::where('product_id', $product->id)->get();


        $sub_categories = SubCategory::where('category_id', $product->category_id)->get();
        $categories = Category::orderBy('name', 'ASC')->get();

        $brands = Brand::orderBy('name', 'ASC')->get();
        return view('admin.product.edit', get_defined_vars());
    }



    public function update(Request $request)
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,' . $request->product_id,
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,' . $request->product_id,
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required',
            'is_featured' => 'required|in:Yes,No',
        ];

        if ($request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Find the product by ID
        $product = Product::findOrFail($request->product_id);

        // Update product attributes
        $product->name = $request->title;
        $product->slug = $request->slug;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->compare_price = $request->compare_price;
        $product->sku = $request->sku;
        $product->barcode = $request->barcode;
        $product->track_qty = $request->track_qty;
        $product->qty = $request->qty ?? null; // qty is optional
        $product->status = $request->status;
        $product->category_id = $request->category;
        $product->sub_category_id = $request->sub_category ?? null;
        $product->brand_id = $request->brand;
        $product->is_featured = $request->is_featured;
        $product->short_description = $request->short_description;
        $product->shipping_returns = $request->shipping_returns;
        $product->save();

        // Handle image uploads
        if ($request->hasFile('images')) {
            $product->clearMediaCollection('images');
            foreach ($request->file('images') as $file) {
                $product->addMedia($file)
                    ->toMediaCollection('images');
            }
        }

        $request->session()->flash('success', 'Product Updated Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Product updated successfully'
        ]);
    }
}
