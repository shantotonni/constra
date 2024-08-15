<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutDetails extends Model
{
    use HasFactory;

    protected $table = "about_details";
    protected $primaryKey = "id";
    protected $guarded = [];
}
