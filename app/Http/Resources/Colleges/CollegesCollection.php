<?php

namespace App\Http\Resources\Colleges;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CollegesCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($college){
                return[
                    'id'=>$college->id,
                    'name'=>$college->name,
                    'image'=>$college->image,
                ];
            })
        ];
    }
}
