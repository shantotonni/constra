<?php

namespace App\Http\Controllers;

use App\Http\Resources\Testimonial\TestimonialCollection;
use App\Models\testimonial;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class TestimonialController extends Controller
{
    public function index()
    {
      $testimonials = Testimonial::orderBy('id','desc')->paginate(15);
      return new TestimonialCollection($testimonials);
    }

    public function store(Request $request)
    {
        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/testimonial/').$name);
        } else {
            $name = 'not_found.jpg';
        }

        $testimonial = new Testimonial();
        $testimonial->name = $request->name;
        $testimonial->complement = $request->complement;
        $testimonial->image = $name;
        $testimonial->position = $request->position;
        $testimonial->save();

        return response()->json(['message'=>'Testimonial Created Successfully'],200);
    }

    public function update(Request $request, testimonial $testimonial)
    {
        $testimonial = Testimonial::where('id',$request->id)->first();
        $image = $request->image;

        if ($image != $testimonial->image) {
            if ($request->has('image')) {
                //code for remove old file
                if ($testimonial->image != '' && $testimonial->image != null) {
                    $destinationPath = 'images/testimonial/';
                    $file_old = $destinationPath . $testimonial->image;
                    if (file_exists($file_old)) {
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/testimonial/') . $name);
            } else {
                $name = $testimonial->image;
            }
        }else{
            $name = $testimonial->image;
        }

        $testimonial->name = $request->name;
        $testimonial->complement = $request->complement;
        $testimonial->position = $request->position;
        $testimonial->image = $name;
        $testimonial->save();
        return response()->json(['message'=>'Testimonial Updated Successfully'],200);
    }

    public function show($id){

        $testimonial = Testimonial::Where('id',$id)->first();
        return response()->json([
            'data'=>$testimonial
        ]);

    }

    public function destroy($id)
    {
        $testimonial = Testimonial::where('id', $id)->first();
        if ($testimonial->image) {
            $destinationPath = '/images/testimonial/';

            $file = public_path('/') . $destinationPath . $testimonial->image;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $testimonial->delete();
        return response()->json(['message' => 'Testimonial Deleted Successfully']);
    }

    public function search($query)
    {
        return new TestimonialCollection(Testimonial::Where('name', 'like', "%$query%")
            ->paginate(10));
    }
}
