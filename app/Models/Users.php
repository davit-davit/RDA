<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use App\Models\Test;
use App\Models\Notifications;
use App\Models\Answer;
use App\Models\SubmitedTests;

class Users extends Model implements Authenticatable
{
    use HasFactory;
    use AuthenticableTrait;

    protected $table = "users";

    protected $primarykey = "id";

    protected $fillable = ["avatar", "name", "lastname", "pid", "phone", "email", "department", "service", "position", "password", "password2", "role"];

    public $timestamps = true;

    public function taken_test() {
        $this->hasMany(Test::class);
    }

    public function notifications() {
        $this->hasMany(Notifications::class);
    }

    public function answers() {
        $this->hasMany(Answer::class);
    }

    public function submited_tests() {
        $this->hasMany(SubmitedTests::class);
    }
}