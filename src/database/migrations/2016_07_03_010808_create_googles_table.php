<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGooglesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('googles', function (Blueprint $table) {
            $table->increments('id');
            $table->string("email");
            $table->string("name");
            $table->string("picture");
            $table->string("link");
            $table->string("locale");
            $table->tinyInteger("verified_email");
            $table->string("access_token");
            $table->string("token_type");
            $table->string("expires_in");
            $table->string("refresh_token");
            $table->mediumText("id_token");
            $table->string("created");
            $table->string("reset_numbers")->default(26);
            $table->tinyInteger("status")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('googles');
    }

}
