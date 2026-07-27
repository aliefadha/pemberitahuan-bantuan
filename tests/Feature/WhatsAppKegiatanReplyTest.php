<?php

use App\Models\Kegiatan;
use App\Models\User;
use App\Models\WhatsAppOutboundMessage;
use App\Notifications\KegiatanResponseNotification;
use Illuminate\Support\Facades\Notification;

beforeEach(function () {
    config()->set('services.whatsapp.webhook_secret', 'test-webhook-secret');

    Notification::fake();

    $this->participant = User::factory()->create([
        'role' => 'peserta',
        'no_telepon' => '081234567890',
        'jorong' => 'pulutan',
    ]);

    $this->admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->kegiatan = Kegiatan::create([
        'judul' => 'Gotong Royong',
        'deskripsi' => 'Membersihkan lingkungan',
        'tanggal' => now()->addDay(),
        'jorong' => 'pulutan',
    ]);

    $this->outbound = WhatsAppOutboundMessage::create([
        'message_id' => 'outbound-message-1',
        'kegiatan_id' => $this->kegiatan->id,
        'user_id' => $this->participant->id,
        'chat_id' => '6281234567890@c.us',
        'sent_at' => now(),
    ]);

    $this->payload = [
        'message_id' => 'incoming-message-1',
        'chat_id' => '6281234567890@c.us',
        'from' => '6281234567890@c.us',
        'body' => 'bersedia',
        'is_group' => false,
        'has_quoted_message' => true,
        'quoted_message_id' => 'outbound-message-1',
        'timestamp' => now()->timestamp,
    ];
});

it('requires the configured webhook secret', function () {
    $this->postJson(route('webhooks.whatsapp.messages'), $this->payload)
        ->assertUnauthorized();
});

it('stores a quoted bersedia response', function () {
    $response = $this->postJson(
        route('webhooks.whatsapp.messages'),
        $this->payload,
        ['X-WhatsApp-Webhook-Secret' => 'test-webhook-secret']
    );

    $response
        ->assertOk()
        ->assertJson([
            'processed' => true,
            'reply' => 'Tanggapan untuk kegiatan "Gotong Royong" berhasil disimpan: Bersedia.',
        ]);

    $this->assertDatabaseHas('kegiatan_user', [
        'kegiatan_id' => $this->kegiatan->id,
        'user_id' => $this->participant->id,
        'status' => 'bersedia',
        'alasan' => null,
    ]);

    Notification::assertSentTo(
        $this->admin,
        KegiatanResponseNotification::class
    );
});

it('requires a reason without changing an existing response', function () {
    $this->kegiatan->users()->attach($this->participant->id, [
        'status' => 'bersedia',
        'alasan' => null,
    ]);

    $payload = array_replace($this->payload, [
        'body' => 'tidak bersedia',
    ]);

    $this->postJson(
        route('webhooks.whatsapp.messages'),
        $payload,
        ['X-WhatsApp-Webhook-Secret' => 'test-webhook-secret']
    )
        ->assertOk()
        ->assertJson([
            'processed' => false,
        ])
        ->assertJsonPath('reply', "Alasan wajib diisi. Balas kembali pesan kegiatan dengan format:\ntidak bersedia <alasan>");

    $this->assertDatabaseHas('kegiatan_user', [
        'kegiatan_id' => $this->kegiatan->id,
        'user_id' => $this->participant->id,
        'status' => 'bersedia',
        'alasan' => null,
    ]);

    Notification::assertNothingSent();
});

it('updates an existing response from a later valid reply', function () {
    $this->kegiatan->users()->attach($this->participant->id, [
        'status' => 'bersedia',
        'alasan' => null,
    ]);

    $payload = array_replace($this->payload, [
        'body' => 'tidak bersedia karena sakit',
    ]);

    $this->postJson(
        route('webhooks.whatsapp.messages'),
        $payload,
        ['X-WhatsApp-Webhook-Secret' => 'test-webhook-secret']
    )
        ->assertOk()
        ->assertJson([
            'processed' => true,
            'reply' => "Tanggapan untuk kegiatan \"Gotong Royong\" berhasil diperbarui: Tidak bersedia.\nAlasan: karena sakit",
        ]);

    $this->assertDatabaseHas('kegiatan_user', [
        'kegiatan_id' => $this->kegiatan->id,
        'user_id' => $this->participant->id,
        'status' => 'tidak_bersedia',
        'alasan' => 'karena sakit',
    ]);
});

it('shares the same response record with the website flow', function () {
    $this->kegiatan->users()->attach($this->participant->id, [
        'status' => 'tidak_bersedia',
        'alasan' => 'karena sakit',
    ]);

    $this->actingAs($this->participant)
        ->post(route('kegiatan.respond', $this->kegiatan), [
            'status' => 'bersedia',
        ])
        ->assertRedirect(route('kegiatan.show', $this->kegiatan))
        ->assertSessionHas('success', 'Tanggapan Anda telah diperbarui.');

    $this->assertDatabaseHas('kegiatan_user', [
        'kegiatan_id' => $this->kegiatan->id,
        'user_id' => $this->participant->id,
        'status' => 'bersedia',
        'alasan' => null,
    ]);
});

it('rejects a response that does not quote a broadcast', function () {
    $payload = array_replace($this->payload, [
        'has_quoted_message' => false,
        'quoted_message_id' => null,
    ]);

    $this->postJson(
        route('webhooks.whatsapp.messages'),
        $payload,
        ['X-WhatsApp-Webhook-Secret' => 'test-webhook-secret']
    )
        ->assertOk()
        ->assertJson([
            'processed' => false,
            'reply' => 'Pesan tidak dapat diproses. Gunakan fitur Reply/Balas pada pesan kegiatan yang ingin ditanggapi.',
        ]);

    $this->assertDatabaseCount('kegiatan_user', 0);
});

it('rejects a reply whose sender does not match the broadcast recipient', function () {
    $payload = array_replace($this->payload, [
        'chat_id' => '628999999999@c.us',
        'from' => '628999999999@c.us',
    ]);

    $this->postJson(
        route('webhooks.whatsapp.messages'),
        $payload,
        ['X-WhatsApp-Webhook-Secret' => 'test-webhook-secret']
    )
        ->assertOk()
        ->assertJson(['processed' => false]);

    $this->assertDatabaseCount('kegiatan_user', 0);
});

it('processes a repeated incoming event only once', function () {
    $headers = ['X-WhatsApp-Webhook-Secret' => 'test-webhook-secret'];

    $first = $this->postJson(
        route('webhooks.whatsapp.messages'),
        $this->payload,
        $headers
    );
    $second = $this->postJson(
        route('webhooks.whatsapp.messages'),
        $this->payload,
        $headers
    );

    $first->assertOk();
    $second
        ->assertOk()
        ->assertExactJson($first->json());

    $this->assertDatabaseCount('whatsapp_inbound_messages', 1);
    $this->assertDatabaseCount('kegiatan_user', 1);

    Notification::assertSentToTimes(
        $this->admin,
        KegiatanResponseNotification::class,
        1
    );
});
