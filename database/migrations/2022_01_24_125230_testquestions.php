<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Testquestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("tquestions", function(Blueprint $table) {
            $table->bigIncrements("id"); // სიდენტიფიკაციო ნომერი
            $table->string("test_subject")->nullable(); // ტესტის თემის/დასახელების ველი თუ რომელ ტესტს ეკუთვნის კითხვა
            $table->string("question_subject")->nullable(); // უშუალოდ კითხვის თემატიკის ველი
            $table->string("type")->nullable(); //კითხვის ტიპის ველი (single choice ან free)
            $table->string("question")->nullable(); // კითხვის ველი
            $table->string("A")->nullable(); // პასუხის ვარიანტის ველი
            $table->string("B")->nullable(); // პასუხის ვარიანტის ველი
            $table->string("C")->nullable(); // პასუხის ვარიანტის ველი
            $table->string("D")->nullable(); // პასუხის ვარიანტის ველი
            $table->json("multi_answers")->nullable(); // multi სწორი პასუხის მქონე კითხვის პასუხები
            $table->json("corrects")->nullable(); // multi სწორი პასუხის მქონე კითხვის სწორი პასუხები
            $table->string("correct")->nullable(); // სწორი პასუხის ველი
            $table->double("duration_minute")->nullable(); //პასუხის ველი
            $table->bigInteger("score")->default(0); // რა ქულით შეფასდეს კითხვა სწორი პასუხის გაცემის შემთხვევაში
            $table->double("wrong_score")->default(0)->unsigned();// რა ქულით უნდა შეფასდეს ქულა არასწორი პასუხის გაცემის შემთხვევაში
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
        //
    }
}
