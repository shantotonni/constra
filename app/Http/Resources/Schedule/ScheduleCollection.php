<?php

namespace App\Http\Resources\Schedule;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ScheduleCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($time){
                return [
                    'id'=>$time->id,
                    'time'=>$time->time,
                    'type'=>$time->type,
                ];
            })
        ];
    }
}
