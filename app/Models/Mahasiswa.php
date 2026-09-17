<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $fillable = [
        'nama',
        'nim',
        'email',
    ];
    
    // Mahasiswa::create(
    //     $request->only(['nama', 'nim', 'email'])
    // );

    // $mahasiswa = Mahasiswa::all();
    // return view('mahasiswa.index', compact('mahasiswa'));
}
