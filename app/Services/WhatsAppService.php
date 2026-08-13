<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private string $baseUrl;

    private string $token;

    private int $timeout = 10;

    public function __construct()
    {
        $this->baseUrl = rtrim(
            (string) config('services.whatsapp.url', 'http://localhost:3001'),
            '/'
        );
        $this->token = (string) config('services.whatsapp.token', '');
    }

    private function request(): PendingRequest
    {
        return Http::acceptJson()
            ->withToken($this->token)
            ->timeout($this->timeout);
    }

    public function isConnected(): bool
    {
        try {
            $response = $this->request()->get("{$this->baseUrl}/status");

            return $response->successful()
                && $response->json('ready') === true;
        } catch (\Throwable $e) {
            Log::warning('WhatsApp status check failed', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    public function getStatus(): array
    {
        try {
            $response = $this->request()->get("{$this->baseUrl}/status");

            if (! $response->successful()) {
                return [
                    'status' => 'error',
                    'ready' => false,
                    'error' => "WhatsApp service returned HTTP {$response->status()}",
                ];
            }

            return $response->json() ?? [
                'status' => 'error',
                'ready' => false,
                'error' => 'WhatsApp service returned an invalid response',
            ];
        } catch (\Throwable $e) {
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
            $response = $this->request()->get("{$this->baseUrl}/qr");

            if (! $response->successful()) {
                return [
                    'status' => 'error',
                    'qr' => null,
                    'error' => "WhatsApp service returned HTTP {$response->status()}",
                ];
            }

            $data = $response->json();

            if (! is_array($data)) {
                return [
                    'status' => 'error',
                    'qr' => null,
                    'error' => 'WhatsApp service returned an invalid response',
                ];
            }

            if (($data['status'] ?? null) === 'ready') {
                return null;
            }

            return [
                'status' => $data['status'] ?? 'unknown',
                'qr' => $data['qr'] ?? null,
            ];
        } catch (\Throwable $e) {
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
            $response = $this->request()->post("{$this->baseUrl}/send", [
                'phone' => $cleanPhone,
                'message' => $message,
            ]);

            if (
                ! $response->successful()
                || $response->json('success') !== true
                || ! is_string($response->json('message_id'))
                || ! is_string($response->json('chat_id'))
            ) {
                Log::warning('WhatsApp send returned an invalid response', [
                    'status' => $response->status(),
                ]);

                return null;
            }

            return [
                'message_id' => $response->json('message_id'),
                'chat_id' => $response->json('chat_id'),
            ];
        } catch (\Throwable $e) {
            Log::error('WhatsApp send failed', [
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function restart(): bool
    {
        try {
            $response = $this->request()->post("{$this->baseUrl}/restart");

            return $response->successful()
                && $response->json('success') === true;
        } catch (\Throwable $e) {
            Log::error('WhatsApp restart failed', [
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function formatPhoneNumber(string $phone): ?string
    {
        $clean = preg_replace('/[^0-9]/', '', $phone);

        if (! $clean) {
            return null;
        }

        if (str_starts_with($clean, '0')) {
            $clean = '62'.substr($clean, 1);
        }

        if (str_starts_with($clean, '62')) {
            return strlen($clean) >= 10 && strlen($clean) <= 15
                ? $clean
                : null;
        }

        if (strlen($clean) >= 10 && strlen($clean) <= 13) {
            return '62'.$clean;
        }

        return null;
    }

    public function formatForWhatsApp(string $phone): string
    {
        $formatted = $this->formatPhoneNumber($phone);

        return $formatted ? $formatted.'@c.us' : '';
    }
}
