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
        Schema::create('spada_answer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('spada_question')->onDelete('cascade');
            $table->text('answer');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spada_answer');
    }
};
