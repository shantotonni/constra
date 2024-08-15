<?php

namespace App\Http\Controllers;

use App\Http\Resources\OurTea\OurTeamCollection;
use App\Models\OurTeam;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class OurTeamController extends Controller
{
    public function index()
    {
        $teams = OurTeam::orderBy('id','desc')->paginate(15);
        return new OurTeamCollection($teams);
    }

    public function store(Request $request)
    {
        if ($request->has('image')) {
            $image = $request->image;
            $name = uniqid().time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
            Image::make($image)->save(public_path('images/team/').$name);
        } else {
            $name = 'not_found.jpg';
        }

        $team = new OurTeam();
        $team->name = $request->name;
        $team->description = $request->description;
        $team->image = $name;
        $team->designation = $request->designation;
        $team->save();

        return response()->json(['message'=>'Team Created Successfully'],200);
    }

    public function update(Request $request, OurTeam $team)
    {
        $team = OurTeam::where('id',$request->id)->first();
        $image = $request->image;

        if ($image != $team->image) {
            if ($request->has('image')) {
                //code for remove old file
                if ($team->image != '' && $team->image != null) {
                    $destinationPath = 'images/team/';
                    $file_old = $destinationPath . $team->image;
                    if (file_exists($file_old)) {
                        unlink($file_old);
                    }
                }
                $name = uniqid() . time() . '.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
                Image::make($image)->save(public_path('images/team/') . $name);
            } else {
                $name = $team->image;
            }
        }else{
            $name = $team->image;
        }

        $team->name = $request->name;
        $team->description = $request->description;
        $team->designation = $request->designation;
        $team->image = $name;
        $team->save();
        return response()->json(['message'=>'Team Updated Successfully'],200);
    }

    public function show($id){

        $team = OurTeam::Where('id',$id)->first();
        return response()->json([
            'data'=>$team
        ]);

    }

    public function destroy($id)
    {
        $team = OurTeam::where('id', $id)->first();
        if ($team->image) {
            $destinationPath = '/images/team/';

            $file = public_path('/') . $destinationPath . $team->image;
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $team->delete();
        return response()->json(['message' => 'Team Deleted Successfully']);
    }

    public function search($query)
    {
        return new OurTeamCollection(OurTeam::Where('name', 'like', "%$query%")
            ->paginate(10));
    }
}
