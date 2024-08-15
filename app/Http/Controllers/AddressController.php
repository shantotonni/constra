<?php

namespace App\Http\Controllers;

use App\Http\Requests\Address\AddressStoreRequest;
use App\Http\Resources\Address\AddressCollection;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{

    public function index()
    {
        $add = Address::orderBy('id','desc')->paginate(15);
        return new AddressCollection($add);
    }

    public function store(AddressStoreRequest $request)
    {
        $add = new Address();
        $add->title = $request->title;
        $add->details = $request->details;
        $add->save();

        return response()->json(['message'=>'Address Created Successfully'],200);
    }

    public function update(Request $request, Address $add)
    {
        $add = Address::where('id',$request->id)->first();
        $add->title = $request->title;
        $add->details = $request->details;
        $add->save();
        return response()->json(['message'=>'Address Updated Successfully'],200);
    }
    public function show($id){

        $add = Address::Where('id',$id)->first();
        return response()->json([
            'data'=>$add
        ]);

    }

    public function destroy($id)
    {
        $add = Address::where('id', $id)->first();
        $add->delete();
        return response()->json(['message' => 'Address Deleted Successfully']);
    }

    public function search($query)
    {
        return new AddressCollection(Address::Where('title', 'like', "%$query%")
            ->paginate(10));
    }
}
