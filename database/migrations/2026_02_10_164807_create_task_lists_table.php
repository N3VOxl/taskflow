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
    Schema::create('task_lists', function (Blueprint $table) {
        $table->id();
        // Relationship: A List belongs to a Board
        $table->foreignId('board_id')->constrained()->cascadeOnDelete();
        $table->string('name');
        $table->integer('position')->default(0); // To keep lists in order
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_lists');
    }
};
