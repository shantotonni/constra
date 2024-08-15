<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MembershipCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($member){
                return [
                    'id'=>$member->id,
                    'name'=>$member->name,
                    'phone'=>$member->phone,
                    'email'=>$member->email,
                    'age'=>$member->age,
                    'gender'=>$member->gender,
                    'address'=>$member->address,
                    'date_of_birth'=>$member->date_of_birth,
                    'father_name'=>$member->father_name,
                    'father_email'=>$member->father_email,
                    'created_at'=>date('Y-m-d',strtotime($member->created_at)),
                ];
            })
        ];
    }
}
