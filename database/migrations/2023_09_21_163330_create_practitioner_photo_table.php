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
        Schema::create('practitioner_photo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('practitioner_id');
            $table->index('practitioner_id');
            $table->foreign('practitioner_id')->references('id')->on('practitioner')->onDelete('cascade');
            $table->string('data')->nullable();
            $table->string('url')->nullable();
            $table->unsignedInteger('size')->nullable();
            $table->string('hash')->nullable();
            $table->string('title')->nullable();
            $table->dateTime('creation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practitioner_photo');
    }
};
