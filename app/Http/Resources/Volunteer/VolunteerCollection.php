<?php

namespace App\Http\Resources\Volunteer;

use Illuminate\Http\Resources\Json\ResourceCollection;

class VolunteerCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($volunteer){
                return [
                    'id'=>$volunteer->id,
                    'name'=>$volunteer->name,
                    'email'=>$volunteer->email,
                    'message'=>$volunteer->message,
                ];
            })
        ];
    }
}
