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
        Schema::create('organization', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->index('resource_id');
            $table->foreign('resource_id')->references('id')->on('resource')->onDelete('cascade');
            $table->boolean('active');
            $table->json('type')->nullable();
            $table->string('name');
            $table->json('alias')->nullable();
            $table->string('part_of')->nullable();
            $table->json('endpoint')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization');
    }
};
