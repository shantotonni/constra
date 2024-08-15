<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BlogCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
          'data'=>$this->collection->transform(function ($blog){
              return[
                'id' => $blog->id,
                'title' => $blog->title,
                'description' => $blog->description,
                'description_front' => substr(strip_tags($blog->description), 0, 70),
                'image' => $blog->image,
                'status' => $blog->status,
                'date' => date("F j, Y",strtotime($blog->created_at)),
              ];
          })
        ];
    }
}
