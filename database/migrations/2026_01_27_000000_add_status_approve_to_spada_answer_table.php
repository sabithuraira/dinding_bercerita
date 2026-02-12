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
        Schema::table('spada_answer', function (Blueprint $table) {
            $table->tinyInteger('status_approve')->default(0)->after('answer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spada_answer', function (Blueprint $table) {
            $table->dropColumn('status_approve');
        });
    }
};
