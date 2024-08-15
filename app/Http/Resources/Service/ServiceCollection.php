<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ServiceCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($service){
                return [
                    'id'=>$service->id,
                    'title'=>$service->title,
                    'name'=>$service->name,
                    'description_without_html' => substr(strip_tags($service->description), 0, 70),
                    'description'=>$service->description,
                    'image'=>$service->image,
                    'status'=>$service->status,
                    'ordering'=>$service->ordering,
                ];
            })
        ];
    }
}
