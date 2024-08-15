<?php

namespace App\Http\Resources\Imam;

use Illuminate\Http\Resources\Json\JsonResource;

class ImamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'mobile'=>$this->mobile,
            'email'=>$this->email,
            'address'=>$this->address,
            'description'=>$this->description,
            'image'=>$this->image,
            'educational_qualification'=>$this->educational_qualification,
            'experience'=>$this->experience,
            'status'=>$this->status,
        ];
    }
}
