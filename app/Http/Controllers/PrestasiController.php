<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PrestasiController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $search = trim($validated['search'] ?? '');

        $prestasi = Prestasi::with('mahasiswa')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('nama_prestasi', 'like', "%{$search}%")
                    ->orWhere('tingkat', 'like', "%{$search}%")
                    ->orWhere('tahun', 'like', "%{$search}%");
            })
            ->orderByDesc('tahun')
            ->orderBy('nama_prestasi')
            ->paginate(5)
            ->withQueryString();

        $mahasiswa = Mahasiswa::orderBy('nama')->get();
        $editId = old('edit_prestasi_id', $request->query('edit'));
        $editPrestasi = $editId ? Prestasi::with('mahasiswa')->find($editId) : null;

        return view('prestasi.index', compact('prestasi', 'mahasiswa', 'search', 'editPrestasi'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_prestasi' => ['required', 'string', 'max:150'],
            'tingkat' => ['required', Rule::in(['Kampus', 'Kota', 'Provinsi', 'Nasional', 'Internasional'])],
            'tahun' => ['required', 'integer', 'between:1900,' . ((int) date('Y') + 1)],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'mahasiswa_ids' => ['required', 'array', 'min:1'],
            'mahasiswa_ids.*' => ['integer', 'exists:mahasiswas,id'],
        ], [
            'mahasiswa_ids.required' => 'Pilih minimal satu mahasiswa.',
            'mahasiswa_ids.min' => 'Pilih minimal satu mahasiswa.',
        ]);

        $prestasi = Prestasi::create($data);
        $prestasi->mahasiswa()->sync($data['mahasiswa_ids']);

        return redirect()->route('prestasi.index')->with('success', 'Prestasi berhasil ditambahkan.');
    }

    public function edit(Prestasi $prestasi)
    {
        return redirect()->route('prestasi.index', ['edit' => $prestasi->id]);
    }

    public function update(Request $request, Prestasi $prestasi)
    {
        $data = $request->validate([
            'nama_prestasi' => ['required', 'string', 'max:150'],
            'tingkat' => ['required', Rule::in(['Kampus', 'Kota', 'Provinsi', 'Nasional', 'Internasional'])],
            'tahun' => ['required', 'integer', 'between:1900,' . ((int) date('Y') + 1)],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'mahasiswa_ids' => ['required', 'array', 'min:1'],
            'mahasiswa_ids.*' => ['integer', 'exists:mahasiswas,id'],
            'edit_prestasi_id' => ['nullable', 'integer'],
        ], [
            'mahasiswa_ids.required' => 'Pilih minimal satu mahasiswa.',
            'mahasiswa_ids.min' => 'Pilih minimal satu mahasiswa.',
        ]);

        $prestasi->update($data);
        $prestasi->mahasiswa()->sync($data['mahasiswa_ids']);

        return redirect()->route('prestasi.index')->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function destroy(Prestasi $prestasi)
    {
        $prestasi->delete();

        return redirect()->route('prestasi.index')->with('success', 'Prestasi berhasil dihapus.');
    }
}
