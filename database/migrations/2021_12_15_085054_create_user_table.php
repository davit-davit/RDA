<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("users", function (Blueprint $table) {
            $table->increments("id");
            $table->string("avatar")->default("images/avatar.png")/*->collation("utf8_general_ci")*/;
            $table->string("name")/*->collation("utf8_general_ci")*/;
            $table->string("lastname")/*->collation("utf8_general_ci")*/;
            $table->biginteger("pid")->unique();
            $table->biginteger("phone")->unique();
            $table->string("email")->unique()/*->collation("utf8_general_ci")*/;
            $table->string("department")->nullable()/*->collation("utf8_general_ci")*/;
            $table->string("service")->nullable()/*->collation("utf8_general_ci")*/;
            $table->string("position")->nullable()/*->collation("utf8_general_ci")*/;
            $table->string("password")/*->collation("utf8_general_ci")*/;
            $table->string("active_status")->default("inactive")/*->collation("utf8_general_ci")*/;
            $table->string("role")/*->collation("utf8_general_ci")*/;
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
        //Schema::dropIfExists("users");
    }
}