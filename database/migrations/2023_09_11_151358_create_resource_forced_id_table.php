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
        Schema::create('resource_forced_id', function (Blueprint $table) {
            $table->id();
            $table->integer('res_id')->unsigned()->foreign('res_id')->references('res_id')->on('resource');
            $table->string('forced_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_forced_id');
    }
};