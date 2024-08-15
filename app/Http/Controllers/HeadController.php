<?php

namespace App\Http\Controllers;

use App\Models\Head;
use Illuminate\Http\Request;

class HeadController extends Controller
{
    public function index(Request $request)
    {
        $take = $request->take;
        return Head::select('head_id','name')->paginate($take);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required',
        ]);

        try {
            $head = new Head();
            $head->name = $request->name;
            $head->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Head has been created successfully'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ],500);
        }
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => 'required',
        ]);
        try {
            $head = Head::where('head_id',$id)->first();
            $head->name = $request->name;
            $head->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Head has been updated successfully'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ],500);
        }
    }

    public function byId($id)
    {
        return response()->json([
            'status' => 'success',
            'data' => Head::where('head_id',$id)->first()
        ]);
    }
}
