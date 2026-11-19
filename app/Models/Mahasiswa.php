<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Authenticatable
{
    protected $table = 'mahasiswas';

    protected $fillable = ['nama', 'nim', 'email', 'password'];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function prestasi(): HasMany
    {
        return $this->hasMany(Prestasi::class);
    }
}