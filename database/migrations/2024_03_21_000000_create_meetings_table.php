<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_time');
            $table->string('agenda');
            $table->string('venue');
            $table->string('organizer');
            $table->json('attendees');
            $table->json('absentees')->nullable();
            $table->text('transcription')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('meetings');
    }
}; 