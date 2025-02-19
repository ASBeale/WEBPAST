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
            <h2 class="text-2xl font-semibold leading-7 text-gray-900">Jadwal Kelompok {{ $kelompok->nama_kelompok }}</h2>
            <p class="mt-2 text-sm leading-6 text-gray-600">List Jadwal Kelompok</p>
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
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
              
            {{-- filter jadwal misa yang sudah ada kehadirannya dan yang belum ada kehadirannya --}}
            <div class="flex items-center mb-4">
                <input checked id="radio-belum" type="radio" value="belum" name="filter-radio" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="radio-belum" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Belum memiliki kehadiran</label>

                <input id="radio-sudah" type="radio" value="sudah" name="filter-radio" class="ml-4 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                <label for="radio-sudah" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Sudah memiliki kehadiran</label>
            </div>


            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-4 border">No.</th>
                            <th scope="col" class="px-4 py-4 border">Jenis Misa</th>
                            <th scope="col" class="px-4 py-4 border">Tanggal Jam Misa</th>
                        </tr>
                    </thead>
                    <tbody id="jadwal-tbody">
                        @php
                            $adajadwal = false;
                        @endphp
                    
                        @foreach ($jadwalmisas as $jadwalmisa)
                            <tr class="border-b dark:border-gray-700 kehadiran-{{ $jadwalmisa->kehadiranMisa()->exists() ? 'sudah' : 'belum' }}">
                                <td class="px-4 py-3 border">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 border">
                                    {{ $jadwalmisa->jenismisa ? $jadwalmisa->jenismisa->jenis_misa : 'Tidak ada jenis misa' }}
                                </td>
                                <td class="px-4 py-3 border">
                                    {{ \Carbon\Carbon::parse($jadwalmisa->tanggal_jam_misa)->format('d M Y (H:i)') }}
                                </td>
                            </tr>
                            @php
                                $adajadwal = true;
                            @endphp
                        @endforeach
                    
                        <tr id="jadwal-kosong" style="display: none;">
                            <td colspan="3" class="px-4 py-3 border text-center font-semibold">
                                Jadwal Habis / Belum ada Jadwal untuk kelompok {{ $kelompok->nama_kelompok }}
                            </td>
                        </tr>
                    </tbody>
                    
                </table>
            </div>
            
        </div>
    </div>
</section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
        // Mengambil elemen radio button
        const radioBelum = document.getElementById('radio-belum');
        const radioSudah = document.getElementById('radio-sudah');
        const rows = document.querySelectorAll('tbody tr.kehadiran-belum, tbody tr.kehadiran-sudah');
        const jadwalKosong = document.getElementById('jadwal-kosong');

        // Fungsi untuk menyembunyikan dan menampilkan jadwal berdasarkan filter
        function filterJadwal() {
            let adaJadwal = false;

            rows.forEach(row => {
                if (row.classList.contains('kehadiran-belum') && radioBelum.checked) {
                    row.style.display = '';
                    adaJadwal = true;
                } else if (row.classList.contains('kehadiran-sudah') && radioSudah.checked) {
                    row.style.display = '';
                    adaJadwal = true;
                } else {
                    row.style.display = 'none';
                }
            });

            // Tampilkan pesan jika tidak ada jadwal yang sesuai
            if (!adaJadwal) {
                jadwalKosong.style.display = '';
            } else {
                jadwalKosong.style.display = 'none';
            }
        }

        // Menambahkan event listener pada radio button
        radioBelum.addEventListener('change', filterJadwal);
        radioSudah.addEventListener('change', filterJadwal);

        // Panggil fungsi filterJadwal saat halaman pertama kali dimuat
        filterJadwal();
    });

    </script>
</body>
</html>

