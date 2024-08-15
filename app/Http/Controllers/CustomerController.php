<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\CustomerStoreRequest;
use App\Http\Resources\Customer\CustomerCollection;
use App\Http\Resources\CustomerEventCollection;
use App\Http\Resources\DonationResource;
use App\Models\Customer;
use App\Models\CustomerEvent;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerController extends Controller
{
    function __construct() {
        Config::set('jwt.user', Customer::class);
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => Customer::class,
        ]]);
    }

    public function index(){
        $customers = Customer::orderBy('created_at','desc')->paginate(10);
        return new CustomerCollection($customers);
    }

    public function updateProfile(Request $request){
        $this->validate($request, [
            'Name' => 'required',
            'Division' => 'required',
            'District' => 'required',
            'Upazilla' => 'required',
        ]);

        $customer = Customer::where('ID',$request->ID)->first();
        $customer->Name = $request->Name;
        $customer->Email = $request->Email;
        $customer->NID = $request->NID;
        $customer->Address = $request->Address;
        $customer->Division = $request->Division;
        $customer->District = $request->District;
        $customer->Upazilla = $request->Upazilla;
        //$customer->Type = 'customer';
        $customer->Status = 'Y';
        $customer->save();

        return response()->json([
            'status'=>200,
            'message'=>'success'
        ],200);
    }

    public function joinEvents(Request $request){
        $customer = JWTAuth::parseToken()->authenticate();
        $customer_event = new CustomerEvent();
        $customer_event->customer_id = $customer->id;
        $customer_event->event_id = $request->EventId;
        $customer_event->save();
        return response()->json(['message'=>'Event Created Successfully'],200);
    }

    public function getAllCustomerEvents(){
        $customer_events = CustomerEvent::with('customer','event')->orderBy('created_at','desc')->paginate(10);
        return new CustomerEventCollection($customer_events);
    }

    public function invoiceData(Request $request){
        $invoice = Donation::query()->with('customer')->where('id',$request->invoice)->first();
        return new DonationResource($invoice);
    }

    public function exportCustomer(){
        $customers = Customer::orderBy('created_at','desc')->get();
        return new CustomerCollection($customers);
    }

    public function exportEvent(){
        $customer_events = CustomerEvent::with('customer','event')->orderBy('created_at','desc')->paginate(10);
        return new CustomerEventCollection($customer_events);
    }
}
