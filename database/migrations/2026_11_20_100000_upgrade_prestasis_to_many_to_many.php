<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa_prestasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnDelete();
            $table->foreignId('prestasi_id')->constrained('prestasis')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['mahasiswa_id', 'prestasi_id']);
        });

        if (Schema::hasColumn('prestasis', 'mahasiswa_id')) {
            $oldRelations = DB::table('prestasis')
                ->whereNotNull('mahasiswa_id')
                ->get(['id', 'mahasiswa_id']);

            foreach ($oldRelations as $relation) {
                DB::table('mahasiswa_prestasi')->insert([
                    'mahasiswa_id' => $relation->mahasiswa_id,
                    'prestasi_id' => $relation->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            Schema::table('prestasis', function (Blueprint $table) {
                $table->dropForeign(['mahasiswa_id']);
                $table->dropColumn('mahasiswa_id');
            });
        }

        Schema::table('prestasis', function (Blueprint $table) {
            $table->renameColumn('judul', 'nama_prestasi');
        });

        Schema::table('prestasis', function (Blueprint $table) {
            $table->string('tingkat')->default('Kampus')->after('nama_prestasi');
            $table->integer('tahun')->default((int) date('Y'))->after('tingkat');
            $table->text('deskripsi')->nullable()->after('tahun');
        });
    }

    public function down(): void
    {
        Schema::table('prestasis', function (Blueprint $table) {
            $table->dropColumn(['deskripsi', 'tahun', 'tingkat']);
            $table->renameColumn('nama_prestasi', 'judul');
            $table->foreignId('mahasiswa_id')->nullable()->after('id');
        });

        Schema::dropIfExists('mahasiswa_prestasi');
    }
};
