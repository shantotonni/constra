<?php

namespace App\Http\Resources\WebMenu;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WebmenuCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($webmenu){
                return [
                    'id'=>$webmenu->id,
                    'name'=>$webmenu->name,
                    'url'=>$webmenu->url,
                    'ordering'=>$webmenu->ordering,
                    'active'=>$webmenu->active,
                ];
            })
        ];
    }
}
