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
        Schema::create('composition_event', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('composition_id');
            $table->index('composition_id');
            $table->foreign('composition_id')->references('id')->on('composition')->onDelete('cascade');
            $table->json('code')->nullable();
            $table->dateTime('period_start')->nullable();
            $table->dateTime('period_end')->nullable();
            $table->json('detail')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('composition_event');
    }
};
