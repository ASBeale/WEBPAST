<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    <div class="flex justify-center mt-8">
        <article class="px-4 max-w-screen-md flex flex-col items-center">
            <!-- Bagian Gambar -->
            <div class="-mt-6 mb-5">
                <img src="{{ asset('/storage/' . $information->image) }}" alt="Gambar Artikel" class="w-80 h-80 object-cover rounded-md shadow-lg">
            </div>

            <!-- Bagian Judul -->
            <h2 class="mb-2 text-3xl tracking-tight font-bold text-gray-900 text-center">{{ $information['title'] }}</h2>
            
            <!-- Bagian Tanggal dan Admin -->
            <div class="text-sm text-gray-500 text-center mb-4">
                By Admin PAST | {{ $information->created_at->diffForHumans() }}
            </div>

            <!-- Bagian Teks -->
            <div class="text-base text-gray-900 text-center">
                <p class="mb-6 font-light text-justify">{{ $information['body'] }}</p>
                <a href="/" class="font-medium text-blue-500 hover:underline transition duration-200 ease-in-out">&laquo; Back to home</a>
            </div>
        </article>
    </div>
</x-layout>
