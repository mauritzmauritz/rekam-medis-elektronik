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
        Schema::create('medication_dispense_dosage', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispense_id');
            $table->index('dispense_id');
            $table->foreign('dispense_id')->references('id')->on('medication_dispense')->onDelete('cascade');
            $table->integer('sequence')->nullable();
            $table->text('text')->nullable();
            $table->text('patient_instruction')->nullable();
            $table->json('timing_event')->nullable();
            $table->json('timing_repeat')->nullable();
            $table->string('timing_system')->nullable();
            $table->string('timing_code')->nullable();
            $table->string('timing_display')->nullable();
            $table->string('site_system')->nullable();
            $table->string('site_code')->nullable();
            $table->string('site_display')->nullable();
            $table->string('route_system')->nullable();
            $table->string('route_code')->nullable();
            $table->string('route_display')->nullable();
            $table->string('method_system')->nullable();
            $table->string('method_code')->nullable();
            $table->string('method_display')->nullable();
            $table->decimal('max_dose_per_period_numerator_value')->nullable();
            $table->enum('max_dose_per_period_numerator_comparator', ['<', '<=', '>=', '>'])->nullable();
            $table->string('max_dose_per_period_numerator_unit')->nullable();
            $table->string('max_dose_per_period_numerator_system')->nullable();
            $table->string('max_dose_per_period_numerator_code')->nullable();
            $table->decimal('max_dose_per_period_denominator_value')->nullable();
            $table->enum('max_dose_per_period_denominator_comparator', ['<', '<=', '>=', '>'])->nullable();
            $table->string('max_dose_per_period_denominator_unit')->nullable();
            $table->string('max_dose_per_period_denominator_system')->nullable();
            $table->string('max_dose_per_period_denominator_code')->nullable();
            $table->decimal('max_dose_per_administration_value')->nullable();
            $table->string('max_dose_per_administration_unit')->nullable();
            $table->string('max_dose_per_administration_system')->nullable();
            $table->string('max_dose_per_administration_code')->nullable();
            $table->decimal('max_dose_per_lifetime_value')->nullable();
            $table->string('max_dose_per_lifetime_unit')->nullable();
            $table->string('max_dose_per_lifetime_system')->nullable();
            $table->string('max_dose_per_lifetime_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_dispense_dosage');
    }
};
