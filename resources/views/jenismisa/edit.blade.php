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
            
            <form method="post" action="/jenismisa/{{ $jenismisa->jenisMisaID }}">
              @method('put')
                @csrf
                <div class="space-y-12">
              
                  <div class="border-b border-gray-900/10 pb-12">
                    <div class="text-center mb-4">
                      <h2 class="text-2xl font-semibold leading-7 text-gray-900">Jenis Misa</h2>
                      <p class="mt-2 text-sm leading-6 text-gray-600">Edit Jenis Misa bertugas PAST</p>
                  </div>

                  @if (session()->has('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-medium">Error!</span> {{ session('error') }}
                </div>
                @endif
                    <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-1">
                      <div class="mt-1 space-y-6">
                        {{-- input jenis_misa --}}
                        <div class="w-full">
                            <label for="jenis_misa" class="block text-sm font-medium leading-6 text-gray-900">Jenis Misa</label>
                            <div class="mt-2">
                                <input type="text" name="jenis_misa" id="jenis_misa" 
                                    class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('jenis_misa') is-invalid @enderror" 
                                    value="{{ old('jenis_misa', $jenismisa->jenis_misa) }}" autofocus>
                            </div>
                            @error('jenis_misa')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>
                  </div>
                </div>
              
                <div class="mt-6 flex items-center justify-end gap-x-6">
                  <a href="/jenismisa">
                  <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                  </a>
                  <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Update</button>
                </div>
              </form>
        </main>
    </body>
    </html>
