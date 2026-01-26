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
        Schema::create('spada_question', function (Blueprint $table) {
            $table->id();
            $table->text("question");
            $table->tinyInteger("type_question");
            $table->date("start_active");
            $table->date("last_active");
            $table->string("validate_rule")->nullable();
            $table->char('satker', 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spada_question');
    }
};
