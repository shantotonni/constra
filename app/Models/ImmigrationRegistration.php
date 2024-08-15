<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImmigrationRegistration extends Model
{
    use HasFactory;

    protected $table = "immigration_registration";
    protected $primaryKey = "id";
    protected $guarded = [];
}
