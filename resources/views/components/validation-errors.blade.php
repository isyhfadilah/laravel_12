@if($errors->any())
    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
        <p class="font-semibold">Periksa kembali data Anda:</p>
        <ul class="mt-2 list-inside list-disc space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
