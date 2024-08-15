<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seeting extends Model
{
    use HasFactory;

    protected $table = "seetings";
    protected $primaryKey = "id";
    protected $guarded = [];
}
