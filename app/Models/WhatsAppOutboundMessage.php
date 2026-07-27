<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsAppOutboundMessage extends Model
{
    protected $table = 'whatsapp_outbound_messages';

    protected $fillable = [
        'message_id',
        'kegiatan_id',
        'user_id',
        'chat_id',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    public function kegiatan(): BelongsTo
    {
        return $this->belongsTo(Kegiatan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function inboundMessages(): HasMany
    {
        return $this->hasMany(
            WhatsAppInboundMessage::class,
            'whatsapp_outbound_message_id'
        );
    }
}
