<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    private string $baseUrl;

    private int $timeout = 10;

    public function __construct()
    {
        $this->baseUrl = config('services.whatsapp.url', 'http://localhost:3001');
    }

    public function isConnected(): bool
    {
        try {
            $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/status");

            return $response->successful() && $response->json('ready') === true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getStatus(): array
    {
        try {
            $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/status");

            return $response->json();
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'ready' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function getQrCode(): ?array
    {
        try {
            $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/qr");
            $data = $response->json();

            if ($data['status'] === 'ready') {
                return null;
            }

            return [
                'status' => $data['status'],
                'qr' => $data['qr'] ?? null,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'qr' => null,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * @return array{message_id: string, chat_id: string}|null
     */
    public function sendMessage(string $phone, string $message): ?array
    {
        $cleanPhone = $this->formatPhoneNumber($phone);

        if (! $cleanPhone) {
            return null;
        }

        try {
            $response = Http::timeout($this->timeout)
                ->post("{$this->baseUrl}/send", [
                    'phone' => $cleanPhone,
                    'message' => $message,
                ]);

            if (
                ! $response->successful()
                || $response->json('success') !== true
                || ! is_string($response->json('message_id'))
                || ! is_string($response->json('chat_id'))
            ) {
                return null;
            }

            return [
                'message_id' => $response->json('message_id'),
                'chat_id' => $response->json('chat_id'),
            ];
        } catch (\Exception $e) {
            \Log::error('WhatsApp send failed: '.$e->getMessage());

            return null;
        }
    }

    public function restart(): bool
    {
        try {
            $response = Http::timeout($this->timeout)->post("{$this->baseUrl}/restart");

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    private function formatPhoneNumber(string $phone): ?string
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($clean, '0')) {
            $clean = '62'.substr($clean, 1);
        }

        if (str_starts_with($clean, '62')) {
            return $clean;
        }

        if (strlen($clean) >= 10) {
            return '62'.$clean;
        }

        return null;
    }

    public function formatForWhatsApp(string $phone): string
    {
        return $this->formatPhoneNumber($phone).'@c.us';
    }
}
