<?php

namespace App\Http\Controllers\admin;


use Intervention\Image\ImageManagerStatic as Image;

use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class TempImagesController extends Controller
{
    public function create(Request $request)
    {
        $image = $request->image;
        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $newName = time() . '_' . Str::random(10) . '.' . $ext; // Use time and a random string for the filename
            $tempimage = new TempImage();
            $tempimage->name = $newName;
            $tempimage->save();
            $image->move(public_path() . '/temp', $newName);

            return response()->json([
                'status' => true,
                'image_id' => $tempimage->id,
                'ImagePath' => asset('temp/' . $newName),
                'message' => 'image uploaded successfully'
            ]);
        }
    }
}
// $source_path = public_path() . '/temp' . $newName;
// $destination_path = public_path() . '/temp/thumb' . $newName;
// $image = Image::make($source_path);
// $image->fit(300, 275);