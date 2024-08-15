<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerEventCollection;
use App\Http\Resources\DonationCollection;
use App\Http\Resources\DonationResource;
use App\Models\Customer;
use App\Models\CustomerEvent;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerAuthController extends Controller
{
    function __construct() {
        Config::set('jwt.user', Customer::class);
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Customer::class,
        ]]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid'], 400);
        }

        if ($token = JWTAuth::attempt(['email' => $request->email, 'password' => $request->password])){
            return response()->json([
                'status'=>200,
                'token'=>$token,
            ],200);
        }
        return response()->json([
            'message'=>'UserId or Password Not Match',
            'status'=>401
        ],200);
    }

    public function registration(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'password' => 'required',
        ]);

        $exist_check = Customer::where('email',$request->email)->exists();
        if ($exist_check){
            return response()->json([
                'status'=>'error',
                'message'=>'Email Already Exist'
            ],200);
        }

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->password = bcrypt($request->password);
        $customer->customer_status = 'Y';
        $customer->save();

        return response()->json([
            'status'=>'success',
            'message'=>'Successfully Registered'
        ],200);
    }

    public function updateProfile(Request $request){
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        $customer = JWTAuth::parseToken()->authenticate();

        $customer = Customer::where('id',$customer->id)->first();
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->address = $request->address;
        $customer->phone = $request->phone;
        $customer->save();

        return response()->json([
            'status'=>200,
            'message'=>'success'
        ],200);
    }

    public function changePassword(Request $request){
        $this->validate($request,[
            'previous_password' => 'required|min:6',
            'password' => 'required|min:6|confirmed',
        ]);

        $current_password = Auth::User()->Password;
        $customer = JWTAuth::parseToken()->authenticate();

        if(Hash::check($request->previous_password, $current_password))
        {
            if(Hash::check($request->password, $current_password)){
                return response()->json(['message'=>'Previous Password and Old Password Same']);
            }else{
                $customer = Customer::where('ID',$customer->ID)->first();
                $customer->Password = bcrypt($request->password);
                $customer->save();
                return response()->json(['message'=>'Password Change successfully :)']);
            }

        }else{
            return response()->json(['message'=>'Previous Password Not Correct :)']);
        }
    }

    public function me()
    {
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json($user);
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        try {
            $this->guard()->logout();
        } catch (\Exception $exception) {

        }
        return response()->json([
            'status'=>200,
            'message' => 'Successfully logged out'
        ],200);
    }

    public function customerDonationList(){
        $customer = JWTAuth::parseToken()->authenticate();
        $donationList = Donation::where('customer_id',$customer->id)->with('customer')->get();
        return new DonationCollection($donationList);
    }

    public function donatePrint($id){
        $donationList = Donation::where('customer_id',$id)->with('customer')->first();
        return new DonationResource($donationList);
    }

    public function customerProgramList(){
        $customer = JWTAuth::parseToken()->authenticate();
        $customer_event = CustomerEvent::where('customer_id',$customer->id)->get();
        return new CustomerEventCollection($customer_event);
    }

}
