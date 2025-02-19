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
    
      <main class="p-4 md:ml-44 h-auto pt-0 bg-gray-50">

      <div class="bg-gray-50 h-screen w-full dark:bg-gray-700 flex justify-center items-center">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:py-20 lg:px-8">
          
            @if (session()->has('error'))
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <span class="font-medium">Error!</span> {{ session('error') }}
            </div>
            @endif

            <h2 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl dark:text-white">Dashboard Putra Altar Santo Tarsisius</h2>
                        
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 mt-4">
              <div class="bg-white overflow-hidden shadow sm:rounded-lg dark:bg-gray-900">
                <div class="px-4 py-5 sm:p-6">
                  <dl>
                    <dt class="text-sm leading-5 font-medium text-gray-500 truncate dark:text-gray-400">Putra Altar</dt>
                            <dd class="mt-1 text-3xl leading-9 font-semibold text-indigo-600 dark:text-indigo-400">{{ $jumlahAnggota }}
                            </dd>
                          </dl>
                        </div>
                      </div>
                <div class="bg-white overflow-hidden shadow sm:rounded-lg dark:bg-gray-900">
                    <div class="px-4 py-5 sm:p-6">
                      <dl>
                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate dark:text-gray-400">Pengurus</dt>
                            <dd class="mt-1 text-3xl leading-9 font-semibold text-indigo-600 dark:text-indigo-400">{{ $jumlahPengurus }}</dd>
                        </dl>
                    </div>
                  </div>
                  <div class="bg-white overflow-hidden shadow sm:rounded-lg dark:bg-gray-900">
                    <div class="px-4 py-5 sm:p-6">
                      <dl>
                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate dark:text-gray-400">PiMi Pendamping</dt>
                            <dd class="mt-1 text-3xl leading-9 font-semibold text-indigo-600 dark:text-indigo-400">{{ $jumlahPiMi }}
                            </dd>
                          </dl>
                    </div>
                  </div>
                  <div class="bg-white overflow-hidden shadow sm:rounded-lg dark:bg-gray-900">
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate dark:text-gray-400">Admin</dt>
                            <dd class="mt-1 text-3xl leading-9 font-semibold text-indigo-600 dark:text-indigo-400">{{ $jumlahAdmin }}</dd>
                        </dl>
                    </div>
                </div>
                  <div class="bg-white overflow-hidden shadow sm:rounded-lg dark:bg-gray-900">
                    <div class="px-4 py-5 sm:p-6">
                        <dl>
                            <dt class="text-sm leading-5 font-medium text-gray-500 truncate dark:text-gray-400">Total</dt>
                            <dd class="mt-1 text-3xl leading-9 font-semibold text-indigo-600 dark:text-indigo-400">{{ $jumlahSemua }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
      </main>
</body>
</html>

