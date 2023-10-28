<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePollVotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poll_votes', function (Blueprint $table) {
            $table->unsignedBigInteger('poll_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('poll_option_id')->index();

            $table->primary(['poll_id', 'user_id', 'poll_option_id']);
            $table->foreign('poll_id')->references('id')->on('polls')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('poll_option_id')->references('id')->on('poll_options')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('poll_votes');
    }
}
