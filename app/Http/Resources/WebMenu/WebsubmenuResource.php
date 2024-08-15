<?php

namespace App\Http\Resources\WebMenu;

use Illuminate\Http\Resources\Json\JsonResource;

class WebsubmenuResource extends JsonResource
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
            'menu_id'=>$this->menu_id,
            'menu_name'=>isset($this->Webmenu) ? $this->Webmenu->name : '',
            'name'=>$this->name,
            'icon'=>$this->icon,
            'active'=>$this->active,
            'slug'=>$this->slug,
            'url'=>$this->url,
        ];
    }
}
