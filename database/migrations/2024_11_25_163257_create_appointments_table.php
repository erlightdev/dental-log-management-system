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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id');
            $table->foreignId('doctor_id');
            $table->string('staff_id')->nullable();
            $table->date('appointment_date');
            $table->string('estimated_duration')->nullable();
            $table->enum('status', ["scheduled","reschedule","completed","cancelled"]);
            $table->string('cancellation_reason', 255)->nullable();
            $table->string('appointment_amount_deposits');
            $table->enum('payment_status', ["paid","unpaid"])->nullable();
            $table->string('total_appointment_amount_deposits');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
