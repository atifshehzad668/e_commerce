<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Routing\RedirectController;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest();
        if (!empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
        $categories = $categories->paginate(10);
        return view('admin.category.list', get_defined_vars());
    }


    public function create()
    {
        return view('admin.category.create');
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);

        if ($validator->passes()) {

            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();



            if (!empty($request->image_id)) {
                $TempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $TempImage->name);
                $ext = last($extArray);
                $newImageName = $category->id . '.' . $ext;
                $sPath = public_path() . '/temp/' . $TempImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;
                File::copy($sPath, $dPath);

                // $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
                // $imgmanager = new ImageManager(new Driver());
                // $thumbimage = $imgmanager->read('/uploads/category/' . $newImageName);
                // $thumbimage->resize(300, 200);
                // $thumbimage->save($dPath);
                $category->image = $newImageName;
                $category->save();
            }



            $request->session()->flash('success', 'Category Added Successfully');
            return response()->json([
                'status' => true,
                'message' => 'category added successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function edit(Request $request, $category_id)
    {
        $category = Category::find($category_id);
        if (empty($category)) {
            return redirect()->route('categories.index');
        }
        return view('admin.category.edit', get_defined_vars());
    }


    public function update(Request $request, $category_id)
    {
        $category = Category::find($category_id);
        if (empty($category)) {
            return response()->json([
                'status' => true,
                'Not Found' => true,
                'message' => 'Category Not Found'
            ]);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id . ',id',
        ]);

        if ($validator->passes()) {


            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();


            $oldimage = $category->image;
            if (!empty($request->image_id)) {
                $TempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $TempImage->name);
                $ext = last($extArray);
                $newImageName = $category->id . '-' . time() . '.' . $ext;
                $sPath = public_path() . '/temp/' . $TempImage->name;
                $dPath = public_path() . '/uploads/category/' . $newImageName;
                File::copy($sPath, $dPath);

                // $dPath = public_path() . '/uploads/category/thumb/' . $newImageName;
                // $imgmanager = new ImageManager(new Driver());
                // $thumbimage = $imgmanager->read('/uploads/category/' . $newImageName);
                // $thumbimage->resize(300, 200);
                // $thumbimage->save($dPath);
                $category->image = $newImageName;
                $category->save();

                File::delete(public_path('/uploads/category/' . $oldimage));
            }



            $request->session()->flash('success', 'Category updated Successfully');
            return response()->json([
                'status' => true,
                'message' => 'category updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function destroy(Request $request, $category_id)
    {
        $category = Category::find($category_id);
        if (empty($category)) {
            $request->session()->flash('success', 'Category Not Found');
            return response()->json([
                'status' => true,
                'message' => 'Category Not Found'
            ]);
        }
        File::delete(public_path('/uploads/category/' . $category->image));
        $category->delete();
        $request->session()->flash('success', 'Category Deleted Successfully');
        return response()->json([
            'status' => true,
            'message' => 'Category Deleted Successfully'
        ]);
    }
}
