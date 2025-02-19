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

        <main class="p-4 md:ml-64 h-auto pt-10">
            
            <form method="post" action="{{ route('anggotas.update', $anggota->anggotaID) }}">
              @method('put')
                @csrf
                <div class="space-y-12">
              
                  <div class="border-b border-gray-900/10 pb-12">
                    <div class="text-center mb-4">
                        <h2 class="text-2xl font-semibold leading-7 text-gray-900">Anggota</h2>
                        <p class="mt-2 text-sm leading-6 text-gray-600">Edit Anggota Putra Altar Santo Tarsisius</p>
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
                        <label for="status_anggota" class="block text-sm font-medium leading-6 text-gray-900">Status Anggota</label>
                        <div class="mt-2 flex items-center">
                            <input type="checkbox" name="status_anggota" id="status_anggota" 
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" 
                                value="1"
                                {{ old('status_anggota', $anggota->status_anggota) == 1 ? 'checked' : '' }}>
                            <label for="status_anggota" class="ml-2 block text-sm text-gray-900">
                                Aktif
                            </label>
                        </div>
                        @error('status_anggota')
                            <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                <span class="font-medium">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                        {{-- input username --}}
                        <div class="w-full">
                            <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Username</label>
                            <div class="mt-2">
                                <input type="text" name="username" id="username" 
                                    class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('username') is-invalid @enderror" 
                                    value="{{ old('username', $anggota->username) }}" autofocus>
                            </div>
                            @error('username')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        {{-- input nama --}}
                        <div class="w-full">
                            <label for="nama" class="block text-sm font-medium leading-6 text-gray-900">Nama Lengkap</label>
                            <div class="mt-2">
                                <input type="text" name="nama" id="nama" 
                                    class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('nama') is-invalid @enderror" 
                                    value="{{ old('nama', $anggota->nama) }}">
                            </div>
                            @error('nama')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
        
                         {{-- input DOB --}}
                         <div class="w-full">
                            <label for="DoB" class="block text-sm font-medium leading-6 text-gray-900">Tanggal Lahir</label>
                            <div class="mt-2">
                                <input type="date" name="DoB" id="DoB" 
                                    class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('DoB') is-invalid @enderror" 
                                    value="{{ old('DoB', $anggota->DoB ? date('Y-m-d', strtotime($anggota->DoB)) : '') }}">
                            </div>
                            @error('DoB')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                            
                        {{-- input no telp --}}
                        <div class="w-full">
                            <label for="TelpNo" class="block text-sm font-medium leading-6 text-gray-900">Nomor Telp</label>
                            <div class="mt-2">
                                <input type="text" name="TelpNo" id="TelpNo" 
                                    class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('TelpNo') is-invalid @enderror" 
                                    value="{{ old('TelpNo', $anggota->TelpNo) }}">
                            </div>
                            @error('TelpNo')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
              
                        {{-- input nama ortu --}}
                        <div class="w-full">
                            <label for="ortu_nama" class="block text-sm font-medium leading-6 text-gray-900">Nama Orang Tua (1 saja)</label>
                            <div class="mt-2">
                                <input type="text" name="ortu_nama" id="ortu_nama" 
                                    class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('ortu_nama') is-invalid @enderror" 
                                    value="{{ old('ortu_nama', $anggota->ortu_nama) }}">
                            </div>
                            @error('ortu_nama')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        {{-- input no telp ortu --}}
                        <div class="w-full">
                            <label for="ortu_telp_no" class="block text-sm font-medium leading-6 text-gray-900">Nomor Telp Orang Tua</label>
                            <div class="mt-2">
                                <input type="text" name="ortu_telp_no" id="ortu_telp_no" 
                                    class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('ortu_telp_no') is-invalid @enderror" 
                                    value="{{ old('ortu_telp_no', $anggota->ortu_telp_no) }}">
                            </div>
                            @error('ortu_telp_no')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        {{-- tanggal bergabung --}}
                        <div class="w-full">
                            <label for="tanggal_bergabung" class="block text-sm font-medium leading-6 text-gray-900">Tanggal Bergabung</label>
                            <div class="mt-2">
                                <input type="date" name="tanggal_bergabung" id="tanggal_bergabung" 
                                    class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('tanggal_bergabung') is-invalid @enderror" 
                                    value="{{ old('tanggal_bergabung', $anggota->tanggal_bergabung ? date('Y-m-d', strtotime($anggota->tanggal_bergabung)) : '') }}">
                            </div>
                            @error('tanggal_bergabung')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                      {{-- input password --}}
                      <div id="password-field" class="w-full">
                        <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                        <div class="mt-2 relative">
                            <input type="password" name="password" id="password" placeholder="Kosongkan kalau anggota"
                                class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 pr-10">
                            
                            <button type="button" onclick="
                                const passwordInput = document.getElementById('password');
                                passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
                                this.querySelector('svg:first-child').classList.toggle('hidden');
                                this.querySelector('svg:last-child').classList.toggle('hidden');" 
                                class="absolute inset-y-0 right-0 flex items-center px-3">

                                {{-- eye --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400 hover:text-gray-600 hidden">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>

                                {{-- eye-slash --}}
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400 hover:text-gray-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                <span class="font-medium">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                    </div>
                  </div>
                </div>
              
                <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('anggotas.index') }}">
                  <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                  </a>
                  <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Update</button>
                </div>
              </form>
        </main>
    </body>
    </html>