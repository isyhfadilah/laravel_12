<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Prestasi extends Model
{
    protected $table = 'prestasis';
    protected $fillable = [
        'nama_prestasi',
        'tingkat',
        'tahun',
        'deskripsi',
        'foto',
    ];

    public function mahasiswa(): BelongsToMany
    {
        return $this->belongsToMany(Mahasiswa::class, 'mahasiswa_prestasi')
            ->withTimestamps();
    }
}
