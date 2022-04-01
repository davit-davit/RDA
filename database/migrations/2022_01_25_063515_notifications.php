<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Notifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("notifications", function(Blueprint $table) {
            $table->bigIncrements("id");
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            $table->string("type");
            $table->string("author_avatar")->default("images/avatar.png");
            $table->string("author_name");
            $table->string("author_lastname");
            $table->string("content");
            $table->string("seen")->default(0);
            $table->string("test_subject_for_link");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists("notifications");
    }
}
