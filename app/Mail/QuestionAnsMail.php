<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuestionAnsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $title;

    public function __construct($data,$title)
    {
        $this->data = $data;
        $this->title = $title;
    }


    public function build()
    {
        return $this->from('no-reply@aci-bd.com')->subject('Question Answer Reply')->view('mail.question_ans',[
            'title' => $this->title,
            'data' => $this->data,
        ]);
    }
}
