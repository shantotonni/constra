<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ContactCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($contact){
                return [
                    'id'=>$contact->id,
                    'name'=>$contact->name,
                    'phone'=>$contact->phone,
                    'email'=>$contact->email,
                    'subject'=>$contact->subject,
                    'message'=>$contact->message,
                    'created_at'=>date('Y-m-d',strtotime($contact->created_at)),
                ];
            })
        ];
    }
}
