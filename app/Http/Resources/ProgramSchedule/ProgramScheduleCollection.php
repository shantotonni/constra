<?php

namespace App\Http\Resources\ProgramSchedule;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProgramScheduleCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
          'data'=>$this->collection->transform(function ($schedule){
              return[
                'id'=>$schedule->id,
                'name'=>$schedule->name,
                'day'=>$schedule->day,
                'time'=>$schedule->time,
              ];
          })
        ];
    }
}
