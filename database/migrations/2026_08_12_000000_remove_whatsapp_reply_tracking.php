<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('whatsapp_inbound_messages');
        Schema::dropIfExists('whatsapp_outbound_messages');

        if (! Schema::hasTable('kegiatan_user')) {
            return;
        }

        $indexName = 'kegiatan_user_kegiatan_id_user_id_unique';
        $hasIndex = collect(Schema::getIndexes('kegiatan_user'))
            ->contains(fn (array $index) => ($index['name'] ?? null) === $indexName);

        if ($hasIndex) {
            Schema::table('kegiatan_user', function (Blueprint $table) use ($indexName) {
                $table->dropUnique($indexName);
            });
        }
    }

    public function down(): void
    {
        // The removed WhatsApp reply feature is intentionally not recreated.
    }
};
