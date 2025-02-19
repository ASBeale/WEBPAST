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

        <div class="text-center mb-4">
            <h2 class="text-2xl font-semibold leading-7 text-gray-900">Kehadiran Kegiatan</h2>
            <p class="mt-2 text-sm leading-6 text-gray-600">Kehadiran-kehadiran kegiatan PAST</p>
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
                    <form action="{{ route('kehadirankegiatan.index') }}" method="GET" class="max-w-lg">
                        <div class="flex">
                            <!-- Dropdown Jenis Kegiatan -->
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
                                        <input id="semua_jenis" type="radio" name="jenis_kegiatan" value=""
                                               {{ request('jenis_kegiatan') === '' ? 'checked' : '' }}
                                               class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500" />
                                        <label for="semua_jenis" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            Semua Jenis
                                        </label>
                                    </li>
                                    
                                    @foreach($jeniskegiatans as $jenis)
                                    <li class="flex items-center px-4 py-2">
                                        <input id="jenis_{{ $jenis->jenisKegiatanID }}" type="radio" name="jenis_kegiatan" 
                                               value="{{ $jenis->jenisKegiatanID }}"
                                               {{ request('jenis_kegiatan') == $jenis->jenisKegiatanID ? 'checked' : '' }}
                                               class="w-4 h-4 bg-gray-100 border-gray-300 rounded text-primary-600 focus:ring-primary-500" />
                                        <label for="jenis_{{ $jenis->jenisKegiatanID }}" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $jenis->jenis_kegiatan }}
                                        </label>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            
                            <div class="relative w-full">
                                <input type="search" name="search" id="search-dropdown" 
                                       value="{{ request('search', '') }}"
                                       class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-e-lg border-s-gray-50 border-s-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-s-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500" 
                                       placeholder="Cari Kehadiran Kegiatan" />
                                
                                <button type="submit" class="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                    </svg>
                                    <span class="sr-only">Search</span>
                                </button>
                            </div>
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

                    <a href="/kehadirankegiatan/create">
                        <button type="button" id="createProductModalButton" data-modal-target="createProductModal" data-modal-toggle="createProductModal" class="flex items-center justify-center text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Kehadiran
                        </button>
                        </a>       
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-4 border">No.</th>
                            <th scope="col" class="px-4 py-4 border">Judul Kegiatan</th>
                            <th scope="col" class="px-4 py-4 border">Jenis Kegiatan</th>
                            <th scope="col" class="px-4 py-4 border">Tanggal dan Jam Mulai Kegiatan</th>
                            <th scope="col" class="px-4 py-4 border">Tanggal dan Jam Selesai Kegiatan</th>
                            <th scope="col" class="px-4 py-4 border">List Anggota yang mengikuti

                            <th scope="col" class="px-4 py-3 border text-center">Action
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($kehadirankegiatans as $kehadirankegiatan)
                            
                        <tr class="border-b dark:border-gray-700">
                            <td class="px-4 py-3 border"> {{ $loop->iteration }}</td>
                            <td class="px-4 py-3 border">{{ $kehadirankegiatan->jadwalKegiatan->judul_kegiatan}}</td>
                            <td class="px-4 py-3 border">{{ $kehadirankegiatan->jadwalKegiatan->jenisKegiatan->jenis_kegiatan }}</td>
                            <td class="px-4 py-3 border">{{ \Carbon\Carbon::parse($kehadirankegiatan->jadwalKegiatan->tanggal_jam_mulai_kegiatan)->format('d M Y (H:i)') }}</td>
                            <td class="px-4 py-3 border">{{ \Carbon\Carbon::parse($kehadirankegiatan->jadwalKegiatan->tanggal_jam_selesai_kegiatan)->format('d M Y (H:i)') }}</td>
                            <td class="px-4 py-3 border">
                                @if($kehadirankegiatan->kehadiranKegiatanAnggota->isEmpty())
                                    Tidak ada anggota
                                @else
                                    <ul>
                                        {{-- PiMi --}}
                                        @foreach ($kehadirankegiatan->kehadiranKegiatanAnggota as $kehadiranAnggota)
                                            @if ($kehadiranAnggota->anggota->roleID == 4)
                                                <li>- {{ $kehadiranAnggota->anggota->nama }} ({{ $kehadiranAnggota->anggota->role->nama_role }})</li>
                                            @endif
                                        @endforeach
                                        {{-- Pengurus --}}
                                        @foreach ($kehadirankegiatan->kehadiranKegiatanAnggota as $kehadiranAnggota)
                                            @if ($kehadiranAnggota->anggota->roleID == 3)
                                                <li>- {{ $kehadiranAnggota->anggota->nama }} ({{ $kehadiranAnggota->anggota->role->nama_role }})</li>
                                            @endif
                                        @endforeach
                                        {{-- Anggota --}}
                                        @foreach ($kehadirankegiatan->kehadiranKegiatanAnggota as $kehadiranAnggota)
                                            @if ($kehadiranAnggota->anggota->roleID == 2)
                                                <li>- {{ $kehadiranAnggota->anggota->nama }} ({{ $kehadiranAnggota->anggota->role->nama_role }})</li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                    
                            
                            <td class="px-4 py-3 flex items-center justify-center">
                                <button id="apple-imac-27-dropdown-button" data-dropdown-toggle="{{ $kehadirankegiatan->kehadiranKegiatanID }}" class="inline-flex items-center text-sm font-medium hover:bg-gray-100 dark:hover:bg-gray-700 p-1.5 dark:hover-bg-gray-800 text-center text-gray-500 hover:text-gray-800 rounded-lg focus:outline-none dark:text-gray-400 dark:hover:text-gray-100" type="button">
                                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                    </svg>
                                </button>
                                <div id="{{ $kehadirankegiatan->kehadiranKegiatanID }}" class="hidden z-10 w-44 bg-white rounded divide-y divide-gray-100 shadow dark:bg-gray-700 dark:divide-gray-600">
                                    <ul class="py-1 text-sm" aria-labelledby="apple-imac-27-dropdown-button">
                                        <li>
                                            <a href="/kehadirankegiatan/{{ $kehadirankegiatan->kehadiranKegiatanID }}/edit" >
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
                                            <a href="/kehadirankegiatan/{{ $kehadirankegiatan->kehadiranKegiatanID }}" >
                                                <button type="button" class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white text-gray-700 dark:text-gray-200">
                                                
                                                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                Preview
                                            </button>
                                            </a>
                                        </li>
                                        <li>
                                            <form action="/kehadirankegiatan/{{ $kehadirankegiatan->kehadiranKegiatanID }}" method="post">
                                                @method('delete')
                                                @csrf
                                            <button type="submit" data-modal-target="deleteModal" data-modal-toggle="deleteModal" class="flex w-full items-center py-2 px-4 hover:bg-gray-100 dark:hover:bg-gray-600 text-red-500 dark:hover:text-red-400" onclick="return confirm('Hapus data?')">
                                                <svg class="w-4 h-4 mr-2" viewbox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" fill="currentColor" d="M6.09922 0.300781C5.93212 0.30087 5.76835 0.347476 5.62625 0.435378C5.48414 0.523281 5.36931 0.649009 5.29462 0.798481L4.64302 2.10078H1.59922C1.36052 2.10078 1.13161 2.1956 0.962823 2.36439C0.79404 2.53317 0.699219 2.76209 0.699219 3.00078C0.699219 3.23948 0.79404 3.46839 0.962823 3.63718C1.13161 3.80596 1.36052 3.90078 1.59922 3.90078V12.9008C1.59922 13.3782 1.78886 13.836 2.12643 14.1736C2.46399 14.5111 2.92183 14.7008 3.39922 14.7008H10.5992C11.0766 14.7008 11.5344 14.5111 11.872 14.1736C12.2096 13.836 12.3992 13.3782 12.3992 12.9008V3.90078C12.6379 3.90078 12.8668 3.80596 13.0356 3.63718C13.2044 3.46839 13.2992 3.23948 13.2992 3.00078C13.2992 2.76209 13.2044 2.53317 13.0356 2.36439C12.8668 2.1956 12.6379 2.10078 12.3992 2.10078H9.35542L8.70382 0.798481C8.62913 0.649009 8.5143 0.523281 8.37219 0.435378C8.23009 0.347476 8.06631 0.30087 7.89922 0.300781H6.09922ZM4.29922 5.70078C4.29922 5.46209 4.39404 5.23317 4.56282 5.06439C4.73161 4.8956 4.96052 4.80078 5.19922 4.80078C5.43791 4.80078 5.66683 4.8956 5.83561 5.06439C6.0044 5.23317 6.09922 5.46209 6.09922 5.70078V11.1008C6.09922 11.3395 6.0044 11.5684 5.83561 11.7372C5.66683 11.906 5.43791 12.0008 5.19922 12.0008C4.96052 12.0008 4.73161 11.906 4.56282 11.7372C4.39404 11.5684 4.29922 11.3395 4.29922 11.1008V5.70078ZM8.79922 4.80078C8.56052 4.80078 8.33161 4.8956 8.16282 5.06439C7.99404 5.23317 7.89922 5.46209 7.89922 5.70078V11.1008C7.89922 11.3395 7.99404 11.5684 8.16282 11.7372C8.33161 11.906 8.56052 12.0008 8.79922 12.0008C9.03791 12.0008 9.26683 11.906 9.43561 11.7372C9.6044 11.5684 9.69922 11.3395 9.69922 11.1008V5.70078C9.69922 5.46209 9.6044 5.23317 9.43561 5.06439C9.26683 4.8956 9.03791 4.80078 8.79922 4.80078Z" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
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
                {{ $kehadirankegiatans->links() }}
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
