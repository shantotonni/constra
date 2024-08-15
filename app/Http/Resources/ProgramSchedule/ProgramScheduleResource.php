<?php

namespace App\Http\Resources\ProgramSchedule;

use Illuminate\Http\Resources\Json\JsonResource;

class ProgramScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'id'=>$this->id,
            'name'=>$this->name,
            'day'=>$this->day,
            'time'=>$this->time,
        ];
    }
}
