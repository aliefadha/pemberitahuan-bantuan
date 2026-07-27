<?php

use App\Models\Kegiatan;
use App\Models\Kelompok;
use App\Models\User;

it('limits a kader to users and activities in their kelompok', function () {
    $kelompokA = Kelompok::create(['name' => 'Kelompok A', 'jorong' => 'pulutan']);
    $kelompokB = Kelompok::create(['name' => 'Kelompok B', 'jorong' => 'pulutan']);
    $kader = User::factory()->create([
        'role' => 'kader',
        'jorong' => 'pulutan',
        'kelompok_id' => $kelompokA->id,
    ]);
    $memberA = User::factory()->create(['role' => 'peserta', 'jorong' => 'pulutan', 'kelompok_id' => $kelompokA->id]);
    $memberB = User::factory()->create(['role' => 'peserta', 'jorong' => 'pulutan', 'kelompok_id' => $kelompokB->id]);
    $activityA = Kegiatan::create(['judul' => 'Kegiatan Kelompok Alpha', 'tanggal' => now(), 'jorong' => 'pulutan', 'kelompok_id' => $kelompokA->id]);
    $activityB = Kegiatan::create(['judul' => 'Kegiatan Kelompok Beta', 'tanggal' => now(), 'jorong' => 'pulutan', 'kelompok_id' => $kelompokB->id]);

    $this->actingAs($kader)
        ->get(route('admin.users.index'))
        ->assertOk()
        ->assertSee($memberA->name)
        ->assertDontSee($memberB->name);

    $this->actingAs($kader)
        ->get(route('admin.kegiatans.index'))
        ->assertOk()
        ->assertSee($activityA->judul)
        ->assertDontSee($activityB->judul);

    $this->actingAs($kader)
        ->get(route('admin.kegiatans.show', $activityB))
        ->assertForbidden();
});
