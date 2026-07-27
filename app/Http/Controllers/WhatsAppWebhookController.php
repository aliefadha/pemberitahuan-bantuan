<?php

namespace App\Http\Controllers;

use App\Models\WhatsAppInboundMessage;
use App\Models\WhatsAppOutboundMessage;
use App\Services\KegiatanResponseService;
use App\Services\WhatsAppKegiatanResponseParser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WhatsAppWebhookController extends Controller
{
    public function __invoke(
        Request $request,
        WhatsAppKegiatanResponseParser $parser,
        KegiatanResponseService $responseService
    ): JsonResponse {
        if (! $this->hasValidSecret($request)) {
            return response()->json(['message' => 'Unauthorized.'], 401);
        }

        $payload = $request->validate([
            'message_id' => ['required', 'string', 'max:255'],
            'chat_id' => ['required', 'string', 'max:255'],
            'from' => ['required', 'string', 'max:255'],
            'body' => ['present', 'string', 'max:5000'],
            'is_group' => ['required', 'boolean'],
            'has_quoted_message' => ['required', 'boolean'],
            'quoted_message_id' => [
                'nullable',
                'required_if:has_quoted_message,true',
                'string',
                'max:255',
            ],
            'timestamp' => ['nullable', 'integer'],
        ]);

        if ($payload['is_group'] || ! $payload['has_quoted_message']) {
            return $this->instructionResponse();
        }

        $outbound = WhatsAppOutboundMessage::with(['kegiatan', 'user'])
            ->where('message_id', $payload['quoted_message_id'])
            ->first();

        if (
            ! $outbound
            || ! $outbound->kegiatan
            || ! $outbound->user
            || ! $outbound->user->isPeserta()
            || ! hash_equals($outbound->chat_id, $payload['chat_id'])
            || ! hash_equals($outbound->chat_id, $payload['from'])
        ) {
            return $this->instructionResponse();
        }

        $parsed = $parser->parse($payload['body']);

        if ($parsed['result'] === WhatsAppKegiatanResponseParser::MISSING_REASON) {
            return response()->json([
                'processed' => false,
                'reply' => "Alasan wajib diisi. Balas kembali pesan kegiatan dengan format:\ntidak bersedia <alasan>",
            ]);
        }

        if ($parsed['result'] === WhatsAppKegiatanResponseParser::INVALID) {
            return response()->json([
                'processed' => false,
                'reply' => "Format tanggapan tidak dikenali. Balas pesan kegiatan dengan:\n- bersedia\n- tidak bersedia <alasan>",
            ]);
        }

        $response = DB::transaction(function () use (
            $payload,
            $outbound,
            $parsed,
            $responseService
        ) {
            $inbound = WhatsAppInboundMessage::firstOrCreate(
                ['message_id' => $payload['message_id']],
                [
                    'whatsapp_outbound_message_id' => $outbound->id,
                    'processed' => false,
                ]
            );

            if ($inbound->whatsapp_outbound_message_id !== $outbound->id) {
                return [
                    'processed' => false,
                    'reply' => 'Pesan tidak dapat diproses. Gunakan fitur Reply/Balas pada pesan kegiatan yang ingin ditanggapi.',
                ];
            }

            if (! $inbound->wasRecentlyCreated && $inbound->reply) {
                return [
                    'processed' => $inbound->processed,
                    'reply' => $inbound->reply,
                ];
            }

            $result = $responseService->submit(
                $outbound->kegiatan,
                $outbound->user,
                $parsed['status'],
                $parsed['alasan']
            );

            $reply = $this->successReply(
                $outbound->kegiatan->judul,
                $parsed['status'],
                $parsed['alasan'],
                $result
            );

            $inbound->update([
                'processed' => true,
                'reply' => $reply,
            ]);

            return [
                'processed' => true,
                'reply' => $reply,
            ];
        });

        return response()->json($response);
    }

    private function hasValidSecret(Request $request): bool
    {
        $expected = (string) config('services.whatsapp.webhook_secret', '');
        $provided = (string) $request->header('X-WhatsApp-Webhook-Secret', '');

        return $expected !== '' && hash_equals($expected, $provided);
    }

    private function instructionResponse(): JsonResponse
    {
        return response()->json([
            'processed' => false,
            'reply' => 'Pesan tidak dapat diproses. Gunakan fitur Reply/Balas pada pesan kegiatan yang ingin ditanggapi.',
        ]);
    }

    private function successReply(
        string $judul,
        string $status,
        ?string $alasan,
        string $result
    ): string {
        $action = $result === KegiatanResponseService::CREATED
            ? 'disimpan'
            : 'diperbarui';
        $statusText = $status === 'bersedia' ? 'Bersedia' : 'Tidak bersedia';
        $reply = "Tanggapan untuk kegiatan \"{$judul}\" berhasil {$action}: {$statusText}.";

        if ($status === 'tidak_bersedia') {
            $reply .= "\nAlasan: {$alasan}";
        }

        return $reply;
    }
}
