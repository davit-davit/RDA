<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users;

class Answer extends Model
{
    use HasFactory;

    protected $casts = [
        "answers" => "array"
    ];

    protected $table = "answers";

    protected $fillable = ["user_id", "tquestion_id", "user_name", "user_lastname", "test_subject", "question", "answer", "answers", "score"];

    protected $primarykey = "id";

    public $timestamps = true;

    public function user() {
        $this->belongsTo(Users::class);
    }

    public function tests_questions() {
        $this->hasMany(TestsQuestions::class);
    }
}
