<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('peserta')->after('email')->change();
        });

        DB::table('users')->where('role', 'dosen')->update(['role' => 'peserta']);
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'peserta')->update(['role' => 'dosen']);

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('dosen')->change();
        });
    }
};