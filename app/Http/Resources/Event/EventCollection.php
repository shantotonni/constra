<?php

namespace App\Http\Resources\Event;

use Illuminate\Http\Resources\Json\ResourceCollection;

class EventCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($event){
                return [
                    'id'=>$event->id,
                    'title'=>$event->title,
                    'description'=>$event->description,
                    'event_date'=> date('D, d M Y',strtotime($event->event_date)),
                    'event_time'=> $event->event_time,
                    'ordering'=>$event->ordering,
                    'status'=>$event->status,
                    'image'=>$event->image,
                ];
            })
        ];
    }
}
