<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;

class DemoMahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswa = [
            ['nama' => 'Nadira Putri', 'nim' => '202610001', 'email' => 'nadira.putri@mahasiswa.example.test'],
            ['nama' => 'Rafi Maulana', 'nim' => '202610002', 'email' => 'rafi.maulana@mahasiswa.example.test'],
            ['nama' => 'Intan Maharani', 'nim' => '202610003', 'email' => 'intan.maharani@mahasiswa.example.test'],
            ['nama' => 'Fajar Pratama', 'nim' => '202610004', 'email' => 'fajar.pratama@mahasiswa.example.test'],
            ['nama' => 'Zahra Nabila', 'nim' => '202610005', 'email' => 'zahra.nabila@mahasiswa.example.test'],
            ['nama' => 'Bima Aditya', 'nim' => '202610006', 'email' => 'bima.aditya@mahasiswa.example.test'],
            ['nama' => 'Salsabila Rahman', 'nim' => '202610007', 'email' => 'salsabila.rahman@mahasiswa.example.test'],
            ['nama' => 'Dimas Arya', 'nim' => '202610008', 'email' => 'dimas.arya@mahasiswa.example.test'],
            ['nama' => 'Nadia Kurnia', 'nim' => '202610009', 'email' => 'nadia.kurnia@mahasiswa.example.test'],
            ['nama' => 'Rizky Ramadhan', 'nim' => '202610010', 'email' => 'rizky.ramadhan@mahasiswa.example.test'],
        ];

        foreach ($mahasiswa as $data) {
            Mahasiswa::firstOrCreate(
                ['nim' => $data['nim']],
                ['nama' => $data['nama'], 'email' => $data['email']]
            );
        }
    }
}
