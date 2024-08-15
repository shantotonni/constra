<?php

namespace App\Http\Resources\Advisors;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AdvisorsCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($advisor){
                return[
                   'id'=>$advisor->id,
                   'name'=>$advisor->name,
                   'designation'=>$advisor->designation,
                   'address'=>$advisor->address,
                   'mobile'=>$advisor->mobile,
                ];
            })
            ];
    }
}
