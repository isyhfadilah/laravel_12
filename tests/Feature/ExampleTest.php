<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_mahasiswa_can_be_searched_by_name_nim_or_email(): void
    {
        $aisyah = Mahasiswa::create([
            'nama' => 'Aisyah Putri',
            'nim' => '20240001',
            'email' => 'aisyah@kampus.ac.id',
        ]);
        $budi = Mahasiswa::create([
            'nama' => 'Budi Santoso',
            'nim' => '20240002',
            'email' => 'budi@kampus.ac.id',
        ]);

        $this->get('/mahasiswa?search=20240001')
            ->assertOk()
            ->assertViewHas('mahasiswa', fn ($mahasiswa) => $mahasiswa->modelKeys() === [$aisyah->id]);

        $this->get('/mahasiswa?search=budi@kampus.ac.id')
            ->assertOk()
            ->assertViewHas('mahasiswa', fn ($mahasiswa) => $mahasiswa->modelKeys() === [$budi->id]);
    }
}
