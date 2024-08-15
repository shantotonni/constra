<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contries\CountriesStoreRequest;
use App\Http\Resources\Contries\CountriesCollection;
use App\Models\Countries;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    public function index()
    {
        $country = Countries::orderBy('id','desc')->paginate(15);
        return new CountriesCollection($country);
    }

    public function store(CountriesStoreRequest $request)
    {

        $country = new Countries();
        $country->name = $request->name;
        $country->save();
        return response()->json(['message'=>'Country Created Successfully'],200);
    }
    public function update(Request $request, Countries $country)
    {
        $country = Countries::where('id',$request->id)->first();
        $country->name = $request->name;
        $country->save();
        return response()->json(['message'=>'Country Updated Successfully'],200);
    }

    public function destroy(Request $request, $id)
    {

        $country = Countries::where('id',$id)->delete();

        return response()->json(['message'=>'Country Deleted Successfully'],200);
    }
    public function search($query)
    {
        return new CountriesCollection(Countries::Where('name', 'like', "%$query%")
            ->paginate(10));
    }
}
