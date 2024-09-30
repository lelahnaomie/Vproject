<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_workinghour', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('workinghour_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('workinghour_id')->references('id')->on('workinghours');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('user_workinghour');
        
    }
};
