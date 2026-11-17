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
        if (!Schema::hasTable('anak')) {
            Schema::create('anak', function (Blueprint $table) {
                $table->id();
                $table->integer('parent_id')->nullable()->index();
                $table->string('nik_anak', 16)->unique();
                $table->string('nama_lengkap', 150);
                $table->string('tempat_lahir', 100)->nullable();
                $table->enum('jenis_kelamin', ['L', 'P']);
                $table->date('tanggal_lahir');
                $table->float('berat_lahir')->nullable();
                $table->float('tinggi_lahir')->nullable();
                $table->string('nama_ibu', 100)->nullable();
                $table->string('no_hp', 15)->nullable();
                $table->timestamps(); // We will use Laravel's timestamps to map created_at and updated_at
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anak');
    }
};
