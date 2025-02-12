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
        Schema::create('medication_dispense', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->json('part_of')->nullable();
            $table->string('status');
            $table->enum('category', ['inpatient', 'outpatient', 'community', 'discharge'])->nullable();
            $table->string('medication');
            $table->string('subject');
            $table->string('context')->nullable();
            $table->string('location')->nullable();
            $table->json('authorizing_prescription')->nullable();
            $table->decimal('quantity_value')->nullable();
            $table->string('quantity_unit')->nullable();
            $table->string('quantity_system')->nullable();
            $table->string('quantity_code')->nullable();
            $table->decimal('days_supply_value')->nullable();
            $table->string('days_supply_unit')->nullable();
            $table->string('days_supply_system')->nullable();
            $table->string('days_supply_code')->nullable();
            $table->dateTime('when_prepared')->nullable();
            $table->dateTime('when_handed_over')->nullable();
            $table->boolean('substitution_was_substituted')->nullable();
            $table->string('substitution_type')->nullable();
            $table->json('substitution_reason')->nullable();
            $table->json('substitution_responsible_party')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_dispense');
    }
};
