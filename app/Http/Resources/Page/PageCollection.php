<?php

namespace App\Http\Resources\Page;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PageCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($page){
                return [
                    'id'=>$page->id,
                    'title'=>$page->title,
                    'body'=>$page->body,
                    'slug'=>$page->slug,
                    'status'=>$page->status,
                ];
            })
        ];
    }
}
