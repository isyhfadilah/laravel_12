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

        $response->assertOk()
            ->assertSee('Data mahasiswa, terkelola dalam satu tempat.')
            ->assertSee('Buka data mahasiswa')
            ->assertSee('href="/mahasiswa"', false);
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

    public function test_edit_page_redirects_to_the_mahasiswa_page_with_the_edit_modal_open(): void
    {
        $mahasiswa = Mahasiswa::create([
            'nama' => 'Aisyah Putri',
            'nim' => '20240001',
            'email' => 'aisyah@kampus.ac.id',
        ]);

        $this->get("/mahasiswa/{$mahasiswa->id}/edit")
            ->assertRedirect("/mahasiswa?edit={$mahasiswa->id}");

        $this->get("/mahasiswa?edit={$mahasiswa->id}")
            ->assertOk()
            ->assertSee('data-open-on-load="true"', false)
            ->assertSee('value="Aisyah Putri"', false);
    }

    public function test_mahasiswa_can_be_updated_from_the_edit_modal(): void
    {
        $mahasiswa = Mahasiswa::create([
            'nama' => 'Aisyah Putri',
            'nim' => '20240001',
            'email' => 'aisyah@kampus.ac.id',
        ]);

        $this->put("/mahasiswa/{$mahasiswa->id}", [
            'edit_mahasiswa_id' => $mahasiswa->id,
            'nama' => 'Aisyah Fadilah',
            'nim' => '20240001',
            'email' => 'aisyah.fadilah@kampus.ac.id',
        ])->assertRedirect('/mahasiswa');

        $this->assertDatabaseHas('mahasiswas', [
            'id' => $mahasiswa->id,
            'nama' => 'Aisyah Fadilah',
            'email' => 'aisyah.fadilah@kampus.ac.id',
        ]);
    }

    public function test_edit_modal_reopens_with_old_input_when_update_validation_fails(): void
    {
        $mahasiswa = Mahasiswa::create([
            'nama' => 'Aisyah Putri',
            'nim' => '20240001',
            'email' => 'aisyah@kampus.ac.id',
        ]);

        $this->from('/mahasiswa')->put("/mahasiswa/{$mahasiswa->id}", [
            'edit_mahasiswa_id' => $mahasiswa->id,
            'nama' => 'Aisyah Fadilah',
            'nim' => '20240001',
            'email' => 'invalid-email',
        ])->assertRedirect('/mahasiswa')
            ->assertSessionHasErrors('email');

        $this->get('/mahasiswa')
            ->assertOk()
            ->assertSee('data-open-on-load="true"', false)
            ->assertSee('value="Aisyah Fadilah"', false)
            ->assertViewHas('editMahasiswa', fn ($editMahasiswa) => $editMahasiswa->is($mahasiswa));
    }
}
