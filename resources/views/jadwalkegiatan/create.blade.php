    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite(['resources/css/app.css','resources/js/app.js'])
        <link href="{{ asset('img/logo_past_icon.ico') }}" rel="icon">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Admin PAST</title>
    </head>
    <body>
        <x-sidebar-admin></x-sidebar-admin>

        <main class="p-4 md:ml-64 h-auto pt-7">
            <form method="post" action="/jadwalkegiatan">
                @csrf
                <div class="space-y-12">
              
                  <div class="border-b border-gray-900/10 pb-12">
                    <div class="text-center mb-4">
                        <h2 class="text-2xl font-semibold leading-7 text-gray-900">Jadwal Kegiatan</h2>
                        <p class="mt-2 text-sm leading-6 text-gray-600">Tambah Jadwal Kegiatan PAST</p>
                    </div>

                    @if (session()->has('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-medium">Error!</span> {{ session('error') }}
                </div>
                @endif

                    <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-1">
                      <div class="mt-1 space-y-6">

                        {{-- input judul kegiatan --}}
                        <div class="w-full">
                            <label for="judul_kegiatan" class="block text-sm font-medium leading-6 text-gray-900">Judul Kegiatan</label>
                            <div class="mt-2">
                                <input type="text" name="judul_kegiatan" id="judul_kegiatan" 
                                    class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('judul_kegiatan') is-invalid @enderror" 
                                    value="{{ old('judul_kegiatan') }}" autofocus>
                            </div>
                            @error('judul_kegiatan')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        {{-- input jenis kegiatan --}}
                        <div class="w-full">
                            <label for="jenisKegiatanID" class="block text-sm font-medium leading-6 text-gray-900">Jenis Kegiatan</label>
                            <select name="jenisKegiatanID" id="jenisKegiatanID" 
                                class="mt-2 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('jenisKegiatanID') is-invalid @enderror">
                                <option value="">Pilih Jenis Kegiatan</option>
                                @foreach ($jeniskegiatans as $jeniskegiatan)
                                    <option value="{{ $jeniskegiatan->jenisKegiatanID }}"
                                        {{ old('jenisKegiatanID') == $jeniskegiatan->jenisKegiatanID ? 'selected' : '' }}>
                                        {{ $jeniskegiatan->jenis_kegiatan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenisKegiatanID')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        
                         {{-- input Tanggal jam mulai kegiatan --}}
                         <div class="w-full">
                            <label for="tanggal_jam_mulai_kegiatan" class="block text-sm font-medium leading-6 text-gray-900">Tanggal dan Jam Mulai Kegiatan</label>
                            <div class="mt-2">
                                <input type="datetime-local" name="tanggal_jam_mulai_kegiatan" id="tanggal_jam_mulai_kegiatan" 
                                    class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('tanggal_jam_mulai_kegiatan') is-invalid @enderror" 
                                    value="{{ old('tanggal_jam_mulai_kegiatan') }}">
                            </div>
                            @error('tanggal_jam_mulai_kegiatan')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                         {{-- input Tanggal jam selesai kegiatan --}}
                         <div class="w-full">
                            <label for="tanggal_jam_selesai_kegiatan" class="block text-sm font-medium leading-6 text-gray-900">Tanggal dan Jam Selesai Kegiatan</label>
                            <div class="mt-2">
                                <input type="datetime-local" name="tanggal_jam_selesai_kegiatan" id="tanggal_jam_selesai_kegiatan" 
                                    class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('tanggal_jam_selesai_kegiatan') is-invalid @enderror" 
                                    value="{{ old('tanggal_jam_selesai_kegiatan') }}">
                            </div>
                            @error('tanggal_jam_selesai_kegiatan')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                  </div>
                </div>
              
                <div class="mt-6 flex items-center justify-end gap-x-6">
                  <a href="/jadwalkegiatan">
                  <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                  </a>
                  <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                </div>
              </form>
        </main> 
    </body>
    </html>