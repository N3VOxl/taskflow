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
    Schema::create('boards', function (Blueprint $table) {
        $table->id();
        // Relationship: A Board belongs to a Workspace
        $table->foreignId('workspace_id')->constrained()->cascadeOnDelete();
        $table->string('title');
        $table->string('color')->default('#ffffff'); // For UI styling
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boards');
    }
};
