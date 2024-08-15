<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DonationResource extends JsonResource
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
            'purpose'=>$this->purpose,
            'amount'=>$this->amount,
            'date'=>date('Y-m-d',strtotime($this->created_at)),
            'customer_name'=>isset($this->customer) ? $this->customer->name: '',
            'customer_email'=>isset($this->customer) ? $this->customer->email: '',
            'customer_phone'=>isset($this->customer) ? $this->customer->phone: '',
        ];
    }
}
