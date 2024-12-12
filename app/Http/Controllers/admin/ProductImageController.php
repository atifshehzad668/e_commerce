<?php

namespace App\Http\Controllers\admin;

use App\Models\Product;
use App\Models\TempImage;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use Illuminate\Http\Request;


use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductImageController extends Controller
{
    // public function update(Request $request)
    // {
    //     $image = $request->file('image');
    //     $ext = $image->getClientOriginalExtension();
    //     $sourcePath = $image->getPath();

    //     $productImage = new ProductImage();
    //     $productImage->product_id = $request->product_id;
    //     $productImage->image = 'NULL';
    //     $productImage->save();

    //     $ImageName = $request->product_i . '-' . $productImage->id . '-' . time() . '.' . $ext;
    //     $productImage->image = $ImageName;
    //     $productImage->save();
    //     $destPath = public_path() . '/uploads/products' . $ImageName;
    //     $image = Image::make($sourcePath);
    //     $image->resize(1400, null, function ($constraint) {
    //         $constraint->aspectRatio();
    //     });
    //     $image->save($destPath);
    // }











    public function update(Request $request)
    {
        // Validate the request to ensure 'image' and 'product_id' are present
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_id' => 'required|integer',
        ]);

        // Retrieve the uploaded image
        $image = $request->file('image');

        // Get the image extension
        $ext = $image->getClientOriginalExtension();

        // Create a new ProductImage instance
        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image = 'NULL';
        $productImage->save();

        // Generate a unique image name
        $imageName = $request->product_id . '-' . $productImage->id . '-' . time() . '.' . $ext;
        $productImage->image = $imageName;
        $productImage->save();

        // Define the destination path
        $destPath = public_path('/uploads/products/' . $imageName);

        // Move the uploaded file to the destination path
        $image->move(public_path('/uploads/products/'), $imageName);

        // Resize the image using GD library
        $this->resizeImage($destPath, 1400);

        // Return a success response or redirect as needed
        return response()->json(['success' => 'Image uploaded and resized successfully.']);
    }

    private function resizeImage($file, $width)
    {
        // Get the image size
        list($originalWidth, $originalHeight) = getimagesize($file);

        // Calculate the new height maintaining the aspect ratio
        $height = ($originalHeight / $originalWidth) * $width;

        // Create a new true color image
        $resizedImage = imagecreatetruecolor($width, $height);

        // Determine the image type and create a new image from file
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        switch (strtolower($ext)) {
            case 'jpeg':
            case 'jpg':
                $sourceImage = imagecreatefromjpeg($file);
                break;
            case 'png':
                $sourceImage = imagecreatefrompng($file);
                break;
            case 'gif':
                $sourceImage = imagecreatefromgif($file);
                break;
            default:
                throw new \Exception('Unsupported image type');
        }

        // Copy and resize the image
        imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

        // Save the resized image to file
        switch (strtolower($ext)) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($resizedImage, $file, 90);
                break;
            case 'png':
                imagepng($resizedImage, $file);
                break;
            case 'gif':
                imagegif($resizedImage, $file);
                break;
        }

        // Free up memory
        imagedestroy($sourceImage);
        imagedestroy($resizedImage);
    }



















    public function delete(Request $request)
    {
        $productImage = ProductImage::find($request->id);
        if ($productImage) {
            $imagePath = public_path() . '/uploads/products/' . $productImage->image;
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            $productImage->delete();
            return response()->json(['status' => true]);
        }
        return response()->json(['status' => false], 404);
    }
}