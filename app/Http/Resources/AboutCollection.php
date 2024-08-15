<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AboutCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($about){
                return [
                    'id'=>$about->id,
                    'title'=>$about->title,
                    'short_intro'=>$about->short_intro,
                    'image'=>$about->image,
                    'description'=>$about->description,
                    'details'=>$about->details,
                ];
            })
        ];
    }
}
