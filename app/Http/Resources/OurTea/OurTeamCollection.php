<?php

namespace App\Http\Resources\OurTea;

use Illuminate\Http\Resources\Json\ResourceCollection;

class OurTeamCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($team){
                return [
                    'id'=>$team->id,
                    'name'=>$team->name,
                    'designation'=>$team->designation,
                    'image'=>$team->image,
                    'description'=>$team->description,
                ];
            })
        ];
    }
}
