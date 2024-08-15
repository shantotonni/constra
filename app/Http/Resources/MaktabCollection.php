<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MaktabCollection extends ResourceCollection
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
            'data'=>$this->collection->transform(function ($maktab){
                return [
                    'id'=>$maktab->id,
                    'name'=>$maktab->name,
                    'gender'=>$maktab->gender,
                    'age'=>$maktab->age,
                    'date_of_birth'=>date('Y-m-d',strtotime($maktab->date_of_birth)),
                    'previous_education'=>$maktab->previous_education,
                    'previous_education_details'=>$maktab->previous_education_details,
                    'father_name'=>$maktab->father_name,
                    'mother_name'=>$maktab->mother_name,
                    'address'=>$maktab->address,
                    'father_email'=>$maktab->father_email,
                    'father_phone'=>$maktab->father_phone,
                    'mother_phone'=>$maktab->mother_phone,
                    'medical_condition'=>$maktab->medical_condition,
                    'emergency_contact_name'=>$maktab->emergency_contact_name,
                    'relation_to_student'=>$maktab->relation_to_student,
                    'emergency_contact_number'=>$maktab->emergency_contact_number,
                    'apply'=>$maktab->apply,
                    'created_at'=>date('Y-m-d',strtotime($maktab->created_at)),
                ];
            })
        ];
    }
}
