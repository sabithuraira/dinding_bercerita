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
        Schema::table('kutipan_buku', function (Blueprint $table) {
            $table->dropColumn(['created_nip', 'is_active']);
            $table->date('date_show')->nullable()->after('dikutip_dari');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kutipan_buku', function (Blueprint $table) {
            $table->string('created_nip')->nullable()->after('dikutip_dari');
            $table->tinyInteger('is_active')->default(1)->after('created_nip');
            $table->dropColumn('date_show');
        });
    }
};
