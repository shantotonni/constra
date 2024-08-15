<?php

namespace App\Http\Controllers;

use App\Http\Resources\GalleryCollection;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class GalleryController extends Controller
{
    public function index()
    {
        $gallery = Gallery::orderBy('id','desc')->paginate(15);
        return new GalleryCollection($gallery);
    }

    public function store(Request $request)
    {
        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/gallery/').$name);
        } else {
            $name = 'not_found.jpg';
        }

        $gallery = new Gallery();
        $gallery->title = $request->title;
        $gallery->paragraph = $request->paragraph;
        $gallery->ordering = $request->ordering;
        $gallery->image = $name;
        $gallery->status =  $request->status;
        $gallery->video_status =  $request->video_status;
        $gallery->link =  $request->link;
        $gallery->save();

        return response()->json(['message'=>'Gallery Created Successfully'],200);
    }

    public function update(Request $request, $id)
    {

        $gallery = Gallery::where('id',$request->id)->first();
        $image = $request->image;

        if ($image != $gallery->image) {
            if ($request->has('image')) {
                //code for remove old file
                if ($gallery->image != '' && $gallery->image != null) {
                    $destinationPath = 'images/gallery/';
                    $file_old = $destinationPath . $gallery->image;
                    if (file_exists($file_old)) {
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/gallery/') . $name);
            } else {
                $name = $gallery->image;
            }
        }else{
            $name = $gallery->image;
        }

        $gallery->title = $request->title;
        $gallery->paragraph = $request->paragraph;
        $gallery->ordering = $request->ordering;
        $gallery->image = $name;
        $gallery->status =  $request->status;
        $gallery->video_status =  $request->video_status;
        $gallery->link =  $request->link;
        $gallery->save();
        return response()->json(['message'=>'Gallery Updated Successfully'],200);
    }

    public function show($id)
    {
        $gallery = Gallery::Where('id',$id)->first();
        return response()->json([
            'data'=>$gallery
        ]);
    }

    public function destroy($id)
    {
        $gallery = Gallery::where('id', $id)->first();
        if ($gallery->image) {
            $destinationPath = '/images/gallery/';

            $file = public_path('/') . $destinationPath . $gallery->image;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $gallery->delete();
        return response()->json(['message' => 'Gallery Deleted Successfully']);
    }

    public function search($query)
    {
        return new GalleryCollection(Gallery::Where('title', 'like', "%$query%")->paginate(10));
    }
}
