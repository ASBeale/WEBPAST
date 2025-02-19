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

    <main class="p-4 md:ml-64 h-auto pt-7 bg-white">

        <div class="text-center mb-4">
            <h2 class="text-2xl font-semibold leading-7 text-gray-900">Role</h2>
            <p class="mt-2 text-sm leading-6 text-gray-600">List Role Putra Altar Santo Tarsisius</p>
        </div>

        @if (session()->has('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <span class="font-medium">Berhasil!</span> {{ session('success') }}
        </div>
        @endif
        @if (session()->has('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <span class="font-medium">Error!</span> {{ session('error') }}
        </div>
        @endif

        <!-- Start block -->
<section class="bg-white dark:bg-gray-900 p-3 sm:p-5 antialiased">
    <div class="mx-auto max-w-screen-7xl px-4 lg:px-12">
        <!-- Start coding here -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-1/2">

                    <form action="{{ route('role.index') }}" method="GET" class="max-w-lg">
                        <div class="flex">
                            <button id="dropdown-button" data-dropdown-toggle="dropdown" 
                                    class="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-s-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600" 
                                    type="button">
                                Filter 
                                <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                                </svg>
                            </button>
                            
                            <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdown-button">
                                    <li class="flex items-center px-4 py-2">
                                        <input id="Semua" type="radio" name="roleID" value=""
                                            {{ request('roleID') === '' ? 'checked' : '' }}
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500" />
                                        <label for="Semua" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Semua
                                        </label>
                                    </li>
                                    <li class="flex items-center px-4 py-2">
                                        <input id="admin" type="radio" name="roleID" value="1"
                                            {{ request('roleID') === '1' ? 'checked' : '' }}
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500" />
                                        <label for="admin" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Admin
                                        </label>
                                    </li>
                                    <li class="flex items-center px-4 py-2">
                                        <input id="anggota" type="radio" name="roleID" value="2"
                                            {{ request('roleID') === '2' ? 'checked' : '' }}
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500" />
                                        <label for="anggota" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Anggota
                                        </label>
                                    </li>
                                    <li class="flex items-center px-4 py-2">
                                        <input id="pengurus" type="radio" name="roleID" value="3"
                                            {{ request('roleID') === '3' ? 'checked' : '' }}
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500" />
                                        <label for="pengurus" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Pengurus
                                        </label>
                                    </li>
                                    <li class="flex items-center px-4 py-2">
                                        <input id="pimi" type="radio" name="roleID" value="4"
                                            {{ request('roleID') === '4' ? 'checked' : '' }}
                                            class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500" />
                                        <label for="pimi" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            PiMi
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            
                            <div class="relative w-full">
                                <input type="search" name="search" id="search-dropdown" 
                                       value="{{ request('search', '') }}"
                                       class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-s-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500" 
                                       placeholder="Cari Anggota" />
                                
                                <button type="submit" class="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                    <span class="sr-only">Search</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">

                   {{-- filter? --}}

                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-4 border">No.</th>
                            <th scope="col" class="px-4 py-4 border">Nama</th>
                            <th scope="col" class="px-4 py-3 border">Role</th>

                            <th scope="col" class="px-4 py-3 border text-center">Action
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($anggotas as $anggota)
                        
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3 border"> {{ $loop->iteration }}</td>
                            <td class="px-4 py-3 border">{{ $anggota->nama }}</td>
                            <td class="px-4 py-3 border">{{ $anggota->role->nama_role }}</td>


                            <td class="px-4 py-3 flex items-center justify-center">
                                <button id="apple-imac-27-dropdown-button" data-dropdown-toggle="{{ $anggota->anggotaID }}" class="inline-flex items-center text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 p-1.5 dark:hover-bg-gray-800 text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" type="button">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </button>
                                <div id="{{ $anggota->anggotaID }}" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                    <ul class="py-1 text-sm" aria-labelledby="apple-imac-27-dropdown-button">
                                        <li>
                                            <a href="{{ route('role.edit', $anggota->anggotaID) }}">
                                            <button type="button" data-modal-target="updateProductModal" data-modal-toggle="updateProductModal" class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                                </svg>
                                                Edit
                                            </button>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('role.show', $anggota->anggotaID) }}">
                                                <button type="button" class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                                
                                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                Preview
                                            </button>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- pagination --}}
            <div class="mt-4">
                {{ $anggotas->links() }}
            </div>
            {{-- end of pagination --}}
        </div>
    </div>
</section>
    </main>
</body>
</html>
