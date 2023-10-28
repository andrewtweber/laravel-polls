<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePollGuestVotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poll_guest_votes', function (Blueprint $table) {
            $table->unsignedBigInteger('poll_id')->index();
            $table->unsignedBigInteger('poll_option_id')->index();

            $table->foreign('poll_id')->references('id')->on('polls')->cascadeOnDelete();
            $table->foreign('poll_option_id')->references('id')->on('poll_options')->cascadeOnDelete();
        });

        DB::statement('ALTER TABLE `poll_guest_votes` ADD `ip_address` VARBINARY(16)');

        Schema::table('poll_guest_votes', function (Blueprint $table) {
            $table->index('ip_address');
            $table->primary(['poll_id', 'ip_address', 'poll_option_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('poll_guest_votes');
    }
}
