<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetPass extends Model
{
    use HasFactory;

    protected $table = "reset_password";

    protected $primarykey = "id";

    protected $fillable = ["random_string", "email"];

    public $timestamps = true;
}