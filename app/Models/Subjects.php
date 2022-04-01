<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{
    use HasFactory;

    protected $table = "subjects";

    protected $primarykey = "id";

    protected $fillable = ["subject_name"];
    
    public $timestamps = true;
}
