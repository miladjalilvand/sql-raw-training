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
        Schema::create('machine_fault_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id');
            $table->foreignId('machine_fault_id');
            $table->foreignId('start_machine_log_id');
            $table->foreignId('end_machine_log_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine_fault_histories');
    }
};
