<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index()
    {
        return view('mahasiswa.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'nim' => 'required',
            'email' => 'required'
        ]);
        
        return back()->with('success', 'Data diterima!');
    }
}
