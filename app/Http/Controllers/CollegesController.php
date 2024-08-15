<?php

namespace App\Http\Controllers;

use App\Http\Requests\Colleges\CollegesStoreRequest;
use App\Http\Resources\Colleges\CollegesCollection;
use App\Models\Colleges;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class CollegesController extends Controller
{
    public function index()
    {
        $college = Colleges::Orderby('id','desc')->paginate(15);
        return new CollegesCollection($college);
    }

    public function store(CollegesStoreRequest $request)
    {
        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/college/').$name);
        } else {
            $name = 'not_found.jpg';
        }

        $college = New Colleges();
        $college->name = $request->name;
        $college->image =$name;
        $college->save();
        return response()->json([
            'message' => 'College Info Stored Successfully'
        ],200);
    }

    public function update(Request $request, Colleges $college)
    {
        $college = Colleges::where('id',$request->id)->first();
        $image = $request->image;

        if ($image != $college->image) {
            if ($request->has('image')) {
                //code for remove old file
                if ($college->image != '' && $college->image != null) {
                    $destinationPath = 'images/college/';
                    $file_old = $destinationPath . $college->image;
                    if (file_exists($file_old)) {
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/college/') . $name);
            } else {
                $name = $college->image;
            }
        }else{
            $name = $college->image;
        }
        $college->name = $request->name;
        $college->image =$name;
        $college->save();
        return response()->json([
            'message' => 'College Info Updated Successfully'
        ],200);
    }

    public function show($id)
    {
        $college = Colleges::where('id', $id)->first();
        return response()->json([
            'data'=>$college
        ]);
    }

    public function destroy($id)
    {
        $college = Colleges::where('id', $id)->first();
        if ($college->image) {
            $destinationPath = '/images/college/';

            $file = public_path('/') . $destinationPath . $college->image;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $college->delete();
        return response()->json(['message' => 'College Deleted Successfully']);
    }

    public function search($query)
    {
        return new CollegesCollection(Colleges::Where('name', 'like', "%$query%")
            ->paginate(10));
    }
}
