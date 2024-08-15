<?php

namespace App\Http\Resources\Imam;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ImamCollection extends ResourceCollection
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
          'data'=>$this->collection->transform(function ($imam){
              return [
                  'id'=>$imam->id,
                  'name'=>$imam->name,
                  'mobile'=>$imam->mobile,
                  'email'=>$imam->email,
                  'address'=>$imam->address,
                  'description'=>$imam->description,
                  'image'=>$imam->image,
                  'educational_qualification'=>$imam->educational_qualification,
                  'experience'=>$imam->experience,
                  'status'=>$imam->status,
              ];
          })
        ];
    }
}
