<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class TempImagesController extends Controller
{
    public function create(Request $request)
    {
        if($request->image){
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();


            $tempImage = new TempImage();
            $tempImage->name = 'TEST';
            $tempImage->save();

            $newName = $tempImage->id. '-' .time(). '.' .$ext;

            $image->move(public_path('/temp') , $newName);

            $manager = new ImageManager(new Driver());
            $sourcePath = public_path() . '/temp/' . $newName;
            $destPath = public_path(). '/temp/thumb/' .$newName;
            $image = $manager->read($sourcePath);
            $image->cover(300,275);
            $image->save($destPath);

            return response()->json([
                'status' => true,
                'name' => $newFileName,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('/temp/thumb/'. $newFileName),
            ]);
        }


    }
}
