<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateZahinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zahins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('contestId');
            $table->integer('creationTimeSeconds')->nullable();
            $table->string('index')->nullable();
            $table->string('name')->nullable();
            $table->string('participantType')->nullable();
            $table->string('programmingLanguage')->nullable();
            $table->string('verdict')->nullable();
            $table->integer('timeConsumedMillis')->nullable();
            $table->integer('memoryConsumedBytes')->nullable();
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
        Schema::dropIfExists('zahins');
    }
}
