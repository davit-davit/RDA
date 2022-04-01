<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users;

class Test extends Model
{
    use HasFactory;

    protected $table = "tests";

    protected $primarykey = "id";

    protected $fillable = ["test_subject", "test_date", "test_start_time", "test_duration", "type", "wrong_score"];

    public $timestamps = true;
}