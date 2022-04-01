<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Answers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("answers", function(Blueprint $table) {
            $table->bigIncrements("id");
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            $table->foreignId("tquestion_id")->constrained()->onDelete("cascade"); // იმ კითხვის ID, რომელ კითხვასაც გაეცა პასუხი
            $table->string("user_name"); // პასუხის ავტორის სახელი
            $table->string("user_lastname"); // პასუხის ავტორის გვარი
            $table->string("test_subject"); // ტესტის თემატიკის ველი თუ რა ტესტის კუთვნილ კითხვას გაეცა პასუხი
            $table->string("question"); // კითხვის ველი
            $table->string("answer")->nullable(); // პასუხის ველი
            $table->json("answers")->nullable(); // პასუხის ველი
            $table->double("score")->nullable(); // აქ ინახება თუ რა ქულით შეფასდა პასუხი
            $table->double("answer_score")->nullable(); // ამ ველში ინახება ის მონაცემი თუ რა ქულით ფასდება პასუხი
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
        //Schema::dropIfExists("answers");
    }
}
