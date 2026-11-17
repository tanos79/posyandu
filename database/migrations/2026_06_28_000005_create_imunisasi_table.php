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
        if (!Schema::hasTable('imunisasi')) {
            Schema::create('imunisasi', function (Blueprint $table) {
                $table->id();
                $table->integer('id_anak')->nullable()->index();
                $table->string('jenis_vaksin', 100)->nullable();
                $table->date('tgl_jadwal')->nullable();
                $table->enum('status', ['Belum', 'Selesai'])->default('Belum');
                $table->date('tgl_selesai')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imunisasi');
    }
};
