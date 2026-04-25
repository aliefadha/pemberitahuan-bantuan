<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('alamat')->nullable()->after('no_telepon');
        });

        Schema::create('anggota_keluarga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama');
            $table->enum('status_dalam_keluarga', ['suami', 'istri', 'anak']);
            $table->enum('status_perkawinan', ['menikah', 'belum_menikah', 'cerai']);
            $table->enum('jenis_kelamin', ['laki_laki', 'perempuan']);
            $table->date('tanggal_lahir')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota_keluarga');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('alamat');
        });
    }
};