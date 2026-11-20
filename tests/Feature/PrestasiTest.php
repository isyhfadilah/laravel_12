<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use App\Models\Prestasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrestasiTest extends TestCase
{
    use RefreshDatabase;

    public function test_pages_render_when_no_prestasi_exist(): void
    {
        $student = Mahasiswa::create([
            'nama' => 'Aisyah',
            'nim' => '100',
            'email' => 'aisyah@test.id',
        ]);

        $this->actingAs($student)
            ->get('/mahasiswa')
            ->assertOk()
            ->assertSee('Belum ada');

        $this->get('/prestasi')
            ->assertOk()
            ->assertSee('Belum ada data prestasi');
    }

    public function test_mahasiswa_and_prestasi_lists_show_at_most_five_records_per_page(): void
    {
        $students = [];

        for ($index = 1; $index <= 6; $index++) {
            $student = Mahasiswa::create([
                'nama' => "Mahasiswa {$index}",
                'nim' => "20{$index}",
                'email' => "mahasiswa{$index}@test.id",
            ]);
            $students[] = $student;

            $prestasi = Prestasi::create([
                'nama_prestasi' => "Prestasi {$index}",
                'tingkat' => 'Kampus',
                'tahun' => 2026,
            ]);
            $prestasi->mahasiswa()->attach($student->id);
        }

        $this->actingAs($students[0])
            ->get('/mahasiswa')
            ->assertOk()
            ->assertViewHas('mahasiswa', fn ($paginator) => $paginator->perPage() === 5
                && $paginator->count() === 5
                && $paginator->total() === 6);

        $this->get('/mahasiswa?page=2')
            ->assertOk()
            ->assertViewHas('mahasiswa', fn ($paginator) => $paginator->count() === 1);

        $this->get('/prestasi')
            ->assertOk()
            ->assertViewHas('prestasi', fn ($paginator) => $paginator->perPage() === 5
                && $paginator->count() === 5
                && $paginator->total() === 6);

        $this->get('/prestasi?page=2')
            ->assertOk()
            ->assertViewHas('prestasi', fn ($paginator) => $paginator->count() === 1);
    }

    public function test_prestasi_can_be_created_for_multiple_mahasiswa(): void
    {
        $studentA = Mahasiswa::create(['nama' => 'Andi', 'nim' => '101', 'email' => 'andi@test.id']);
        $studentB = Mahasiswa::create(['nama' => 'Budi', 'nim' => '102', 'email' => 'budi@test.id']);

        $this->actingAs($studentA)
            ->post('/prestasi', [
                'nama_prestasi' => 'Juara 1 Hackathon 2026',
                'tingkat' => 'Nasional',
                'tahun' => 2026,
                'deskripsi' => 'Juara kategori pengembangan aplikasi.',
                'mahasiswa_ids' => [$studentA->id, $studentB->id],
            ])
            ->assertRedirect('/prestasi');

        $prestasi = Prestasi::where('nama_prestasi', 'Juara 1 Hackathon 2026')->firstOrFail();
        $this->assertEqualsCanonicalizing(
            [$studentA->id, $studentB->id],
            $prestasi->mahasiswa()->pluck('mahasiswas.id')->all()
        );
    }

    public function test_mahasiswa_can_be_filtered_by_prestasi_name(): void
    {
        $studentA = Mahasiswa::create(['nama' => 'Andi', 'nim' => '101', 'email' => 'andi@test.id']);
        $studentB = Mahasiswa::create(['nama' => 'Budi', 'nim' => '102', 'email' => 'budi@test.id']);
        $prestasi = Prestasi::create([
            'nama_prestasi' => 'Juara 1 Hackathon 2026',
            'tingkat' => 'Nasional',
            'tahun' => 2026,
            'deskripsi' => null,
        ]);
        $prestasi->mahasiswa()->attach([$studentA->id, $studentB->id]);

        $this->actingAs($studentA)
            ->get('/mahasiswa?prestasi=Juara+1+Hackathon')
            ->assertOk()
            ->assertSee('Andi')
            ->assertSee('Budi');
    }

    public function test_prestasi_can_be_updated_and_deleted(): void
    {
        $student = Mahasiswa::create(['nama' => 'Andi', 'nim' => '101', 'email' => 'andi@test.id']);
        $prestasi = Prestasi::create([
            'nama_prestasi' => 'Lama',
            'tingkat' => 'Kampus',
            'tahun' => 2025,
        ]);
        $prestasi->mahasiswa()->attach($student->id);

        $this->actingAs($student)
            ->put("/prestasi/{$prestasi->id}", [
                'nama_prestasi' => 'Baru',
                'tingkat' => 'Provinsi',
                'tahun' => 2026,
                'deskripsi' => 'Deskripsi baru',
                'mahasiswa_ids' => [$student->id],
            ])
            ->assertRedirect('/prestasi');

        $this->assertDatabaseHas('prestasis', [
            'id' => $prestasi->id,
            'nama_prestasi' => 'Baru',
            'tingkat' => 'Provinsi',
        ]);

        $this->actingAs($student)
            ->delete("/prestasi/{$prestasi->id}")
            ->assertRedirect('/prestasi');
        $this->assertDatabaseMissing('prestasis', ['id' => $prestasi->id]);
    }
}
