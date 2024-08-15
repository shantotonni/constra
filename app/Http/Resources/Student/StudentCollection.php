<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Resources\Json\ResourceCollection;

class StudentCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return [
            'data'=>$this->collection->transform(function ($student){
                $date_of_birth = '';
                if ($student->date_of_birth == '1970-01-01'){
                    $date_of_birth = '';
                }else{
                    $date_of_birth =  $student->date_of_birth;
                }
                return [
                    'student_id'=>$student->student_id,
                    'first_name'=>$student->first_name,
                    'last_name'=>$student->last_name,
                    'full_name'=>$student->first_name .' '.$student->last_name,
                    'roll_no'=>$student->roll_no,
                    'email'=>$student->email,
                    'mobile'=>$student->mobile,
                    'date_of_birth'=>$date_of_birth,
                    'nid'=>$student->nid,
                    'address'=>$student->address,
                    'nationality'=>$student->nationality,
                    'session_id'=>$student->session_id,
                    'category_id'=>$student->category_id,
                    'session_name'=>isset($student->session) ? $student->session->name:'',
                    'category_name'=>isset($student->category) ? $student->category->name:'',
                    'batch_number'=>$student->batch_number,
                    'status'=>$student->status,
                    'is_hostel'=>$student->is_hostel,
                ];
            })
        ];
    }
}
