<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Prestasi;
use Illuminate\Database\Seeder;

class DemoPrestasiSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(DemoMahasiswaSeeder::class);

        $mahasiswa = Mahasiswa::whereIn('nim', [
            '202610001', '202610002', '202610003', '202610004', '202610005',
            '202610006', '202610007', '202610008', '202610009', '202610010',
        ])->pluck('id', 'nim');

        $prestasi = [
            [
                'nama_prestasi' => 'Juara 1 Gemastik - UX Design',
                'tingkat' => 'Nasional',
                'tahun' => 2026,
                'deskripsi' => 'Meraih juara pertama pada kategori UX Design melalui rancangan aplikasi layanan kampus.',
                'mahasiswa' => ['202610001', '202610002'],
            ],
            [
                'nama_prestasi' => 'Juara 2 Kompetisi Inovasi Digital Kampus',
                'tingkat' => 'Kampus',
                'tahun' => 2026,
                'deskripsi' => 'Mengembangkan prototipe sistem peminjaman ruang dan fasilitas kampus.',
                'mahasiswa' => ['202610003', '202610004'],
            ],
            [
                'nama_prestasi' => 'Juara 1 Lomba Karya Tulis Ilmiah Mahasiswa',
                'tingkat' => 'Provinsi',
                'tahun' => 2025,
                'deskripsi' => 'Karya penelitian tentang pemanfaatan teknologi untuk pengelolaan sampah perkotaan.',
                'mahasiswa' => ['202610005'],
            ],
            [
                'nama_prestasi' => 'Finalis Hackathon Nusantara',
                'tingkat' => 'Nasional',
                'tahun' => 2026,
                'deskripsi' => 'Terpilih sebagai finalis dengan aplikasi pencatatan keuangan untuk UMKM.',
                'mahasiswa' => ['202610006', '202610008'],
            ],
            [
                'nama_prestasi' => 'Medali Perak Olimpiade Sains Data',
                'tingkat' => 'Nasional',
                'tahun' => 2025,
                'deskripsi' => 'Meraih medali perak pada kompetisi analisis data tingkat mahasiswa.',
                'mahasiswa' => ['202610007'],
            ],
            [
                'nama_prestasi' => 'Juara 3 Business Plan Competition',
                'tingkat' => 'Kota',
                'tahun' => 2026,
                'deskripsi' => 'Menyusun rencana bisnis platform pemasaran produk lokal.',
                'mahasiswa' => ['202610009', '202610010'],
            ],
            [
                'nama_prestasi' => 'Best Presentation Student Research Forum',
                'tingkat' => 'Internasional',
                'tahun' => 2025,
                'deskripsi' => 'Penghargaan presentasi terbaik untuk penelitian bidang teknologi pendidikan.',
                'mahasiswa' => ['202610001', '202610005'],
            ],
            [
                'nama_prestasi' => 'Juara 1 Web Design Competition',
                'tingkat' => 'Provinsi',
                'tahun' => 2024,
                'deskripsi' => 'Meraih juara pertama melalui desain portal informasi pariwisata daerah.',
                'mahasiswa' => ['202610002', '202610008'],
            ],
            [
                'nama_prestasi' => 'Pendanaan Program Kreativitas Mahasiswa Kewirausahaan',
                'tingkat' => 'Nasional',
                'tahun' => 2026,
                'deskripsi' => 'Proposal usaha produk pangan lokal terpilih untuk memperoleh pendanaan program mahasiswa.',
                'mahasiswa' => ['202610003', '202610009'],
            ],
            [
                'nama_prestasi' => 'Juara 2 Kejuaraan Debat Bahasa Indonesia',
                'tingkat' => 'Kota',
                'tahun' => 2025,
                'deskripsi' => 'Meraih juara kedua dalam kompetisi debat antarperguruan tinggi tingkat kota.',
                'mahasiswa' => ['202610004', '202610007', '202610010'],
            ],
        ];

        foreach ($prestasi as $data) {
            $item = Prestasi::firstOrCreate(
                ['nama_prestasi' => $data['nama_prestasi']],
                [
                    'tingkat' => $data['tingkat'],
                    'tahun' => $data['tahun'],
                    'deskripsi' => $data['deskripsi'],
                ]
            );

            $item->mahasiswa()->syncWithoutDetaching(
                $mahasiswa->only($data['mahasiswa'])->values()->all()
            );
        }
    }
}
