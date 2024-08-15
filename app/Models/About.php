<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $table = "about";
    protected $primaryKey = "id";
    protected $guarded = [];

    public function details(){
        return $this->hasMany(AboutDetails::class,'about_id','id');
    }
}
