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
        Schema::create('clinical_impression_investigation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('impression_id');
            $table->index('impression_id');
            $table->foreign('impression_id')->references('id')->on('clinical_impression')->onDelete('cascade');
            $table->string('code')->nullable();
            $table->string('code_text')->nullable();
            $table->json('item')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinical_impression_investigation');
    }
};
