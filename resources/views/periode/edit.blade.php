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
        {{-- side bar --}}
        <x-sidebar-admin></x-sidebar-admin>

        <main class="p-4 md:ml-64 h-auto pt-7">
            
            <form method="POST" action="{{ route('periode.update', $periode->periodeID) }}">
              @method('put')
                @csrf
                <div class="space-y-12">
              
                  <div class="border-b border-gray-900/10 pb-12">
                    <div class="text-center mb-4">
                      <h2 class="text-2xl font-semibold leading-7 text-gray-900">Periode</h2>
                      <p class="mt-2 text-sm leading-6 text-gray-600">Edit Periode Putra Altar Santo Tarsisius</p>
                  </div> 

                  @if (session()->has('error'))
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                        <span class="font-medium">Error!</span> {{ session('error') }}
                    </div>
                    @endif
                  
                    <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-1">
                      <div class="mt-1 space-y-6">

                        {{-- ubah status --}}
                      <div class="w-full">
                        <label for="status_periode" class="block text-sm font-medium leading-6 text-gray-900">Status Periode</label>
                        <div class="mt-2 flex items-center">
                            <input type="checkbox" name="status_periode" id="status_periode" 
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" 
                                value="1"
                                {{ old('status_periode', $periode->status_periode) == 1 ? 'checked' : '' }}>
                            <label for="status_periode" class="ml-2 block text-sm text-gray-900">
                                Aktif
                            </label>
                        </div>
                        @error('status_periode')
                            <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                <span class="font-medium">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                        {{-- input nama_periode --}}
                        <div class="w-full">
                          <label for="nama_periode" class="block text-sm font-medium leading-6 text-gray-900">Nama Periode</label>
                          <div class="mt-2">
                              <input type="text" name="nama_periode" id="nama_periode" 
                                  class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('nama_periode') is-invalid @enderror" 
                                  value="{{ old('nama_periode', $periode->nama_periode) }}" autofocus>
                          </div>
                          @error('nama_periode')
                              <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                  <span class="font-medium">{{ $message }}</span>
                              </div>
                          @enderror
                      </div>

                      {{-- input tanggal_mulai_periode --}}
                      <div class="w-full">
                          <label for="tanggal_mulai_periode" class="block text-sm font-medium leading-6 text-gray-900">Tanggal Mulai Periode</label>
                          <div class="mt-2">
                              <input type="date" name="tanggal_mulai_periode" id="tanggal_mulai_periode" 
                                  class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('tanggal_mulai_periode') is-invalid @enderror" 
                                  value="{{ old('tanggal_mulai_periode' , $periode->tanggal_mulai_periode ? date('Y-m-d', strtotime($periode->tanggal_mulai_periode)) : '') }}">
                          </div>
                          @error('tanggal_mulai_periode')
                              <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                  <span class="font-medium">{{ $message }}</span>
                              </div>
                          @enderror
                      </div>

                      {{-- input tanggal_selesai_periode --}}
                      <div class="w-full">
                          <label for="tanggal_selesai_periode" class="block text-sm font-medium leading-6 text-gray-900">Tanggal Selesai Periode</label>
                          <div class="mt-2">
                              <input type="date" name="tanggal_selesai_periode" id="tanggal_selesai_periode" 
                                  class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('tanggal_selesai_periode') is-invalid @enderror" 
                                  value="{{ old('tanggal_selesai_periode' , $periode->tanggal_selesai_periode ? date('Y-m-d', strtotime($periode->tanggal_selesai_periode)) : '') }}">
                          </div>
                          @error('tanggal_selesai_periode')
                              <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                  <span class="font-medium">{{ $message }}</span>
                              </div>
                          @enderror
                      </div>
                    </div>
                  </div>
                </div>
              
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="{{ route('periode.index') }}">
                  <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                  </a>
                  <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Update</button>
                </div>
              </form>
        </main>
    </body>
    </html>