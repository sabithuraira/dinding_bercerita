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
        Schema::create('curhat_anon_comment', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('curhat_anon_id');
            $table->text('comment');
            $table->timestamps();

            $table->foreign('curhat_anon_id')
                ->references('id')
                ->on('curhat_anon')
                ->onDelete('cascade');

            $table->index('curhat_anon_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curhat_anon_comment');
    }
};
