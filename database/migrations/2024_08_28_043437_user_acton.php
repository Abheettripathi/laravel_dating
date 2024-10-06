<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserActon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_actions',function (Blueprint $table){
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('target_user_id');
        $table->string('action');
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('target_user_id')->references('id')->on('users')->onDelete('cascade');

        $table->unique(['user_id','target_user_id']);
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_actions');
    }
}
