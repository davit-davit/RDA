<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Question;

class History extends Model
{
    use HasFactory;

    protected $table = "histories";

    protected $primarykey = "id";

    protected $fillable = ["question_id", "author"];

    public $timestamps = true;

    public function question() {
        $this->belongsTo(Question::class);
    }
}