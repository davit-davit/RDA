<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Question extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("questions", function(Blueprint $table) {
            $table->bigIncrements("id"); // სიდენტიფიკაციო ნომერი
            $table->string("question_subject")->nullable(); // უშუალოდ კითხვის თემატიკის ველი
            $table->string("type")->nullable(); //კითხვის ტიპის ველი (ერთი სწორი პასუხით, რამდენიმე ან ღია)
            $table->string("question")->nullable(); // კითხვის ველი
            $table->string("A")->nullable(); // პასუხის ვარიანტის ველი
            $table->string("B")->nullable(); // პასუხის ვარიანტის ველი
            $table->string("C")->nullable(); // პასუხის ვარიანტის ველი
            $table->string("D")->nullable(); // პასუხის ვარიანტის ველი
            $table->json("answers")->nullable(); // multi სწორი პასუხის მქონე კითხვის პასუხები
            $table->json("corrects")->nullable(); // multi სწორი პასუხის მქონე კითხვის სწორი პასუხები
            $table->string("correct")->nullable(); // სწორი პასუხის ველი
            $table->double("duration_minute")->nullable(); //პასუხის ველი
            $table->bigInteger("score")->default(0); // რა ქულით შეფასდეს კითხვა სწორი პასუხის გაცემის შემთხვევაში
            $table->double("wrong_score")->default(0)->unsigned();// რა ქულით უნდა შეფასდეს ქულა არასწორი პასუხის გაცემის შემთხვევაში
            $table->integer("status")->default(1); // კითხვის სტატუსის ველი თუ 0 ესეიგი არააქტიური კითხვაა და თუ 1 ესეიგი აქტიურია
            $table->integer("deleted")->default(0); // სვეტი აღნიშნავს კითხვა წაშლილია თუ არა. 0 ნიშნავს, რომ არაა წაშლილი, 1 კი ნიშნავს წაშლილს
            $table->timestamps(); // ცხრილში შეიქმნება შექმნა/განახლების თარიღის ველები
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists("questions");
    }
}
