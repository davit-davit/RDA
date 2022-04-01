<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users;

class SubmitedTests extends Model
{
    use HasFactory;

    protected $table = "submited_tests";

    protected $fillable = ["user_id", "test_subject", "type"];

    protected $primarykey = "id";

    public $timestamps = true;

    public function users() {
        $this->belongsTo(Users::class);
    }
}
