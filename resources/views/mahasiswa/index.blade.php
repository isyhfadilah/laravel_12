<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Data Mahasiswa </h1>
    <form action="/mahasiswa" method="POST">
        @csrf

        <input type="text" name="nama" placeholder="Nama">
        <input type="text" name="nim" placeholder="NIM">
        <input type="email" name="email" placeholder="Email">

        <button type="submit">Simpan</button>
    </form>

    @if($errors->any())
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <ul>
        @foreach($mahasiswa as $mhs)
            <li>{{ $mhs->nama }} - {{ $mhs->nim }} - {{ $mhs->email }}</li>
        @endforeach
    </ul>
</body>
</html>