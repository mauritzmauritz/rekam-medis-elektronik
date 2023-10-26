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
        Schema::create('medication_dispense_substitution', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dispense_id');
            $table->index('dispense_id');
            $table->foreign('dispense_id')->references('id')->on('medication_dispense')->onDelete('cascade');
            $table->boolean('was_substituted');
            $table->string('type_system')->nullable();
            $table->string('type_code')->nullable();
            $table->string('type_display')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_dispense_substitution');
    }
};