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
        Schema::create('musi_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->string('nip_baru');
            $table->string('urutreog')->nullable();
            $table->string('kdorg')->nullable();
            $table->string('nmorg')->nullable();
            $table->string('nmjab');
            $table->string('flagwil');
            $table->char('kdprop', 3);
            $table->char('kdkab', 3);
            $table->string('kdkec');
            $table->string('nmwil');
            $table->string('kdgol');
            $table->string('nmgol');
            $table->string('kdstjab');
            $table->string('nmstjab');
            $table->string('kdesl');
            $table->string('foto')->nullable();
            $table->string('kode_desa', 10)->nullable();
            $table->integer('pimpinan_id')->nullable();
            $table->string('pimpinan_nik')->nullable();
            $table->string('pimpinan_nama')->nullable();
            $table->string('pimpinan_jabatan')->nullable();
            $table->tinyInteger('is_active')->default(1);

            $table->index('kdprop', 'musi_users_prop_index');
            $table->index('kdkab', 'musi_users_kab_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('musi_users');
    }
};
