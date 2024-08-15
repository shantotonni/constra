<?php

namespace App\Http\Resources\Advisors;

use Illuminate\Http\Resources\Json\JsonResource;

class AdvisorsResource extends JsonResource
{
    public function toArray($request)
    {
        return[
            'id'=>$this->id,
            'name'=>$this->name,
            'designation'=>$this->designation,
            'address'=>$this->address,
            'mobile'=>$this->mobile,
        ];
    }
}
