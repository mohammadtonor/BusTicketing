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
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('origin_terminal_id')->constrained('terminals')->onDelete('cascade');
            $table->foreignId('destination_terminal_id')->constrained('terminals')->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->integer('duration'); // Duration in minutes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
