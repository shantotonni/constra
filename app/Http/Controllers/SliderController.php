<?php

namespace App\Http\Controllers;

use App\Http\Requests\Slider\SliderStoreRequest;
use App\Http\Resources\Slider\SliderCollection;
use App\Models\Slider;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('id','desc')->paginate(15);
        return new SliderCollection($sliders);
    }

    public function store(SliderStoreRequest $request)
    {
        // Validate image dimensions
        $imageDimantion = Image::make($request->image);
        if ($imageDimantion->width() != 1920 || $imageDimantion->height() != 800) {
            return response()->json([
                'status' => 'error',
                'message' => 'Image dimensions must be Width-1920px, Height- 800px.'
            ]);
        }

        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/slider/').$name);
        } else {
            $name = 'not_found.jpg';
        }

        $slider = new Slider();
        $slider->title = $request->title;
        $slider->paragraph = $request->paragraph;
        $slider->status = $request->status;
        $slider->image = $name;
        $slider->save();

        return response()->json([
            'status'=>'success',
            'message'=>'Slider Created Successfully',
        ],200);
    }

    public function update(Request $request, $id)
    {

        $slider = Slider::where('id',$request->id)->first();
        $image = $request->image;

        if ($image != $slider->image) {
            if ($request->has('image')) {

                // Validate image dimensions
                $imageDimantion = Image::make($request->image);

                if ($imageDimantion->width() != 1920 || $imageDimantion->height() != 800) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Image dimensions must be Width-1920px, Height- 800px.'
                    ]);
                }

                //code for remove old file
                if ($slider->image != '' && $slider->image != null) {
                    $destinationPath = 'images/slider/';
                    $file_old = $destinationPath . $slider->image;
                    if (file_exists($file_old)) {
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/slider/') . $name);
            } else {
                $name = $slider->image;
            }
        }else{
            $name = $slider->image;
        }

        $slider->title = $request->title;
        $slider->paragraph = $request->paragraph;
        $slider->status = $request->status;
        $slider->image = $name;
        $slider->save();
        return response()->json([
            'status'=>'success',
            'message'=>'Slider Updated Successfully'
        ],200);
    }

    public function show($id)
    {
        $slider = Slider::Where('id',$id)->first();
        return response()->json([
            'data'=>$slider
        ]);

    }

    public function destroy($id)
    {
        $slider = Slider::where('id', $id)->first();
        if ($slider->image) {
            $destinationPath = '/images/slider/';

            $file = public_path('/') . $destinationPath . $slider->image;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $slider->delete();
        return response()->json(['message' => 'Slider Deleted Successfully']);
    }

    public function search($query)
    {
        return new SliderCollection(Slider::Where('title', 'like', "%$query%")
            ->paginate(10));
    }
}
