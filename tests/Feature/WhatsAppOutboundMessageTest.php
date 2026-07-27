<?php

use App\Models\Kegiatan;
use App\Models\User;
use App\Notifications\KegiatanNotification;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

it('records the WhatsApp message ID for a kegiatan broadcast', function () {
    config()->set('services.whatsapp.url', 'http://whatsapp.test');

    Http::fake([
        'http://whatsapp.test/send' => Http::response([
            'success' => true,
            'message_id' => 'sent-message-id',
            'chat_id' => '6281234567890@c.us',
        ]),
    ]);

    $participant = User::factory()->create([
        'role' => 'peserta',
        'no_telepon' => '081234567890',
        'jorong' => 'pulutan',
    ]);

    $kegiatan = Kegiatan::create([
        'judul' => 'Gotong Royong',
        'deskripsi' => 'Membersihkan lingkungan',
        'tanggal' => now()->addDay(),
        'jorong' => 'pulutan',
    ]);

    $participant->notify(new KegiatanNotification($kegiatan, 'created'));

    Http::assertSent(function (Request $request) {
        return $request->url() === 'http://whatsapp.test/send'
            && str_contains($request['message'], 'Balas langsung pesan ini')
            && str_contains($request['message'], 'tidak bersedia <alasan>');
    });

    $this->assertDatabaseHas('whatsapp_outbound_messages', [
        'message_id' => 'sent-message-id',
        'kegiatan_id' => $kegiatan->id,
        'user_id' => $participant->id,
        'chat_id' => '6281234567890@c.us',
    ]);
});
