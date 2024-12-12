<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\CssSelector\Node\FunctionNode;

class BrandController extends Controller
{
    public function create()
    {
        return view('admin.brand.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'status' => 'required'
        ]);

        if ($validator->passes()) {
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $request->session()->flash('success', 'Brand Added Successfully');
            return response()->json(['status' => true, 'message' => 'Brand Added Successfully']);
        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }

    public function index()
    {
        $brands = Brand::paginate(10);
        return view('admin.brand.list', get_defined_vars());
    }

    public function edit(Request $request, $brand_id)
    {
        $brand = Brand::find($brand_id);
        if (empty($brand)) {
            return redirect()->route('brands.index');
        }
        return view('admin.brand.edit', get_defined_vars());
    }
    public function destroy(Request $request, $id)
    {
        $brand = Brand::find($id);
        if (empty($brand)) {
            $request->session()->flash('error', 'Record Not Found');
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }
        $brand->delete();

        $request->session()->flash('success', 'Brand Deleted Successfully');

        return response()->json(['status' => true, 'message' => 'Brand Deleted Successfully']);
    }

    public function update(Request $request, $brand_id)
    {
        $brand = Brand::find($brand_id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $brand->id . ',id',
            'status' => 'required'
        ]);

        if ($validator->passes()) {
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            $request->session()->flash('success', 'Brand Updated Successfully');
            return response()->json(['status' => true, 'message' => 'Brand Updated Successfully']);
        } else {
            $request->session()->flash('error', 'Validation failed. Please check the fields.');
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }
}