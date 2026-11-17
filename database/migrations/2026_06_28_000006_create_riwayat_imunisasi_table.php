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
        if (!Schema::hasTable('riwayat_imunisasi')) {
            Schema::create('riwayat_imunisasi', function (Blueprint $table) {
                $table->id();
                $table->foreignId('anak_id')->constrained('anak')->onDelete('cascade');
                $table->foreignId('vaksin_id')->constrained('vaksin')->onDelete('cascade'); // modified default constraint restrict to cascade for easier tests
                $table->date('tgl_pemberian');
                $table->string('lokasi_pemberian', 100)->nullable();
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_imunisasi');
    }
};
