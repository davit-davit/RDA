<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Answer;

class TestsQuestions extends Model
{
    use HasFactory;

    protected $casts = [
        "multi_answers" => "array",
        "corrects" => "array"
    ];

    protected $table = "tquestions";

    protected $primarykey = "id";

    protected $fillable = ["test_subject", "question_subject", "type", "question", "A", "B", "C", "D", "multi_answers", "corrects", "correct", "score", "wrong_score", "duration_minute"];

    public $timestamps = true;

    public function answer() {
        $this->belongsTo(Answer::class);
    }
}