<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TakenTest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("taken_tests", function(Blueprint $table) {
            $table->bigIncrements("id");
            $table->foreignId("user_id")->nullable()->constrained()->onDelete("cascade");
            $table->string("test_subject")->nullable();
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
        //Schema::dropIfExists("taken_tests");
    }
}
