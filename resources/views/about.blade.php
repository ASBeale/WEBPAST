<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    @foreach ($about as $ab)
    <div class="flex flex-col items-center mt-0">
        <!-- Bagian Judul -->
        <h2 class="mb-0 text-2xl tracking-tight font-bold text-gray-900 text-center">{{ $ab['title'] }}</h2>

        <article class="py-4 max-w-screen-md flex flex-col items-center">
            <!-- Bagian Gambar -->
            <div class="mb-4">
                <img src="{{ asset('/storage/' . $ab->image) }}" alt="Gambar Artikel" class="w-60 h-60 object-cover rounded-full">
            </div>

            <!-- Bagian Teks -->
            <div class="text-center px-4">
                <p class="text-base text-gray-900 font-light text-justify">{{ $ab['body'] }}</p>
            </div>
        </article>
    </div>
    @endforeach

</x-layout>
