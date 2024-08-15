<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VolunteerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $name;

    public function __construct($data , $name)
    {
        $this->data = $data;
        $this->name = $name;
    }

    public function build()
    {
        return $this->from('tonnishanto@gmail.com')->subject('Confirmation of Volunteer Form Submission')->view('mail.volunteer',[
            'title' => $this->name,
            'data' => $this->data,
        ]);
    }
}
