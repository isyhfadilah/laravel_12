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

                <form action="/mahasiswa" method="POST" class="space-y-5">
                    @csrf
                    <x-form-input id="nama" name="nama" label="Nama lengkap" :value="old('nama')" placeholder="Contoh: Aisyah Putri" required />
                    <x-form-input id="nim" name="nim" label="NIM" :value="old('nim')" placeholder="Contoh: 20240001" required />
                    <x-form-input id="email" name="email" label="Email" type="email" :value="old('email')" placeholder="nama@kampus.ac.id" required />
                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 active:bg-blue-800">Simpan data</button>
                </form>
            </x-panel>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-200 px-6 py-5 sm:px-7">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-950">Daftar mahasiswa</h2>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ $mahasiswa->count() }} {{ $search !== '' ? 'hasil pencarian' : 'data terdaftar' }}
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
                    <div class="flex items-center gap-2">
                        <button type="submit" class="inline-flex flex-1 items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 sm:flex-none">Cari</button>
                        @if($search !== '')
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
                                <th class="px-4 py-3.5 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($mahasiswa as $mhs)
                                <tr class="transition hover:bg-blue-50/50">
                                    <td class="px-6 py-4 font-medium text-slate-900 sm:px-7">{{ $mhs->nama }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-slate-600">{{ $mhs->nim }}</td>
                                    <td class="px-4 py-4 text-slate-600">{{ $mhs->email }}</td>
                                    <td class="whitespace-nowrap px-4 py-4">
                                        <div class="flex items-center gap-2">
                                            <button
                                                type="button"
                                                class="edit-mahasiswa-button inline-flex items-center rounded-lg border border-blue-200 px-3 py-2 text-sm font-semibold text-blue-700 transition hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-blue-100"
                                                data-mahasiswa-id="{{ $mhs->id }}"
                                                data-mahasiswa-name="{{ $mhs->nama }}"
                                                data-mahasiswa-nim="{{ $mhs->nim }}"
                                                data-mahasiswa-email="{{ $mhs->email }}"
                                            >Edit</button>
                                            <form action="/mahasiswa/{{ $mhs->id }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    data-mahasiswa-name="{{ $mhs->nama }}"
                                                    data-mahasiswa-nim="{{ $mhs->nim }}"
                                                    data-mahasiswa-email="{{ $mhs->email }}"
                                                    class="inline-flex items-center rounded-lg border border-red-200 px-3 py-2 text-sm font-semibold text-red-700 transition hover:bg-red-50 focus:outline-none focus:ring-4 focus:ring-red-100"
                                                >Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                        <td colspan="4" class="px-6 py-12 text-center sm:px-7">
                                        <p class="font-medium text-slate-700">{{ $search !== '' ? 'Mahasiswa tidak ditemukan' : 'Belum ada data mahasiswa' }}</p>
                                        <p class="mt-1 text-sm text-slate-500">{{ $search !== '' ? 'Coba kata kunci lain atau reset pencarian.' : 'Data yang ditambahkan akan muncul di sini.' }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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

                <form id="edit-mahasiswa-form" action="{{ $editMahasiswa ? '/mahasiswa/' . $editMahasiswa->id : '/mahasiswa' }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <input id="edit-mahasiswa-id" type="hidden" name="edit_mahasiswa_id" value="{{ $editMahasiswa?->id }}">
                    <x-form-input id="edit-nama" name="nama" label="Nama lengkap" :value="old('nama', $editMahasiswa?->nama)" required />
                    <x-form-input id="edit-nim" name="nim" label="NIM" :value="old('nim', $editMahasiswa?->nim)" required />
                    <x-form-input id="edit-email" name="email" label="Email" type="email" :value="old('email', $editMahasiswa?->email)" required />

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
            const closeEditModal = () => editModal.close();

            document.querySelectorAll('.edit-mahasiswa-button').forEach((button) => {
                button.addEventListener('click', () => {
                    const id = button.dataset.mahasiswaId;
                    editForm.action = `/mahasiswa/${id}`;
                    editId.value = id;
                    editNama.value = button.dataset.mahasiswaName;
                    editNim.value = button.dataset.mahasiswaNim;
                    editEmail.value = button.dataset.mahasiswaEmail;
                    editModal.showModal();
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