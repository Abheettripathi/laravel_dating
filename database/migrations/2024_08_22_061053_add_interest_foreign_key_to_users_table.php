<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInterestForeignKeyToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Assuming the 'interests' column in 'users' is supposed to store the interest_id
            $table->unsignedBigInteger('interest')->change();
            $table->foreign('interest')->references('id')->on('interests')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['interest']);
        });
    }
}
