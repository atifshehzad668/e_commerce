<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\CssSelector\Node\FunctionNode;

class SubCategoryController extends Controller
{
    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.sub_categories.create', get_defined_vars());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category' => 'required',
            'status' => 'required'
        ]);
        if ($validator->passes()) {

            $subcategory = new SubCategory();

            $subcategory->name = $request->name;
            $subcategory->slug = $request->slug;
            $subcategory->status = $request->status;
            $subcategory->showHome = $request->showHome;
            $subcategory->category_id = $request->category;
            $subcategory->save();

            $request->session()->flash('success', 'Sub Category Added Successfully');

            return response()->json(['status' => true, 'message' => 'Sub Category Added Successfully']);
        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }




    // public function index(Request $request)
    // {
    //     $subcategoriesQuery = SubCategory::latest();
    //     $subcategories = $subcategoriesQuery->with('category')->paginate(10);
    //     if (!empty($request->get('keyword'))) {
    //         $subcategoriesQuery = $subcategoriesQuery->where('name', 'like', '%' . $request->get('keyword') . '%');
    //         $subcategoriesQuery = $subcategoriesQuery->orwhere('category.name', 'like', '%' . $request->get('keyword') . '%');
    //     }
    //     return view('admin.sub_categories.list', compact('subcategories'));
    // }



    public function index(Request $request)
    {
        $subcategoriesQuery = SubCategory::latest()->with('category');

        if (!empty($request->get('keyword'))) {
            $keyword = $request->get('keyword');
            $subcategoriesQuery = $subcategoriesQuery->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%')
                    ->orWhereHas('category', function ($query) use ($keyword) {
                        $query->where('name', 'like', '%' . $keyword . '%');
                    });
            });
        }

        $subcategories = $subcategoriesQuery->paginate(10);

        return view('admin.sub_categories.list', compact('subcategories'));
    }






    public function edit(Request $request, $id)
    {
        $subCategory = SubCategory::find($id);
        if (empty($subCategory)) {
            $request->session()->flash('error', 'Record Not Found');
            return redirect()->route('sub-categories.index');
        }
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.sub_categories.edit', get_defined_vars());
    }

    public function update(Request $request, $id)
    {
        $subCategory = SubCategory::find($id);
        if (empty($subCategory)) {
            $request->session()->flash('error', 'Record Not Found');
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',

            'slug' => 'required|unique:sub_categories,slug,' . $subCategory->id . ',id',
            'category' => 'required',
            'status' => 'required'
        ]);
        if ($validator->passes()) {


            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->showHome = $request->showHome;
            $subCategory->category_id = $request->category;
            $subCategory->save();

            $request->session()->flash('success', 'Sub Category Updated Successfully');

            return response()->json(['status' => true, 'message' => 'Sub Category Updated Successfully']);
        } else {
            return response()->json(['status' => false, 'errors' => $validator->errors()]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $subCategory = SubCategory::find($id);
        if (empty($subCategory)) {
            $request->session()->flash('error', 'Record Not Found');
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }
        $subCategory->delete();

        $request->session()->flash('success', 'Sub Category Deleted Successfully');

        return response()->json(['status' => true, 'message' => 'Sub Category Deleted Successfully']);
    }
}
