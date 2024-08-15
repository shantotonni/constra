<?php

namespace App\Http\Controllers;

use App\Http\Requests\AboutRequest;
use App\Http\Resources\AboutCollection;
use App\Models\About;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class AboutController extends Controller
{
    public function index()
    {
        $abouts = About::orderBy('id','desc')->paginate(15);
        return new AboutCollection($abouts);
    }

    public function store(AboutRequest $request)
    {
        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/about/').$name);
        } else {
            $name = 'not_found.jpg';
        }

        $about = new About();
        $about->title = $request->title;
        $about->short_intro = $request->short_intro;
        $about->description = $request->description;
        $about->image = $name;
        $about->save();

        return response()->json(['message'=>'About Created Successfully'],200);
    }

    public function update(Request $request, $id)
    {

        $about = About::where('id',$request->id)->first();
        $image = $request->image;

        if ($image != $about->image) {
            if ($request->has('image')) {
                //code for remove old file
                if ($about->image != '' && $about->image != null) {
                    $destinationPath = 'images/about/';
                    $file_old = $destinationPath . $about->image;
                    if (file_exists($file_old)) {
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/about/') . $name);
            } else {
                $name = $about->image;
            }
        }else{
            $name = $about->image;
        }

        $about->title = $request->title;
        $about->short_intro = $request->short_intro;
        $about->description = $request->description;
        $about->image = $name;
        $about->save();
        return response()->json(['message'=>'About Updated Successfully'],200);
    }

    public function show($id)
    {
        $about = About::Where('id',$id)->first();
        return response()->json([
            'data'=>$about
        ]);

    }

    public function destroy($id)
    {
        $about = About::where('id', $id)->first();
        if ($about->image) {
            $destinationPath = '/images/about/';

            $file = public_path('/') . $destinationPath . $about->image;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $about->delete();
        return response()->json(['message' => 'About Deleted Successfully']);
    }

    public function search($query)
    {
        return new AboutCollection(About::Where('title', 'like', "%$query%")
            ->paginate(10));
    }
}
