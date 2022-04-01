<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users;

class Taken extends Model
{
    use HasFactory;

    protected $table = "taken_tests";

    protected $fillable = ["user_id", "test_subject"];

    protected $primarykey = "id";

    public $timestamps = true;

    public function user() {
        $this->belongsTo(Users::class);
    }
}
