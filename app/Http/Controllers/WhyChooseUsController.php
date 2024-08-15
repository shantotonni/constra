<?php

namespace App\Http\Controllers;

use App\Http\Requests\WhyChooseUs\WhyChooseUsStoreRequest;
use App\Http\Resources\WhyChooseUs\WhyChooseUsCollection;
use App\Models\WhyChooseUs;
use Illuminate\Http\Request;

class WhyChooseUsController extends Controller
{

    public function index()
    {
        $chooses = WhyChooseUs::orderBy('id','desc')->paginate(15);
        return new WhyChooseUsCollection($chooses);
    }

    public function store(WhyChooseUsStoreRequest $request)
    {
        $choose = new WhyChooseUs();
        $choose->icon = $request->icon;
        $choose->title = $request->title;
        $choose->short_intro = $request->short_intro;
        $choose->save();

        return response()->json(['message'=>'WhyChooseUs Created Successfully'],200);
    }

    public function update(Request $request, $id)
    {
        $choose = WhyChooseUs::where('id',$request->id)->first();
        $choose->title = $request->title;
        $choose->icon = $request->icon;
        $choose->short_intro = $request->short_intro;
        $choose->save();
        return response()->json(['message'=>'Why Choose Us Updated Successfully'],200);
    }

    public function show($id)
    {
        $choose = WhyChooseUs::Where('id',$id)->first();
        return response()->json([
            'data'=>$choose
        ]);

    }

    public function destroy($id)
    {
        $choose = WhyChooseUs::where('id', $id)->first();
        $choose->delete();
        return response()->json(['message' => 'Why Choose Us Deleted Successfully']);
    }

    public function search($query)
    {
        return new WhyChooseUsCollection(WhyChooseUs::Where('icon', 'like', "%$query%")->paginate(10));
    }
}
