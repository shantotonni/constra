<?php

namespace App\Http\Resources\Testimonial;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TestimonialCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($testimonial){
                return[
                  'id'=>$testimonial->id,
                  'name'=>$testimonial->name,
                  'image'=>$testimonial->image,
                  'complement'=>$testimonial->complement,
                  'position'=>$testimonial->position,

                ];
            })
        ];
    }
}
