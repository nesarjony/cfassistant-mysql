<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('handle');

            $table->string('cFromDate')->nullable();
            $table->string('cToDate')->nullable();
            $table->boolean('cGym')->nullable();

            $table->string('pFromDate')->nullable();
            $table->string('pToDate')->nullable();
            $table->boolean('pGym')->nullable();
            $table->integer('pRatingFrom')->nullable();
            $table->integer('pRatingTo')->nullable();
            $table->boolean('sortByDate')->nullable();
            $table->boolean('order')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
