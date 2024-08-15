<?php

namespace App\Http\Resources\Slider;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SliderCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($slider){
                return [
                    'id'=>$slider->id,
                    'title'=>$slider->title,
                    'paragraph'=>$slider->paragraph,
                    'link'=>$slider->link,
                    'ordering'=>$slider->ordering,
                    'status'=>$slider->status,
                    'image'=>$slider->image,
                ];
            })
        ];
    }
}
