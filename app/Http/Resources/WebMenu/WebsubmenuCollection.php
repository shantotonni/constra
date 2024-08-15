<?php

namespace App\Http\Resources\WebMenu;

use Illuminate\Http\Resources\Json\ResourceCollection;

class WebsubmenuCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($submenu){
                return [
                    'id'=>$submenu->id,
                    'menu_id'=>$submenu->menu_id,
                    'menu_name'=>isset($submenu->Webmenu) ? $submenu->Webmenu->name : '',
                    'name'=>$submenu->name,
                    'icon'=>$submenu->icon,
                    'active'=>$submenu->active,
                    'slug'=>$submenu->slug,
                    'url'=>$submenu->url,
                    'ordering'=>$submenu->ordering,
                ];
            })
        ];
    }
}
