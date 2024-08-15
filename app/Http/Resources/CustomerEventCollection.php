<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerEventCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($CE){
                return [
                    'id'=>$CE->id,
                    'customer_name'=>isset($CE->customer) ? $CE->customer->name: '',
                    'customer_email'=>isset($CE->customer) ? $CE->customer->email: '',
                    'customer_phone'=>isset($CE->customer) ? $CE->customer->phone: '',
                    'customer_address'=>isset($CE->customer) ? $CE->customer->address: '',
                    'title'=>isset($CE->event) ? $CE->event->title: '',
                    'event_date'=>isset($CE->event) ? $CE->event->event_date: '',
                ];
            })
        ];
    }
}
