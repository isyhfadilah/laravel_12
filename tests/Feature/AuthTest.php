<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_open_auth_pages_but_cannot_manage_mahasiswa(): void
    {
        $this->get('/login')->assertOk()->assertSee('Kata sandi');
        $this->get('/register')->assertOk()->assertSee('Konfirmasi kata sandi');
        $this->get('/mahasiswa')->assertRedirect('/login');
        $this->post('/mahasiswa', [])->assertRedirect('/login');
    }

    public function test_registration_hashes_password_and_authenticates_student(): void
    {
        $this->post('/register', [
            'nama' => 'Aisyah', 'nim' => '12345', 'email' => 'aisyah@example.test',
            'password' => 'password123', 'password_confirmation' => 'password123',
        ])->assertRedirect('/mahasiswa');

        $student = Mahasiswa::where('nim', '12345')->firstOrFail();
        $this->assertTrue(Hash::check('password123', $student->password));
        $this->assertAuthenticatedAs($student);
        $this->get('/login')->assertRedirect('/mahasiswa');
    }

    public function test_registration_rejects_duplicate_nim_and_mismatched_password(): void
    {
        Mahasiswa::create(['nama' => 'Aisyah', 'nim' => '12345', 'email' => 'a@example.test']);
        $this->post('/register', [
            'nama' => 'Budi', 'nim' => '12345', 'email' => 'b@example.test',
            'password' => 'password123', 'password_confirmation' => 'different',
        ])->assertSessionHasErrors(['nim', 'password']);
        $this->assertGuest();
        $this->assertDatabaseCount('mahasiswas', 1);
    }

    public function test_login_rejects_invalid_password_and_accounts_without_password(): void
    {
        $student = Mahasiswa::create([
            'nama' => 'Aisyah', 'nim' => '12345', 'email' => 'a@example.test',
        ]);
        $this->post('/login', ['nim' => '12345', 'password' => 'wrong'])
            ->assertSessionHasErrors('nim');
        $student->update(['password' => 'password123']);
        $this->post('/login', ['nim' => '12345', 'password' => 'wrong'])
            ->assertSessionHasErrors('nim');
        $this->assertGuest();
    }

    public function test_student_can_login_and_logout(): void
    {
        $student = Mahasiswa::create([
            'nama' => 'Aisyah', 'nim' => '12345', 'email' => 'a@example.test',
            'password' => 'password123',
        ]);
        $this->post('/login', ['nim' => '12345', 'password' => 'password123'])
            ->assertRedirect('/mahasiswa');
        $this->assertAuthenticatedAs($student);
        $this->get('/mahasiswa')->assertOk()->assertSee('Keluar');
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
        $this->get('/mahasiswa')->assertRedirect('/login');
    }
}