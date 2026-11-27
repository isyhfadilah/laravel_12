<?php

namespace App\Models;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel; 

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Filament\Models\Contracts\HasName;

class Mahasiswa extends Authenticatable implements FilamentUser, HasName
{
    protected $table = 'mahasiswas';

    protected $fillable = ['nama', 'nim', 'email', 'password', 'bio', 'foto'];

    protected $hidden = ['password'];
    
    protected $casts = [
        'is_admin' => 'boolean',
    ];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'admin'
            && (bool) $this->is_admin;
    }

    public function getFilamentName(): string
    {
        return (string) $this->nama;
    }

    public function prestasi(): BelongsToMany
    {
        return $this->belongsToMany(Prestasi::class, 'mahasiswa_prestasi')
            ->withTimestamps();
    }
}
