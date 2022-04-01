<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TestSubmited extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("submited_tests", function(Blueprint $table) {
            $table->bigIncrements("id");
            $table->foreignId("user_id")->default(0)->constrained()->onDelete("cascade");
            $table->string("test_subject")->default("jh");
            $table->string("type"); //ტესტის ტიპი
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
        //
    }
}
