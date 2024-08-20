<?php

namespace App\Http\Controllers;

use App\Http\Requests\Service\ServiceStoreRequest;
use App\Http\Resources\Service\ServiceCollection;
use App\Models\Service;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ServiceController extends Controller
{

    public function index()
    {
        $services = Service::orderBy('id','desc')->paginate(15);
        return new ServiceCollection($services);
    }

    public function store(ServiceStoreRequest $request)
    {
        // Validate image dimensions
        $imageDimantion = Image::make($request->image);
        if ($imageDimantion->width() != 405 || $imageDimantion->height() != 300) {
            return response()->json([
                'status' => 'error',
                'message' => 'Image dimensions must be width-405px, height- 300px'
            ]);
        }

        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/service/').$name);
        } else {
            $name = 'not_found.jpg';
        }

        $service = new Service();
        $service->title = $request->title;
        $service->description = $request->description;
        $service->status = $request->status;
        $service->ordering = $request->ordering;
        $service->image = $name;
        $service->save();

        return response()->json([
            'status'=>'success',
            'message'=>'Service Created Successfully'
        ],200);
    }

    public function update(Request $request, $id)
    {
        $service = Service::where('id',$request->id)->first();
        $image = $request->image;

        if ($image != $service->image) {
            if ($request->has('image')) {
                // Validate image dimensions
                $imageDimantion = Image::make($request->image);
                if ($imageDimantion->width() != 405 || $imageDimantion->height() != 300) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Image dimensions must be width-405px, height- 300px'
                    ]);
                }

                //code for remove old file
                if ($service->image != '' && $service->image != null) {
                    $destinationPath = 'images/service/';
                    $file_old = $destinationPath . $service->image;
                    if (file_exists($file_old)) {
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/service/') . $name);
            } else {
                $name = $service->image;
            }
        }else{
            $name = $service->image;
        }

        $service->title = $request->title;
        $service->description = $request->description;
        $service->status = $request->status;
        $service->ordering = $request->ordering;
        $service->image = $name;
        $service->save();
        return response()->json([
            'status'=>'success',
            'message'=>'Service Updated Successfully'
        ],200);
    }

    public function show($id)
    {
        $service = Service::Where('id',$id)->first();
        return response()->json([
            'data'=>$service
        ]);

    }

    public function destroy($id)
    {
        $service = Service::where('id', $id)->first();
        if ($service->image) {
            $destinationPath = '/images/service/';

            $file = public_path('/') . $destinationPath . $service->image;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $service->delete();
        return response()->json(['message' => 'Service Deleted Successfully']);
    }

    public function search($query)
    {
        return new ServiceCollection(Service::Where('title', 'like', "%$query%")
            ->paginate(10));
    }
}
