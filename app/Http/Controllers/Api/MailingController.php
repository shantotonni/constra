<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MailingCollection;
use App\Models\Mailing;
use Illuminate\Http\Request;

class MailingController extends Controller
{
    public function list(){
        $mailings = Mailing::orderBy('created_at','desc')->paginate(12);
        return new MailingCollection($mailings);
    }
}
