<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactCollection;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function list(){
        $contacts = Contact::orderBy('created_at','desc')->paginate(12);
        return new ContactCollection($contacts);
    }
}
