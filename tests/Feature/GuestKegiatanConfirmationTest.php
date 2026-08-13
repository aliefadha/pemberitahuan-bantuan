<?php

use App\Models\Kegiatan;
use App\Models\Kelompok;
use App\Models\User;
use App\Notifications\KegiatanNotification;
use Illuminate\Support\Facades\URL;

function guestKegiatanUrl(string $route, Kegiatan $kegiatan, User $user, $expiration = null): string
{
    return URL::temporarySignedRoute(
        $route,
        $expiration ?? now()->addHour(),
        ['kegiatan' => $kegiatan, 'user' => $user]
    );
}

it('lets an eligible recipient view an activity without logging in', function () {
    $kelompok = Kelompok::create(['name' => 'Kelompok Melati', 'jorong' => 'pulutan']);
    $recipient = User::factory()->create([
        'role' => 'peserta',
        'jorong' => 'pulutan',
        'kelompok_id' => $kelompok->id,
    ]);
    $otherParticipant = User::factory()->create([
        'role' => 'peserta',
        'jorong' => 'pulutan',
        'kelompok_id' => $kelompok->id,
    ]);
    $kegiatan = Kegiatan::create([
        'judul' => 'Gotong Royong',
        'deskripsi' => 'Membersihkan lingkungan bersama.',
        'tanggal' => now()->addDay(),
        'jorong' => 'pulutan',
        'kelompok_id' => $kelompok->id,
    ]);

    $this->get(guestKegiatanUrl('kegiatan.guest.show', $kegiatan, $recipient))
        ->assertOk()
        ->assertSee('Gotong Royong')
        ->assertSee($recipient->name)
        ->assertDontSee($otherParticipant->name);

    $this->assertGuest();
});

it('lets an eligible recipient submit and update a response without logging in', function () {
    $recipient = User::factory()->create([
        'role' => 'peserta',
        'jorong' => 'pulutan',
    ]);
    $admin = User::factory()->create(['role' => 'admin']);
    $kegiatan = Kegiatan::create([
        'judul' => 'Pertemuan Warga',
        'tanggal' => now()->addDay(),
        'jorong' => 'pulutan',
    ]);

    $response = $this->post(
        guestKegiatanUrl('kegiatan.guest.respond', $kegiatan, $recipient),
        ['status' => 'tidak_bersedia', 'alasan' => 'Sedang di luar kota']
    );

    $response->assertRedirect();
    $this->assertDatabaseHas('kegiatan_user', [
        'kegiatan_id' => $kegiatan->id,
        'user_id' => $recipient->id,
        'status' => 'tidak_bersedia',
        'alasan' => 'Sedang di luar kota',
    ]);
    expect($admin->notifications()->count())->toBe(1);
    $this->assertGuest();
});

it('rejects expired, modified, and ineligible confirmation links', function () {
    $recipient = User::factory()->create([
        'role' => 'peserta',
        'jorong' => 'pulutan',
    ]);
    $outsider = User::factory()->create([
        'role' => 'peserta',
        'jorong' => 'koto_tuo',
    ]);
    $kegiatan = Kegiatan::create([
        'judul' => 'Pertemuan Warga',
        'tanggal' => now()->addDay(),
        'jorong' => 'pulutan',
    ]);

    $expired = guestKegiatanUrl('kegiatan.guest.show', $kegiatan, $recipient, now()->subMinute());
    $modified = guestKegiatanUrl('kegiatan.guest.show', $kegiatan, $recipient).'&extra=changed';
    $ineligible = guestKegiatanUrl('kegiatan.guest.show', $kegiatan, $outsider);

    $this->get($expired)->assertForbidden();
    $this->get($modified)->assertForbidden();
    $this->get($ineligible)->assertForbidden();
});

it('puts a valid recipient-specific guest link in the WhatsApp message', function () {
    config(['services.whatsapp.activity_link_expiration' => 60]);

    $recipient = User::factory()->create([
        'role' => 'peserta',
        'jorong' => 'pulutan',
        'no_telepon' => '081234567890',
    ]);
    $kegiatan = Kegiatan::create([
        'judul' => 'Pertemuan Warga',
        'tanggal' => now()->addDay(),
        'jorong' => 'pulutan',
    ]);

    $message = (new KegiatanNotification($kegiatan, 'created'))->toWhatsApp($recipient)['message'];
    preg_match('/https?:\/\/\S+/', $message, $matches);

    expect($matches)->toHaveCount(1);
    expect(URL::hasValidSignature(request()->create($matches[0])))->toBeTrue();
    expect($matches[0])->toContain("/konfirmasi-kegiatan/{$kegiatan->id}/{$recipient->id}");
});
