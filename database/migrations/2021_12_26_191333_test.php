<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Test extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("tests", function(Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("test_subject"); // ტესტირების თემატიკის სვეტი, თუ რაზე ტარდება ტესტი
            $table->string("test_date"); // დესტირების დაწყების თარიღის სვეტი
            $table->string("test_start_time"); // ტესტირების დაწყების დროის სვეტი
            $table->string("test_duration")->nullable(); //ტესტირების ხანგრძლივობის სვეტი
            $table->string("type"); //ტესტის ტიპი - გამოკითხვაა თუ ტესტირება (quiz, test)
            $table->double("wrong_score"); // რამდენი ქულით სეფასდეს ტესტის თითოეული კითხვა 
            //არასწორი პასუხის შემთხვევაში
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
        //Schema::dropIfExists("tests");
    }
}