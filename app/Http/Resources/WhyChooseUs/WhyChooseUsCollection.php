<?php

namespace App\Http\Resources\WhyChooseUs;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WhyChooseUsCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($choose){
                return [
                    'id'=>$choose->id,
                    'icon'=>$choose->icon,
                    'title'=>$choose->title,
                    'short_intro'=>$choose->short_intro,
                ];
            })
        ];
    }
}
