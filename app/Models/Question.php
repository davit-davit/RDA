<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\History;

class Question extends Model
{
    use HasFactory;
    
    protected $casts = [
        "answers" => "array", // პასუხების ველი json ტიპიდან გადაკეთდება მასივის ტიპად
        "corrects" => "array" // სწორი პასუხების ველი json ტიპიდან გადაკეთდება მასივის ტიპად
    ];

    protected $table = "questions";

    protected $primarykey = "id";

    protected $fillable = ["question_subject", "type", "question", "A", "B", "C", "D", "answers", "corrects", "correct", "score", "wrong_score", "duration_minute", "status"];

    public $timestamps = true;

    public function history() {
        $this->hasMany(history::class);
    }
}