<x-layout>
    <x-slot:title>{{ $title }}</x-slot>

    <div class="text-center">
        <p class="mt-3 mb-5 text-gray-500 dark:text-gray-400">Hubungi Putra Altar Santo Tarsisius melalui contact dibawah ini</p>
    </div>

    @if ($contact->isEmpty())
    <p class="flex justify-center text-gray-500 ">Belum ada Contact</p>
    @else

    @foreach ($contact as $kontak)
    <div class="flex flex-col items-center justify-center text-center mb-5">
        <span class="p-3 text-blue-500 rounded-full bg-blue-100/80 dark:bg-gray-800">
            <img src="{{ asset('/storage/' . $kontak['image']) }}" alt="Gambar Artikel" class="w-10 h-10 object-cover">
        </span>

        <h2 class="mt-4 text-lg font-medium text-gray-800 dark:text-white">{{ $kontak['title'] }}</h2>
        @if(filter_var($kontak['isi'], FILTER_VALIDATE_URL))
            <a href='{{ $kontak['isi'] }}' target="_blank" class="mt-2 text-blue-600 dark:text-blue-400">{{ $kontak['isi'] }}</a>
        @else
            <a href='tel:{{ $kontak['isi'] }}' target="_blank" class="mt-2 text-blue-600 dark:text-blue-400">{{ $kontak['isi'] }}</a>
        @endif
    </div>
    @endforeach

    @endif

</x-layout>