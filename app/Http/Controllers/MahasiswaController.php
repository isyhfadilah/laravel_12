<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa = Mahasiswa::all();
        return view('mahasiswa.index', compact('mahasiswa'));
    }

    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'nama' => 'required',
    //         'nim' => 'required',
    //         'email' => 'required'
    //     ]);
        
    //     return back()->with('success', 'Data diterima!');
    // }
}
