@props(['register' => false])

<aside class="auth-intro">
    <span class="neo-tag">PORTAL AKADEMIK / MAHASISWA</span>
    <h1>{{ $register ? 'Langkah baru.' : 'Halo lagi.' }}<br><span>{{ $register ? 'Mulai di sini.' : 'Siap produktif?' }}</span></h1>
    <p>{{ $register ? 'Satu akun untuk mengakses dan mengelola data mahasiswa. Isi data dirimu, lalu mulai.' : 'Ruang sederhana untuk data yang lebih teratur. Masuk dan lanjutkan aktivitas akademikmu.' }}</p>
    <div class="academic-art" aria-hidden="true">
        <span class="art-orbit">✳</span>
        <div class="art-card">
            <span class="art-label">KARTU MAHASISWA</span>
            <div class="art-profile"><span class="art-avatar">M.</span><div><strong>Siap melangkah.</strong><span>Belajar. Tumbuh. Berkarya.</span></div></div>
            <div class="art-bars"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
        </div>
        <span class="art-sticker">YOUR NEXT<br>CHAPTER ↗</span>
    </div>
    <p class="auth-note"><span aria-hidden="true">●</span> Data rapi. Aktivitas lebih mudah.</p>
</aside>