<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PasswordReset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("reset_password", function(Blueprint $table) {
            $table->increments("id");
            $table->string("random_string", 6)->unique()/*->collation("utf8_general_ci")*/;
            $table->string("email")/*->collation("utf8_general_ci")*/;
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
        //Schema::dropIfExists("reset_password");
    }
}
