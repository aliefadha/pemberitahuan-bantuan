<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kegiatan_user', function (Blueprint $table) {
            $table->unique(
                ['kegiatan_id', 'user_id'],
                'kegiatan_user_kegiatan_id_user_id_unique'
            );
        });

        Schema::create('whatsapp_outbound_messages', function (Blueprint $table) {
            $table->id();
            $table->string('message_id')->unique();
            $table->foreignId('kegiatan_id')->constrained()->cascadeOnDelete();
            $table->string('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('chat_id');
            $table->timestamp('sent_at');
            $table->timestamps();
        });

        Schema::create('whatsapp_inbound_messages', function (Blueprint $table) {
            $table->id();
            $table->string('message_id')->unique();
            $table->foreignId('whatsapp_outbound_message_id')
                ->constrained('whatsapp_outbound_messages')
                ->cascadeOnDelete();
            $table->boolean('processed')->default(false);
            $table->text('reply')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_inbound_messages');
        Schema::dropIfExists('whatsapp_outbound_messages');

        Schema::table('kegiatan_user', function (Blueprint $table) {
            $table->dropUnique('kegiatan_user_kegiatan_id_user_id_unique');
        });
    }
};
