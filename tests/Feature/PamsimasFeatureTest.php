<?php

use App\Models\User;
use App\Models\Warga;
use App\Models\Pencatatan;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('pengelola can view and create warga', function () {
    $user = User::factory()->create(['role' => 'pengelola']);

    $response = $this->actingAs($user)->get('/wargas');
    $response->assertStatus(200);

    $response = $this->actingAs($user)->post('/wargas', [
        'nama' => 'Budi Santoso',
        'rt' => 1,
        'rw' => 2,
        'nomor_meteran' => 'MTR-000001',
    ]);

    $response->assertRedirect('/wargas');
    $this->assertDatabaseHas('wargas', [
        'nama' => 'Budi Santoso',
        'rt' => 1,
        'rw' => 2,
        'nomor_meteran' => 'MTR-000001',
    ]);
});

test('petugas cannot view or create warga', function () {
    $user = User::factory()->create(['role' => 'petugas']);

    $response = $this->actingAs($user)->get('/wargas');
    $response->assertStatus(403);

    $response = $this->actingAs($user)->post('/wargas', [
        'nama' => 'Budi Santoso',
        'rt' => 1,
        'rw' => 2,
        'nomor_meteran' => 'MTR-000001',
    ]);
    $response->assertStatus(403);
});

test('warga nomor_meteran must be unique', function () {
    $user = User::factory()->create(['role' => 'pengelola']);
    Warga::create([
        'nama' => 'Warga Pertama',
        'rt' => 1,
        'rw' => 2,
        'nomor_meteran' => 'MTR-UNIQUE',
    ]);

    $response = $this->actingAs($user)->post('/wargas', [
        'nama' => 'Warga Kedua',
        'rt' => 1,
        'rw' => 2,
        'nomor_meteran' => 'MTR-UNIQUE',
    ]);

    $response->assertSessionHasErrors('nomor_meteran');
});

test('user with any role can record meter reading and calculates usage correctly', function () {
    $user = User::factory()->create(['role' => 'petugas']);
    $warga = Warga::create([
        'nama' => 'Budi Santoso',
        'rt' => 1,
        'rw' => 2,
        'nomor_meteran' => 'MTR-000001',
    ]);

    // Record month 1: 100
    $response = $this->actingAs($user)->post('/pencatatans', [
        'warga_id' => $warga->id,
        'bulan' => '2026-06',
        'angka_meteran' => 100,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('pencatatans', [
        'warga_id' => $warga->id,
        'bulan' => '2026-06',
        'angka_meteran' => 100,
        'pemakaian' => 100, // 100 - 0 = 100
    ]);
});

test('user cannot do duplicate recording for same month', function () {
    $user = User::factory()->create(['role' => 'petugas']);
    $warga = Warga::create([
        'nama' => 'Budi Santoso',
        'rt' => 1,
        'rw' => 2,
        'nomor_meteran' => 'MTR-000001',
    ]);

    // Record month 1 first time
    $this->actingAs($user)->post('/pencatatans', [
        'warga_id' => $warga->id,
        'bulan' => '2026-07',
        'angka_meteran' => 100,
    ]);

    // Record month 1 second time
    $response = $this->actingAs($user)->post('/pencatatans', [
        'warga_id' => $warga->id,
        'bulan' => '2026-07',
        'angka_meteran' => 150,
    ]);

    $response->assertSessionHasErrors('pencatatan');
});

test('user cannot record smaller meter reading than previous month', function () {
    $user = User::factory()->create(['role' => 'petugas']);
    $warga = Warga::create([
        'nama' => 'Budi',
        'rt' => 1,
        'rw' => 2,
        'nomor_meteran' => 'MTR-000001',
    ]);

    // Month 1: 100
    $this->actingAs($user)->post('/pencatatans', [
        'warga_id' => $warga->id,
        'bulan' => '2026-06',
        'angka_meteran' => 100,
    ]);

    // Month 2: 90 (invalid)
    $response = $this->actingAs($user)->post('/pencatatans', [
        'warga_id' => $warga->id,
        'bulan' => '2026-07',
        'angka_meteran' => 90,
    ]);

    $response->assertSessionHasErrors('angka_meteran');
});

test('pengelola can view rekapitulasi page but petugas cannot', function () {
    $pengelola = User::factory()->create(['role' => 'pengelola']);
    $petugas = User::factory()->create(['role' => 'petugas']);

    $response = $this->actingAs($pengelola)->get('/rekap');
    $response->assertStatus(200);

    $response = $this->actingAs($petugas)->get('/rekap');
    $response->assertStatus(403);
});

test('pengelola can manage petugas CRUD', function () {
    $pengelola = User::factory()->create(['role' => 'pengelola']);

    // List petugas
    $response = $this->actingAs($pengelola)->get('/petugases');
    $response->assertStatus(200);

    // Create petugas
    $response = $this->actingAs($pengelola)->post('/petugases', [
        'nama' => 'Petugas Baru',
        'username' => 'petugasbaru',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);
    $response->assertRedirect('/petugases');
    $this->assertDatabaseHas('users', [
        'nama' => 'Petugas Baru',
        'username' => 'petugasbaru',
        'role' => 'petugas',
    ]);
});

test('petugas cannot manage petugas CRUD', function () {
    $petugas = User::factory()->create(['role' => 'petugas']);

    $response = $this->actingAs($petugas)->get('/petugases');
    $response->assertStatus(403);

    $response = $this->actingAs($petugas)->post('/petugases', [
        'nama' => 'Petugas Baru 2',
        'username' => 'petugasbaru2',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);
    $response->assertStatus(403);
});
