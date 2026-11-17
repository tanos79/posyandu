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
        if (!Schema::hasTable('pengukuran')) {
            Schema::create('pengukuran', function (Blueprint $table) {
                $table->id();
                $table->foreignId('anak_id')->constrained('anak')->onDelete('cascade');
                $table->integer('id_petugas')->nullable();
                $table->integer('kader_id')->nullable()->index();
                $table->date('tgl_pengukuran');
                $table->decimal('berat_badan', 5, 2);
                $table->decimal('tinggi_badan', 5, 2);
                $table->double('lingkar_kepala')->nullable();
                $table->text('catatan')->nullable();
                $table->enum('status_stunting', ['normal', 'stunted']);
                $table->enum('vitamin_a', ['Sudah', 'Belum'])->default('Belum');
                $table->string('imunisasi', 50)->default('-');
                $table->timestamps(); // Created_at and updated_at
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengukuran');
    }
};
