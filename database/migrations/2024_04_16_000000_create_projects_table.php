<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->date('start_date');
            $table->date('target_end_date');
            $table->enum('project_type', ['BDP', 'Calamity']);
            $table->enum('status', ['Not Started', 'Ongoing', 'Delayed', 'Completed']);
            $table->integer('progress')->default(0); // Percentage completed
            $table->string('assigned_committee');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
}; 