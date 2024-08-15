<?php

namespace App\Http\Resources\Ramandan;

use Illuminate\Http\Resources\Json\ResourceCollection;

class RamadanCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($ramadan){
                return[
                    'id'=>$ramadan->id,
                    'name'=>$ramadan->name,
                    'image'=>$ramadan->image,

                ];
            })
        ];
    }
}
