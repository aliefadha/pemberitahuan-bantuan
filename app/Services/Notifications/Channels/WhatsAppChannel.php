<?php

namespace App\Services\Notifications\Channels;

use App\Models\WhatsAppOutboundMessage;
use App\Services\WhatsAppService;
use Illuminate\Notifications\Notification;

class WhatsAppChannel
{
    protected WhatsAppService $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    public function send(object $notifiable, Notification $notification): void
    {
        if (! method_exists($notification, 'toWhatsApp')) {
            return;
        }

        $data = $notification->toWhatsApp($notifiable);

        if (empty($data['phone']) || empty($data['message'])) {
            return;
        }

        $result = $this->whatsAppService->sendMessage(
            $data['phone'],
            $data['message']
        );

        if (
            $result === null
            || empty($data['track_response'])
            || empty($data['kegiatan_id'])
        ) {
            return;
        }

        WhatsAppOutboundMessage::create([
            'message_id' => $result['message_id'],
            'kegiatan_id' => $data['kegiatan_id'],
            'user_id' => $notifiable->getKey(),
            'chat_id' => $result['chat_id'],
            'sent_at' => now(),
        ]);
    }
}
