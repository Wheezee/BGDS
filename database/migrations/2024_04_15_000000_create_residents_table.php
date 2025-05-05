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
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            
            // Personal Information
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('place_of_birth');
            $table->date('date_of_birth');
            $table->integer('age');
            $table->enum('sex', ['Male', 'Female']);
            $table->enum('civil_status', ['Single', 'Married', 'Widowed', 'Divorced']);
            $table->string('citizenship');
            $table->string('occupation')->nullable();
            $table->enum('labor_status', ['Employed', 'Unemployed', 'PWD', 'OFW', 'Solo Parent', 'OSY'])->nullable();
            $table->string('contact_number');
            $table->string('email')->nullable();
            $table->string('education')->nullable();
            
            // Family Information
            $table->string('mother_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('philsys_number')->nullable();
            $table->string('household_id')->nullable();
            $table->enum('program_participation', ['MCCT', '4PS', 'UCT'])->nullable();
            $table->string('family_group')->nullable();
            
            // Identity Information
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->decimal('height', 5, 2)->nullable(); // in inches
            $table->decimal('weight', 5, 2)->nullable(); // in kg
            $table->enum('skin_complexion', ['Fair', 'Medium', 'Light Brown', 'Brown', 'Black'])->nullable();
            $table->enum('voter', ['Yes', 'No'])->nullable();
            $table->enum('resident_voter', ['Yes', 'No'])->nullable();
            $table->integer('year_last_voted')->nullable();
            
            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
}; 