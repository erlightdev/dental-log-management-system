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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('patient_before_image', 255)->nullable();
            $table->string('patient_after_image', 255)->nullable();
            $table->enum('gender', ["male","female","other"]);
            $table->string('dob')->nullable();
            $table->enum('blood_type', ["A+","A-","B+","B-","AB+","AB-","O+","O-"])->nullable();
            $table->string('address')->nullable();
            $table->string('medical_history_current_medications')->nullable();
            $table->string('treatment_name', 100)->nullable();
            $table->string('treatment_price');
            $table->string('service_name', 100)->nullable();
            $table->string('service_price');
            $table->string('initial_deposit');
            $table->string('total_appointment_amount_deposits');
            $table->string('total_amount');
            $table->date('registration_date');
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
