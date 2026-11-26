@extends('layouts.app', ['title' => 'Data Mahasiswa'])

@section('content')
        <x-page-heading
            title="Data Mahasiswa"
            description="Kelola daftar mahasiswa dengan cepat dan teratur."
            class="max-w-2xl"
        />

        @if(session('success'))
            <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="status">
                {{ session('success') }}
            </div>
        @endif

        <section class="grid gap-7 lg:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)] lg:items-start">
            <x-panel title="Tambah mahasiswa" description="Lengkapi informasi berikut untuk menyimpan data baru.">
                @unless($editMahasiswa)
                    <x-validation-errors />
                @endunless

                <form action="/mahasiswa" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <x-validation-errors />
                    <x-form-input id="nama" name="nama" label="Nama lengkap" :value="old('nama')" placeholder="Contoh: Aisyah Putri" required />
                    <x-form-input id="nim" name="nim" label="NIM" :value="old('nim')" placeholder="Contoh: 20240001" required />
                    <x-form-input id="email" name="email" label="Email" type="email" :value="old('email')" placeholder="nama@kampus.ac.id" required />
                    <div>
                        <label for="bio" class="mb-2 block">Bio <span class="font-normal text-slate-500">(opsional)</span></label>
                        <textarea id="bio" name="bio" class="summernote">{{ old('bio') }}</textarea>
                    </div>
                    <div>
                        <label for="foto" class="mb-2 block">Foto profil <span class="font-normal text-slate-500">(opsional)</span></label>
                        <label for="foto" class="photo-upload-box">
                            <span class="photo-upload-icon" aria-hidden="true">&#8593;</span>
                            <span class="photo-upload-title">Pilih foto profil</span>
                            <span class="photo-upload-help">JPG, PNG, atau WebP &middot; Maksimal 2 MB</span>
                            <input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp" class="photo-file-input">
                        </label>
                        <img id="foto-preview" class="photo-preview hidden" alt="Pratinjau foto profil">
                    </div>
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 active:bg-blue-800">Simpan data</button>
                </form>
            </x-panel>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5 sm:px-7">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950">Daftar mahasiswa</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ $mahasiswa->total() }} {{ ($search !== '' || $prestasiFilter !== '') ? 'hasil pencarian' : 'data terdaftar' }}
                        </p>
                    </div>
                </div>
                <form action="/mahasiswa" method="GET" role="search" class="flex flex-col gap-3 border-b border-slate-200 px-6 py-4 sm:flex-row sm:items-center sm:px-7">
                    <div class="relative min-w-0 flex-1">
                        <label for="search-mahasiswa" class="sr-only">Cari nama, NIM, atau email mahasiswa</label>
                        <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" aria-hidden="true"></i>
                        <input
                            id="search-mahasiswa"
                            type="search"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari nama, NIM, atau email"
                            class="w-full appearance-none rounded-lg border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100"
                        >
                    </div>
                    <div class="min-w-0 sm:w-64">
                        <label for="filter-prestasi" class="sr-only">Filter berdasarkan prestasi</label>
                        <input
                            id="filter-prestasi"
                            type="search"
                            name="prestasi"
                            list="daftar-nama-prestasi"
                            value="{{ $prestasiFilter }}"
                            placeholder="Cari berdasarkan prestasi"
                            class="w-full appearance-none rounded-lg border border-slate-300 bg-white py-2.5 px-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-100"
                        >
                        <datalist id="daftar-nama-prestasi">
                            @foreach($daftarPrestasi as $itemPrestasi)
                                <option value="{{ $itemPrestasi->nama_prestasi }}"></option>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 sm:flex-none">Cari</button>
                        @if($search !== '' || $prestasiFilter !== '')
                            <a href="/mahasiswa" class="inline-flex flex-1 items-center justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-100 sm:flex-none">Reset</a>
                        @endif
                    </div>
                </form>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[560px] text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-6 py-3.5 font-semibold sm:px-7">Mahasiswa</th>
                                <th class="px-4 py-3.5 font-semibold">NIM</th>
                                <th class="px-4 py-3.5 font-semibold">Email</th>
                                <th class="px-4 py-3.5 font-semibold">Jumlah Prestasi</th>
                                <th class="px-4 py-3.5 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($mahasiswa as $mhs)
                                <tr class="transition hover:bg-blue-50/50">
                                    <td class="px-6 py-4 font-medium text-slate-900 sm:px-7">
                                        <div class="student-identity">
                                            @if($mhs->foto)
                                                <img src="{{ asset('storage/' . $mhs->foto) }}" alt="Foto {{ $mhs->nama }}" class="student-avatar">
                                            @else
                                                <span class="student-avatar student-avatar-placeholder" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($mhs->nama, 0, 1)) }}</span>
                                            @endif
                                            <div><span class="student-name">{{ $mhs->nama }}</span>@if($mhs->bio)<span class="student-bio">{{ \Illuminate\Support\Str::limit(strip_tags($mhs->bio), 72) }}</span>@endif</div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 text-slate-600">{{ $mhs->nim }}</td>
                                    <td class="px-4 py-4 text-slate-600">{{ $mhs->email }}</td>
                                    <td class="px-4 py-4 text-slate-600">
                                        @if($mhs->prestasi->isEmpty())
                                            <span class="text-slate-400">Belum ada</span>
                                        @else
                                            {{ $mhs->prestasi->pluck('nama_prestasi')->join(', ') }}
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4">
                                        <div class="flex items-center gap-2">
                                            <button
                                                type="button"
                                                class="edit-mahasiswa-button record-action-button record-action-edit"
                                                data-mahasiswa-id="{{ $mhs->id }}"
                                                data-mahasiswa-name="{{ $mhs->nama }}"
                                                data-mahasiswa-nim="{{ $mhs->nim }}"
                                                data-mahasiswa-email="{{ $mhs->email }}"
                                                data-mahasiswa-bio="{{ $mhs->bio }}"
                                                data-mahasiswa-foto="{{ $mhs->foto ? asset('storage/' . $mhs->foto) : '' }}"
                                            >Edit</button>
                                            <form action="/mahasiswa/{{ $mhs->id }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    data-mahasiswa-name="{{ $mhs->nama }}"
                                                    data-mahasiswa-nim="{{ $mhs->nim }}"
                                                    data-mahasiswa-email="{{ $mhs->email }}"
                                                    class="record-action-button record-action-delete"
                                                >Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                        <td colspan="5" class="px-6 py-12 text-center sm:px-7">
                                        <p class="font-medium text-slate-700">{{ ($search !== '' || $prestasiFilter !== '') ? 'Mahasiswa tidak ditemukan' : 'Belum ada data mahasiswa' }}</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ ($search !== '' || $prestasiFilter !== '') ? 'Coba kata kunci lain atau reset pencarian.' : 'Data yang ditambahkan akan muncul di sini.' }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($mahasiswa->hasPages())
                    <div class="border-t border-slate-200 px-6 py-4 sm:px-7">
                        {{ $mahasiswa->links() }}
                    </div>
                @endif
            </div>
        </section>

        <dialog id="delete-modal" aria-labelledby="delete-modal-title" class="delete-modal w-[calc(100%-2rem)] max-w-md rounded-xl border border-slate-200 bg-white p-0 shadow-2xl backdrop:bg-slate-950/50">
            <div class="p-6 sm:p-7">
                <div class="flex items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-red-50 text-red-600" aria-hidden="true">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.3 3.7 2.6 17a2 2 0 0 0 1.7 3h15.4a2 2 0 0 0 1.7-3L13.7 3.7a2 2 0 0 0-3.4 0Z" />
                            <path d="M12 9v4M12 17h.01" />
                        </svg>
                    </div>
                    <div>
                        <h2 id="delete-modal-title" class="text-lg font-semibold text-slate-950">Hapus data mahasiswa?</h2>
                        <p class="mt-1 text-sm leading-6 text-slate-600">Data berikut akan dihapus secara permanen.</p>
                    </div>
                </div>

                <div class="mt-5 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3.5">
                    <p id="delete-mahasiswa-name" class="font-semibold text-slate-900"></p>
                    <div class="mt-1 flex flex-wrap gap-x-3 gap-y-1 text-xs text-slate-500">
                        <span>NIM <span id="delete-mahasiswa-nim" class="font-medium text-slate-700"></span></span>
                        <span class="text-slate-300" aria-hidden="true">|</span>
                        <span id="delete-mahasiswa-email"></span>
                    </div>
                </div>

                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <button type="button" id="cancel-delete" class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-100">Batal</button>
                    <button type="button" id="confirm-delete" class="inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 focus:outline-none focus:ring-4 focus:ring-red-200">Ya, hapus</button>
                </div>
            </div>
        </dialog>

        <dialog
            id="edit-modal"
            aria-labelledby="edit-modal-title"
            data-open-on-load="{{ $editMahasiswa ? 'true' : 'false' }}"
            class="edit-modal m-auto w-[calc(100%-2rem)] max-w-xl max-h-[calc(100%-2rem)] overflow-y-auto rounded-xl border border-slate-200 bg-white p-0 shadow-2xl backdrop:bg-slate-950/50"
        >
            <div class="p-6 sm:p-7">
                <div class="mb-6 flex items-start justify-between gap-4">
                    <div>
                        <h2 id="edit-modal-title" class="text-lg font-semibold text-slate-950">Edit mahasiswa</h2>
                        <p class="mt-1 text-sm text-slate-500">Perbarui informasi mahasiswa dengan data terbaru.</p>
                    </div>
                    <button type="button" id="cancel-edit" aria-label="Tutup" class="rounded-lg px-2 py-1 text-2xl leading-none text-slate-500 transition hover:bg-slate-100 hover:text-slate-700">&times;</button>
                </div>

                <x-validation-errors />

                <form id="edit-mahasiswa-form" action="{{ $editMahasiswa ? '/mahasiswa/' . $editMahasiswa->id : '/mahasiswa' }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <input id="edit-mahasiswa-id" type="hidden" name="edit_mahasiswa_id" value="{{ $editMahasiswa?->id }}">
                    <x-form-input id="edit-nama" name="nama" label="Nama lengkap" :value="old('nama', $editMahasiswa?->nama)" required />
                    <x-form-input id="edit-nim" name="nim" label="NIM" :value="old('nim', $editMahasiswa?->nim)" required />
                    <x-form-input id="edit-email" name="email" label="Email" type="email" :value="old('email', $editMahasiswa?->email)" required />
                    <div>
                        <label for="edit-bio" class="mb-2 block">Bio <span class="font-normal text-slate-500">(opsional)</span></label>
                        <textarea id="edit-bio" name="bio" class="summernote">{{ old('bio', $editMahasiswa?->bio) }}</textarea>
                    </div>
                    <div>
                        <label for="edit-foto" class="mb-2 block">Foto profil <span class="font-normal text-slate-500">(opsional)</span></label>
                        <img id="edit-foto-current" class="photo-current hidden" alt="Foto profil saat ini">
                        <label for="edit-foto" class="photo-upload-box">
                            <span class="photo-upload-icon" aria-hidden="true">&#8593;</span>
                            <span class="photo-upload-title">Pilih foto baru</span>
                            <span class="photo-upload-help">JPG, PNG, atau WebP &middot; Maksimal 2 MB</span>
                            <input id="edit-foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp" class="photo-file-input">
                        </label>
                        <img id="edit-foto-preview" class="photo-preview hidden" alt="Pratinjau foto profil baru">
                    </div>

                    <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                        <button type="button" id="cancel-edit-form" class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-slate-100">Batal</button>
                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 active:bg-blue-800">Simpan perubahan</button>
                    </div>
                </form>
            </div>
        </dialog>

        <script>
            const editModal = document.getElementById('edit-modal');
            const editForm = document.getElementById('edit-mahasiswa-form');
            const editId = document.getElementById('edit-mahasiswa-id');
            const editNama = document.getElementById('edit-nama');
            const editNim = document.getElementById('edit-nim');
            const editEmail = document.getElementById('edit-email');
            const editBio = document.getElementById('edit-bio');
            const editFotoCurrent = document.getElementById('edit-foto-current');
            const closeEditModal = () => editModal.close();

            document.querySelectorAll('.edit-mahasiswa-button').forEach((button) => {
                button.addEventListener('click', () => {
                    const id = button.dataset.mahasiswaId;
                    editForm.action = `/mahasiswa/${id}`;
                    editId.value = id;
                    editNama.value = button.dataset.mahasiswaName;
                    editNim.value = button.dataset.mahasiswaNim;
                    editEmail.value = button.dataset.mahasiswaEmail;
                    editBio.value = button.dataset.mahasiswaBio || '';
                    if (editFotoCurrent) {
                        editFotoCurrent.src = button.dataset.mahasiswaFoto || '';
                        editFotoCurrent.classList.toggle('hidden', !button.dataset.mahasiswaFoto);
                    }
                    editModal.showModal();
                    initializeSummernote('#edit-bio');
                    $('#edit-bio').summernote('code', button.dataset.mahasiswaBio || '');
                    editNama.focus();
                });
            });

            document.getElementById('cancel-edit').addEventListener('click', closeEditModal);
            document.getElementById('cancel-edit-form').addEventListener('click', closeEditModal);
            editModal.addEventListener('click', (event) => {
                if (event.target === editModal) {
                    closeEditModal();
                }
            });

            if (editModal.dataset.openOnLoad === 'true') {
                editModal.showModal();
                initializeSummernote('#edit-bio');
            }
            initializeSummernote('#bio');
            initializePhotoPreview('foto', 'foto-preview');
            initializePhotoPreview('edit-foto', 'edit-foto-preview');

            function initializeSummernote(selector) {
                $(selector).each(function () {
                    if ($(this).next('.note-editor').length) return;
                    $(this).summernote({
                        height: 150,
                        placeholder: 'Tulis beberapa kalimat tentang mahasiswa...',
                        dialogsInBody: true,
                        toolbar: [
                            ['font', ['bold', 'italic', 'underline', 'clear']],
                            ['para', ['ul', 'ol', 'paragraph']],
                            ['view', ['codeview']]
                        ]
                    });
                });
            }

            function initializePhotoPreview(inputId, previewId) {
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

            const deleteModal = document.getElementById('delete-modal');
            const deleteName = document.getElementById('delete-mahasiswa-name');
            const deleteNim = document.getElementById('delete-mahasiswa-nim');
            const deleteEmail = document.getElementById('delete-mahasiswa-email');
            const cancelDelete = document.getElementById('cancel-delete');
            const confirmDelete = document.getElementById('confirm-delete');
            let pendingDeleteForm = null;

            document.querySelectorAll('.delete-form').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    pendingDeleteForm = form;
                    const deleteButton = form.querySelector('button');
                    deleteName.textContent = deleteButton.dataset.mahasiswaName;
                    deleteNim.textContent = deleteButton.dataset.mahasiswaNim;
                    deleteEmail.textContent = deleteButton.dataset.mahasiswaEmail;
                    deleteModal.showModal();
                });
            });

            cancelDelete.addEventListener('click', () => {
                deleteModal.close();
                pendingDeleteForm = null;
            });

            confirmDelete.addEventListener('click', () => {
                if (pendingDeleteForm) {
                    pendingDeleteForm.submit();
                }
            });

            deleteModal.addEventListener('click', (event) => {
                if (event.target === deleteModal) {
                    deleteModal.close();
                    pendingDeleteForm = null;
                }
            });
        </script>
@endsection
