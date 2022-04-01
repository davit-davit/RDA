<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Users;

class Notifications extends Model
{
    use HasFactory;

    protected $table = "notifications";

    protected $fillable = ["user_id", "author_avatar", "author_name", "author_lastname", "content", "seen", "test_subject_for_link"];

    protected $primarykey = "id";

    public $timestamps = true;

    public function users() {
        $this->belongsTo(Users::class);
    }
}
