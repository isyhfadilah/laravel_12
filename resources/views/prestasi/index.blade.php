@extends('layouts.app', ['title' => 'Data Prestasi'])

@section('content')
    <x-page-heading
        title="Data Prestasi"
        description="Kelola prestasi dan mahasiswa yang meraih prestasi tersebut."
        class="max-w-2xl"
    />

    @if(session('success'))
        <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">
            {{ session('success') }}
        </div>
    @endif

    <section class="grid gap-7 lg:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)] lg:items-start">
        <x-panel title="Tambah prestasi" description="Lengkapi detail pencapaian dan mahasiswa penerima.">
            @unless($editPrestasi)
                <x-validation-errors />
            @endunless

            <form action="{{ route('prestasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <x-form-input id="nama_prestasi" name="nama_prestasi" label="Nama prestasi" :value="old('nama_prestasi')" placeholder="Contoh: Juara 1 Hackathon 2026" required />

                <div>
                    <label for="tingkat" class="mb-2 block">Tingkat</label>
                    <select id="tingkat" name="tingkat" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100">
                        @foreach(['Kampus', 'Kota', 'Provinsi', 'Nasional', 'Internasional'] as $tingkat)
                            <option value="{{ $tingkat }}" @selected(old('tingkat', 'Kampus') === $tingkat)>{{ $tingkat }}</option>
                        @endforeach
                    </select>
                </div>

                <x-form-input id="tahun" name="tahun" label="Tahun" type="number" :value="old('tahun', date('Y'))" min="1900" :max="date('Y') + 1" required />

                <div>
                    <label for="deskripsi" class="mb-2 block">Deskripsi <span class="font-normal text-slate-500">(opsional)</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" class="summernote w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100">{{ old('deskripsi') }}</textarea>
                </div>

                <div>
                    <label for="foto" class="mb-2 block">Foto prestasi <span class="font-normal text-slate-500">(opsional)</span></label>
                    <label for="foto" class="photo-upload-box">
                        <span class="photo-upload-icon" aria-hidden="true">↑</span>
                        <span class="photo-upload-title">Pilih foto untuk diunggah</span>
                        <span class="photo-upload-help">JPG, PNG, atau WebP · Maksimal 2 MB</span>
                        <input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp" class="photo-file-input">
                    </label>
                    <img id="foto-preview" class="photo-preview hidden" alt="Pratinjau foto prestasi">
                </div>

                <fieldset>
                    <legend class="mb-2 font-semibold text-slate-800">Mahasiswa penerima</legend>
                    <div class="max-h-52 space-y-2 overflow-y-auto rounded-lg border border-slate-300 p-3">
                        @forelse($mahasiswa as $mhs)
                            <label class="flex cursor-pointer items-center gap-3 rounded px-2 py-2 hover:bg-slate-50">
                                <input type="checkbox" name="mahasiswa_ids[]" value="{{ $mhs->id }}" @checked(in_array($mhs->id, old('mahasiswa_ids', [])))>
                                <span>{{ $mhs->nama }} <span class="text-xs text-slate-500">({{ $mhs->nim }})</span></span>
                            </label>
                        @empty
                            <p class="text-sm text-slate-500">Belum ada data mahasiswa.</p>
                        @endforelse
                    </div>
                </fieldset>

                <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm">Simpan prestasi</button>
            </form>
        </x-panel>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5 sm:px-7">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950">Daftar prestasi</h2>
                    <p class="mt-1 text-sm text-slate-500">{{ $prestasi->total() }} data terdaftar</p>
                </div>
            </div>

            <form action="{{ route('prestasi.index') }}" method="GET" role="search" class="flex gap-3 border-b border-slate-200 px-6 py-4 sm:px-7">
                <label for="search-prestasi" class="sr-only">Cari prestasi</label>
                <input id="search-prestasi" type="search" name="search" value="{{ $search }}" placeholder="Cari nama, tingkat, atau tahun" class="min-w-0 flex-1 rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
                <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white">Cari</button>
                @if($search !== '')
                    <a href="{{ route('prestasi.index') }}" class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700">Reset</a>
                @endif
            </form>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[680px] text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-3.5 sm:px-7">Prestasi</th>
                            <th class="px-4 py-3.5">Tingkat</th>
                            <th class="px-4 py-3.5">Tahun</th>
                            <th class="px-4 py-3.5">Mahasiswa</th>
                            <th class="px-4 py-3.5">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($prestasi as $item)
                            <tr class="transition hover:bg-blue-50/50">
                                <td class="px-6 py-4 sm:px-7">
                                    <p class="font-semibold text-slate-900">{{ $item->nama_prestasi }}</p>
                                    @if($item->foto || $item->deskripsi)
                                        <div class="achievement-summary">
                                            @if($item->foto)
                                                <img src="{{ asset('storage/' . $item->foto) }}" alt="Foto {{ $item->nama_prestasi }}" class="achievement-thumb">
                                            @endif
                                            @if($item->deskripsi)
                                                <p class="achievement-description">{{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 120) }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-4"><span class="achievement-level">{{ $item->tingkat }}</span></td>
                                <td class="whitespace-nowrap px-4 py-4"><span class="achievement-year">{{ $item->tahun }}</span></td>
                                <td class="px-4 py-4 text-slate-600">
                                    {{ $item->mahasiswa->pluck('nama')->join(', ') ?: 'Belum dipilih' }}
                                </td>
                                <td class="whitespace-nowrap px-4 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('prestasi.edit', $item) }}" class="record-action-button record-action-edit">Edit</a>
                                        <form action="{{ route('prestasi.destroy', $item) }}" method="POST" class="delete-prestasi-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" data-prestasi-name="{{ $item->nama_prestasi }}" class="record-action-button record-action-delete">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center sm:px-7">
                                    <p class="font-medium text-slate-700">{{ $search !== '' ? 'Prestasi tidak ditemukan' : 'Belum ada data prestasi' }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($prestasi->hasPages())
                <div class="border-t border-slate-200 px-6 py-4 sm:px-7">
                    {{ $prestasi->links() }}
                </div>
            @endif
        </div>
    </section>

    <dialog id="delete-prestasi-modal" class="delete-modal w-[calc(100%-2rem)] max-w-md rounded-xl border border-slate-200 bg-white p-0 shadow-2xl backdrop:bg-slate-950/50">
        <div class="p-6 sm:p-7">
            <h2 class="text-lg font-semibold text-slate-950">Hapus prestasi?</h2>
            <p class="mt-2 text-sm text-slate-600">Prestasi <strong id="delete-prestasi-name"></strong> akan dihapus secara permanen beserta hubungan mahasiswa.</p>
            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button type="button" id="cancel-delete-prestasi" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700">Batal</button>
                <button type="button" id="confirm-delete-prestasi" class="rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white">Ya, hapus</button>
            </div>
        </div>
    </dialog>

    <dialog id="edit-prestasi-modal" aria-labelledby="edit-prestasi-title" data-open-on-load="{{ $editPrestasi ? 'true' : 'false' }}" class="m-auto w-[calc(100%-2rem)] max-w-xl max-h-[calc(100%-2rem)] overflow-y-auto rounded-xl border border-slate-200 bg-white p-0 shadow-2xl backdrop:bg-slate-950/50">
        <div class="p-6 sm:p-7">
            <div class="mb-5 flex items-start justify-between gap-4">
                <div>
                    <h2 id="edit-prestasi-title" class="text-lg font-semibold text-slate-950">Edit prestasi</h2>
                    <p class="mt-1 text-sm text-slate-500">Perbarui data dan mahasiswa penerima.</p>
                </div>
                <button type="button" id="close-edit-prestasi" aria-label="Tutup" class="min-h-10 min-w-10 border-2 border-slate-700">&times;</button>
            </div>
            <x-validation-errors />
            <form action="{{ $editPrestasi ? route('prestasi.update', $editPrestasi) : route('prestasi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')
                <input type="hidden" name="edit_prestasi_id" value="{{ $editPrestasi?->id }}">
                <x-form-input id="edit-nama-prestasi" name="nama_prestasi" label="Nama prestasi" :value="old('nama_prestasi', $editPrestasi?->nama_prestasi)" required />
                <div>
                    <label for="edit-tingkat" class="mb-2 block">Tingkat</label>
                    <select id="edit-tingkat" name="tingkat" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
                        @foreach(['Kampus', 'Kota', 'Provinsi', 'Nasional', 'Internasional'] as $tingkat)
                            <option value="{{ $tingkat }}" @selected(old('tingkat', $editPrestasi?->tingkat) === $tingkat)>{{ $tingkat }}</option>
                        @endforeach
                    </select>
                </div>
                <x-form-input id="edit-tahun" name="tahun" label="Tahun" type="number" :value="old('tahun', $editPrestasi?->tahun)" min="1900" :max="date('Y') + 1" required />
                <div>
                    <label for="edit-deskripsi" class="mb-2 block">Deskripsi <span class="font-normal text-slate-500">(opsional)</span></label>
                    <textarea id="edit-deskripsi" name="deskripsi" rows="4" class="summernote w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">{{ old('deskripsi', $editPrestasi?->deskripsi) }}</textarea>
                </div>
                <div>
                    <label for="edit-foto" class="mb-2 block">Foto prestasi <span class="font-normal text-slate-500">(opsional)</span></label>
                    @if($editPrestasi?->foto)
                        <img src="{{ asset('storage/' . $editPrestasi->foto) }}" alt="Foto {{ $editPrestasi->nama_prestasi }} saat ini" class="photo-current">
                    @endif
                    <label for="edit-foto" class="photo-upload-box">
                        <span class="photo-upload-icon" aria-hidden="true">↑</span>
                        <span class="photo-upload-title">Pilih foto baru</span>
                        <span class="photo-upload-help">JPG, PNG, atau WebP · Maksimal 2 MB</span>
                        <input id="edit-foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp" class="photo-file-input">
                    </label>
                    <img id="edit-foto-preview" class="photo-preview hidden" alt="Pratinjau foto baru">
                </div>
                <fieldset>
                    <legend class="mb-2 font-semibold text-slate-800">Mahasiswa penerima</legend>
                    <div class="max-h-52 space-y-2 overflow-y-auto rounded-lg border border-slate-300 p-3">
                        @foreach($mahasiswa as $mhs)
                            @php
                                $selectedIds = old('mahasiswa_ids', $editPrestasi ? $editPrestasi->mahasiswa->pluck('id')->all() : []);
                            @endphp
                            <label class="flex cursor-pointer items-center gap-3 rounded px-2 py-2 hover:bg-slate-50">
                                <input type="checkbox" name="mahasiswa_ids[]" value="{{ $mhs->id }}" @checked(in_array($mhs->id, $selectedIds))>
                                <span>{{ $mhs->nama }} <span class="text-xs text-slate-500">({{ $mhs->nim }})</span></span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
                <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                    <button type="button" id="cancel-edit-prestasi" class="rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700">Batal</button>
                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white">Simpan perubahan</button>
                </div>
            </form>
        </div>
    </dialog>

    <script>
        const deletePrestasiModal = document.getElementById('delete-prestasi-modal');
        const deletePrestasiName = document.getElementById('delete-prestasi-name');
        let pendingPrestasiDeleteForm = null;

        document.querySelectorAll('.delete-prestasi-form').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                pendingPrestasiDeleteForm = form;
                deletePrestasiName.textContent = form.querySelector('button').dataset.prestasiName;
                deletePrestasiModal.showModal();
            });
        });
        document.getElementById('cancel-delete-prestasi').addEventListener('click', () => deletePrestasiModal.close());
        document.getElementById('confirm-delete-prestasi').addEventListener('click', () => {
            if (pendingPrestasiDeleteForm) pendingPrestasiDeleteForm.submit();
        });
        deletePrestasiModal.addEventListener('click', (event) => {
            if (event.target === deletePrestasiModal) deletePrestasiModal.close();
        });

        const editPrestasiModal = document.getElementById('edit-prestasi-modal');
        document.getElementById('close-edit-prestasi').addEventListener('click', () => editPrestasiModal.close());
        document.getElementById('cancel-edit-prestasi').addEventListener('click', () => editPrestasiModal.close());
        editPrestasiModal.addEventListener('click', (event) => {
            if (event.target === editPrestasiModal) editPrestasiModal.close();
        });
        function initializeSummernote(selector) {
            $(selector).each(function () {
                if ($(this).next('.note-editor').length) return;
                $(this).summernote({
                height: 180,
                placeholder: 'Tulis deskripsi prestasi...',
                dialogsInBody: true,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['codeview']]
                ]
                });
            });
        }

        function showEditPrestasiModal() {
            editPrestasiModal.showModal();
            initializeSummernote('#edit-deskripsi');
        }
        if (editPrestasiModal.dataset.openOnLoad === 'true') showEditPrestasiModal();
        initializeSummernote('#deskripsi');

        function previewPhoto(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            input.addEventListener('change', () => {
                const file = input.files[0];
                if (!file) {
                    preview.removeAttribute('src');
                    preview.classList.add('hidden');
                    return;
                }
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            });
        }
        previewPhoto('foto', 'foto-preview');
        previewPhoto('edit-foto', 'edit-foto-preview');
    </script>
@endsection
