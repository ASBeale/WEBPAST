<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link href="{{ asset('img/logo_past_icon.ico') }}" rel="icon">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pengurus PAST</title>
</head>
<body>
    <x-sidebar-pengurus></x-sidebar-pengurus>

    <main class="p-4 md:ml-64 h-auto pt-7 bg-white">

        <div class="text-center mb-4">
            <h2 class="text-2xl font-semibold leading-7 text-gray-900">Kehadiran Misa Kelompok {{ $kelompok->nama_kelompok }}</h2>
            <p class="mt-2 text-sm leading-6 text-gray-600">Kehadiran-kehadiran kelompok {{ $kelompok->nama_kelompok }} pada misa</p>
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
                    {{-- pencarian --}}
                    <form class="max-w-2xl" action="{{ route('kehadiran.index') }}" method="GET">
                        {{-- label --}}
                        <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                        <div class="relative">
                            {{-- logo --}}
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            {{-- input search --}}
                            <input type="search" id="search" name="search" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari Nama Misa / Pengisi Kehadiran" autocomplete="off"/>
                            {{-- tombol --}}
                            <button type="submit" class="text-white absolute end-2.5 bottom-2.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Cari</button>
                        </div>

                        <div id="date-range-picker" date-rangepicker class="flex items-center mt-3">
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                    </svg>
                                </div>
                                <input 
                                    id="datepicker-range-start" 
                                    name="start" 
                                    type="text" 
                                    autocomplete="off"
                                    value="{{ request('start') }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                    placeholder="Tanggal"
                                >
                            </div>
                            <span class="mx-4 text-gray-500">-</span>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                    </svg>
                                </div>
                                <input 
                                    id="datepicker-range-end" 
                                    name="end" 
                                    type="text" 
                                    autocomplete="off"
                                    value="{{ request('end') }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                                    placeholder="Tanggal"
                                >
                            </div>
                        </div>
                    </form>
                </div>
                <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">

                    <div class="flex items-center space-x-4">
                        <form id="createForm" action="{{ route('kehadiran.create') }}" method="GET" class="flex-none">
                            <input type="hidden" name="kelompokID" value="{{ $kelompok->kelompokID }}">
                            <button type="submit" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                </svg>
                                Kehadiran
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-4 border">No.</th>
                            <th scope="col" class="px-4 py-4 border">Nama Misa</th>
                            <th scope="col" class="px-4 py-4 border">Tanggal Jam Misa</th>
                            <th scope="col" class="px-4 py-4 border">Pengisi Kehadiran</th>
                            <th scope="col" class="px-4 py-4 border">Anggota Hadir</th>
                            <th scope="col" class="px-4 py-4 border">Anggota Izin</th>
                            <th scope="col" class="px-4 py-4 border">Anggota Alpha</th>
                            <th scope="col" class="px-4 py-4 border">Bantuan</th>
                            
                            <th scope="col" class="px-4 py-3 border text-center">Action
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($kehadiranmisas as $kehadiranmisa)
                            
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3 border">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 border">{{ $kehadiranmisa->jadwalMisa->jenisMisa->jenis_misa }}</td>
                            <td class="px-4 py-3 border">{{ \Carbon\Carbon::parse($kehadiranmisa->jadwalMisa->tanggal_jam_misa)->format('d M Y (H:i)') }}</td>
                            <td class="px-4 py-3 border">{{ $kehadiranmisa->pengisi_kehadiran_misa }}</td>

                            <!-- List Anggota yang Hadir -->
                            <td class="px-4 py-3 border">
                                <ul>
                                    {{-- PiMi --}}
                                    @foreach ($kehadiranmisa->kehadiranMisaAnggota as $kehadiranAnggota)
                                        @if ($kehadiranAnggota->status_kehadiran === 'hadir' && $kehadiranAnggota->keanggotaan->anggota->roleID == 4 && $kehadiranAnggota->keanggotaan->kelompokID === $kehadiranmisa->jadwalMisa->kelompokID)
                                            <li>- {{ $kehadiranAnggota->keanggotaan->anggota->nama }} ({{ $kehadiranAnggota->sebagai }})</li>
                                        @endif
                                    @endforeach
                                    {{-- Pengurus --}}
                                    @foreach ($kehadiranmisa->kehadiranMisaAnggota as $kehadiranAnggota)
                                        @if ($kehadiranAnggota->status_kehadiran === 'hadir' && $kehadiranAnggota->keanggotaan->anggota->roleID == 3 && $kehadiranAnggota->keanggotaan->kelompokID === $kehadiranmisa->jadwalMisa->kelompokID)
                                            <li>- {{ $kehadiranAnggota->keanggotaan->anggota->nama }} ({{ $kehadiranAnggota->sebagai }})</li>
                                        @endif
                                    @endforeach
                                    {{-- Anggota --}}
                                    @foreach ($kehadiranmisa->kehadiranMisaAnggota as $kehadiranAnggota)
                                        @if ($kehadiranAnggota->status_kehadiran === 'hadir' && $kehadiranAnggota->keanggotaan->anggota->roleID == 2 && $kehadiranAnggota->keanggotaan->kelompokID === $kehadiranmisa->jadwalMisa->kelompokID)
                                            <li>- {{ $kehadiranAnggota->keanggotaan->anggota->nama }} ({{ $kehadiranAnggota->sebagai }})</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </td>

                            <!-- List Anggota yang Izin -->
                            <td class="px-4 py-3 border">
                                <ul>
                                    {{-- PiMi --}}
                                    @foreach ($kehadiranmisa->kehadiranMisaAnggota as $kehadiranAnggota)
                                        @if ($kehadiranAnggota->status_kehadiran === 'ijin' && $kehadiranAnggota->keanggotaan->anggota->roleID == 4 )
                                            <li>- {{ $kehadiranAnggota->keanggotaan->anggota->nama }} ({{ $kehadiranAnggota->sebagai }})</li>
                                        @endif
                                    @endforeach
                                    {{-- Pengurus --}}
                                    @foreach ($kehadiranmisa->kehadiranMisaAnggota as $kehadiranAnggota)
                                        @if ($kehadiranAnggota->status_kehadiran === 'ijin' && $kehadiranAnggota->keanggotaan->anggota->roleID == 3 )
                                            <li>- {{ $kehadiranAnggota->keanggotaan->anggota->nama }} ({{ $kehadiranAnggota->sebagai }})</li>
                                        @endif
                                    @endforeach
                                    {{-- Anggota --}}
                                    @foreach ($kehadiranmisa->kehadiranMisaAnggota as $kehadiranAnggota)
                                        @if ($kehadiranAnggota->status_kehadiran === 'ijin' && $kehadiranAnggota->keanggotaan->anggota->roleID == 2 )
                                            <li>- {{ $kehadiranAnggota->keanggotaan->anggota->nama }} ({{ $kehadiranAnggota->sebagai }})</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </td>

                            <!-- List Anggota yang Alpha -->
                            <td class="px-4 py-3 border">
                                <ul>
                                    {{-- PiMi --}}
                                    @foreach ($kehadiranmisa->kehadiranMisaAnggota as $kehadiranAnggota)
                                        @if ($kehadiranAnggota->status_kehadiran === 'alpha' && $kehadiranAnggota->keanggotaan->anggota->roleID == 4 )
                                            <li>- {{ $kehadiranAnggota->keanggotaan->anggota->nama }} ({{ $kehadiranAnggota->sebagai }})</li>
                                        @endif
                                    @endforeach
                                    {{-- Pengurus --}}
                                    @foreach ($kehadiranmisa->kehadiranMisaAnggota as $kehadiranAnggota)
                                        @if ($kehadiranAnggota->status_kehadiran === 'alpha' && $kehadiranAnggota->keanggotaan->anggota->roleID == 3 )
                                            <li>- {{ $kehadiranAnggota->keanggotaan->anggota->nama }} ({{ $kehadiranAnggota->sebagai }})</li>
                                        @endif
                                    @endforeach
                                    {{-- Anggota --}}
                                    @foreach ($kehadiranmisa->kehadiranMisaAnggota as $kehadiranAnggota)
                                        @if ($kehadiranAnggota->status_kehadiran === 'alpha' && $kehadiranAnggota->keanggotaan->anggota->roleID == 2 )
                                            <li>- {{ $kehadiranAnggota->keanggotaan->anggota->nama }} ({{ $kehadiranAnggota->sebagai }})</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </td>

                            <!-- List Anggota bantuan -->
                            <td class="px-4 py-3 border">
                                <ul>
                                    @foreach ($kehadiranmisa->kehadiranMisaAnggota as $kehadiran)
                                        @if ($kehadiran->status_kehadiran === 'hadir' && $kehadiran->keanggotaan && $kehadiran->keanggotaan->anggota && $kehadiran->keanggotaan->kelompokID !== $kehadiranmisa->jadwalMisa->kelompokID)
                                            <li>- {{ $kehadiran->keanggotaan->anggota->nama }} ({{ $kehadiran->sebagai }})</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </td>
                            
                            <td class="px-4 py-3 flex items-center justify-center">
                                <button id="apple-imac-27-dropdown-button" data-dropdown-toggle="{{ $kehadiranmisa->kehadiranMisaID }}" class="inline-flex items-center text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 p-1.5 dark:hover-bg-gray-800 text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" type="button">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </button>
                                <div id="{{ $kehadiranmisa->kehadiranMisaID }}" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                    <ul class="py-1 text-sm" aria-labelledby="apple-imac-27-dropdown-button">
                                        <li>
                                            <a href="{{ route('kehadiran.edit', $kehadiranmisa->kehadiranMisaID) }}">
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
                                            <a href="/kehadiran/{{ $kehadiranmisa->kehadiranMisaID }}" >
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
                {{ $kehadiranmisas->links() }}
            </div>
            {{-- end of pagination --}}
        </div>
    </div>
</section>
    
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi date range picker
            const startDateInput = document.getElementById('datepicker-range-start');
            const endDateInput = document.getElementById('datepicker-range-end');
    
            // Ambil nilai awal dari input
            const initialStartDate = startDateInput.value;
            const initialEndDate = endDateInput.value;
    
            // Flatpickr configuration
            const startDatePicker = flatpickr(startDateInput, {
                dateFormat: "Y-m-d",
                maxDate: "today",
                defaultDate: initialStartDate || null,
                onChange: function(selectedDates, dateStr, instance) {
                    endDatePicker.set('minDate', dateStr);
                }
            });
    
            const endDatePicker = flatpickr(endDateInput, {
                dateFormat: "Y-m-d",
                maxDate: "today",
                defaultDate: initialEndDate || null,
                onChange: function(selectedDates, dateStr, instance) {
                    startDatePicker.set('maxDate', dateStr);
                }
            });
    
            // Optional: Tambahkan validasi pada form submission
            const form = startDateInput.closest('form');
            form.addEventListener('submit', function(e) {
                if (startDateInput.value && endDateInput.value) {
                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(endDateInput.value);
    
                    if (startDate > endDate) {
                        e.preventDefault();
                        alert('Tanggal mulai tidak boleh lebih besar dari tanggal selesai');
                    }
                }
            });
        });
    </script>
</body>
</html>
