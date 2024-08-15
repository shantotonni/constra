<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'student_id'=>$this->student_id,
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'full_name'=>$this->first_name .' '.$this->last_name,
            'roll_no'=>$this->roll_no,
            'email'=>$this->email,
            'mobile'=>$this->mobile,
            'date_of_birth'=>$this->date_of_birth,
            'nid'=>$this->nid,
            'address'=>$this->address,
            'nationality'=>$this->nationality,
            'session_id'=>$this->session_id,
            'category_id'=>$this->category_id,
            'session_name'=>isset($this->session) ? $this->session->name:'',
            'category_name'=>isset($this->category) ? $this->category->name:'',
            'currency'=>isset($this->category->currency) ? $this->category->currency->name:'',
            'batch_number'=>$this->batch_number,
            'status'=>$this->status,
        ];
    }
}
