<?php

namespace App\Http\Resources\OurTea;

use Illuminate\Http\Resources\Json\JsonResource;

class OurTeamResource extends JsonResource
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
            'designation'=>$this->designation,
            'image'=>$this->image,
            'description'=>$this->description,
        ];
    }
}
