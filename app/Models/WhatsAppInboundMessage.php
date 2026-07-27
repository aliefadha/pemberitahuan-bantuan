<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsAppInboundMessage extends Model
{
    protected $table = 'whatsapp_inbound_messages';

    protected $fillable = [
        'message_id',
        'whatsapp_outbound_message_id',
        'processed',
        'reply',
    ];

    protected function casts(): array
    {
        return [
            'processed' => 'boolean',
        ];
    }

    public function outboundMessage(): BelongsTo
    {
        return $this->belongsTo(
            WhatsAppOutboundMessage::class,
            'whatsapp_outbound_message_id'
        );
    }
}
