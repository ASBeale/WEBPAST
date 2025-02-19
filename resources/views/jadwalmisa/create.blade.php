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
            <form method="post" action="/jadwalmisa">
                @csrf
                <div class="space-y-12">
              
                  <div class="border-b border-gray-900/10 pb-12">
                    <div class="text-center mb-4">
                        <h2 class="text-2xl font-semibold leading-7 text-gray-900">Jadwal Misa</h2>
                        <p class="mt-2 text-sm leading-6 text-gray-600">Tambah Jadwal Misa Bertugas PAST</p>
                    </div>

                    @if (session()->has('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-medium">Error!</span> {{ session('error') }}
                </div>
                @endif

                    <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-1">
                      <div class="mt-1 space-y-6">

                        {{-- periode aktif --}}
                        <div class="w-full">
                            <label class="block text-sm font-medium leading-6 text-gray-900">Periode Aktif</label>
                            <input type="text" 
                                   class="mt-2 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 bg-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 sm:text-sm sm:leading-6" 
                                   value="{{ $periodes->where('status_periode', 1)->first()->nama_periode }} ({{ $periodes->where('status_periode', 1)->first()->tanggal_mulai_periode->format('d M Y') }} - {{ $periodes->where('status_periode', 1)->first()->tanggal_selesai_periode->format('d M Y') }})" 
                                   readonly>
                            <input type="hidden" name="periodeID" value="{{ $periodes->where('status_periode', 1)->first()->periodeID }}">
                        </div>

                       {{-- input nama misa --}}
                        <div class="w-full">
                            <label for="jenisMisaID" class="block text-sm font-medium leading-6 text-gray-900">Jenis Misa</label>
                            <select name="jenisMisaID" id="jenisMisaID" 
                                class="mt-2 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('jenisMisaID') is-invalid @enderror">
                                <option value="" disabled selected> Pilih Jenis Misa</option>
                                @foreach ($jenismisas as $jenismisa)
                                    <option value="{{ $jenismisa->jenisMisaID }}" 
                                        {{ old('jenisMisaID') == $jenismisa->jenisMisaID ? 'selected' : '' }}>
                                        {{ $jenismisa->jenis_misa }}
                                    </option>
                                @endforeach
                            </select>

                            @error('jenisMisaID')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                       {{-- input Kelompok yang ditugaskan --}}
                        <div class="w-full">
                            <label for="kelompokID" class="block text-sm font-medium leading-6 text-gray-900">Kelompok yang ditugaskan</label>
                            <select name="kelompokID" id="kelompokID" 
                                class="mt-2 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('kelompokID') is-invalid @enderror">
                                <option value="" disabled selected> Pilih Kelompok</option>
                                @foreach ($kelompoks as $kelompok)
                                    <option value="{{ $kelompok->kelompokID }}" 
                                        {{ old('kelompokID') == $kelompok->kelompokID ? 'selected' : '' }}>
                                        {{ $kelompok->nama_kelompok }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kelompokID')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                         {{-- input Tanggal Misa --}}
                         <div class="w-full">
                            <label for="tanggal_jam_misa" class="block text-sm font-medium leading-6 text-gray-900">Tanggal Jam Misa</label>
                            <div class="mt-2">
                                <input type="datetime-local" name="tanggal_jam_misa" id="tanggal_jam_misa" 
                                    class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('tanggal_jam_misa') is-invalid @enderror" 
                                    value="{{ old('tanggal_jam_misa') }}">
                            </div>
                            @error('tanggal_jam_misa')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                    </div>
                  </div>
                </div>
              
                <div class="mt-6 flex items-center justify-end gap-x-6">
                  <a href="/jadwalmisa">
                  <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                  </a>
                  <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                </div>
              </form>
        </main> 
    </body>
    </html>