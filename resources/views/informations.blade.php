<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    @if ($informations->isEmpty())
    <p class="flex justify-center text-gray-500 ">Belum ada Informasi</p>
    @else

    @foreach ($informations as $information)

    <div class="flex justify-center mt-0">
        <article class="py-8 max-w-screen-lg border-b border-gray-400 flex">
            <!-- Bagian Teks -->
            <div class="mr-4 flex-1">
                <a href="/single-information/{{ $information['slug'] }}" class="hover:underline">
                <h2 class="mb-1 text-3xl tracking-tight font-bold text-gray-900">{{ $information['title'] }}</h2>
                </a>
                <div class="text-base text-gray-900">
                    By Admin PAST | {{ $information->created_at->diffForHumans()}}
                    <p class="my-4 font-light">{{ Str::limit($information['body'], 195) }}</p>
                    <a href="/single-information/{{ $information['slug'] }}" class="font-medium text-blue-500 hover:underline">Read more &raquo;</a>
                </div>
            </div>
    
            <!-- Bagian Gambar -->
            <div class="ml-4 flex-shrink-0">
                <img src="{{ asset('/storage/' . $information->image) }}" alt="Gambar Artikel" class="w-60 h-60 object-cover">
            </div>
        </article>
    </div>
    @endforeach
    @endif

</x-layout>