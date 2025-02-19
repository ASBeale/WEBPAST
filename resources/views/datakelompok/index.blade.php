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
            <h2 class="text-2xl font-semibold leading-7 text-gray-900">Data Kelompok {{ $namaKelompok }}</h2>
            <p class="mt-2 text-sm leading-6 text-gray-600">List Anggota Kelompok {{ $namaKelompok }}</p>
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

        <section class="bg-white dark:bg-gray-900 p-3 sm:p-5 antialiased">
            <div class="mx-auto max-w-screen-7xl px-4 lg:px-12">
                <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <div class="space-y-4 mb-5">
                            {{-- PiMi Kelompok --}}
                            <div>
                                <h3 class="text-lg font-semibold mb-2 ml-5">PiMi:</h3>
                                <p class="ml-5">
                                    @php
                                        $pimiNames = $anggotas
                                            ->filter(function($item) {
                                                return $item->anggota->roleID == 4;
                                            })
                                            ->pluck('anggota.nama')
                                            ->implode(', ');
                                    @endphp
                                    {{ $pimiNames ?: 'Tidak ada PiMi' }}
                                </p>
                            </div>
                        
                            {{-- Pengurus Kelompok --}}
                            <div>
                                <h3 class="text-lg font-semibold mb-2 ml-5">Pengurus:</h3>
                                <p class="ml-5">
                                    @php
                                        $pengurusNames = $anggotas
                                            ->filter(function($item) {
                                                return $item->anggota->roleID == 3;
                                            })
                                            ->pluck('anggota.nama')
                                            ->implode(', ');
                                    @endphp
                                    {{ $pengurusNames ?: 'Tidak ada Pengurus' }}
                                </p>
                            </div>
                        </div>

                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-4 border">No.</th>
                                    <th scope="col" class="px-4 py-4 border">Nama</th>
                                    <th scope="col" class="px-4 py-3 border">Tanggal Lahir</th>
                                    <th scope="col" class="px-4 py-3 border">No. Telepon</th>
                                    <th scope="col" class="px-4 py-3 border">Nama Orang Tua</th>
                                    <th scope="col" class="px-4 py-3 border">No. Telepon Orang Tua</th>
                                    <th scope="col" class="px-4 py-3 border">Role</th>
                                    <th scope="col" class="px-4 py-3 border">Hadir</th>
                                    <th scope="col" class="px-4 py-3 border">Ijin</th>
                                    <th scope="col" class="px-4 py-3 border">Alpha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $hitung = 1; @endphp
                                @foreach ($anggotas as $keanggotaan)
                                    @php 
                                        $anggota = $keanggotaan->anggota; 
                                        $role = $anggota->role->nama_role;
                                    @endphp
                                    @if (in_array($role, ['Pengurus', 'Anggota']))
                                        <tr class="border-b dark:border-gray-700">
                                            <td class="px-4 py-3 border">{{ $hitung }}</td>
                                            <td class="px-4 py-3 border">{{ $anggota->nama }}</td>
                                            <td class="px-4 py-3 border">{{ $anggota->DoB->format('d M Y') }}</td>
                                            <td class="px-4 py-3 border">{{ $anggota->TelpNo }}</td>
                                            <td class="px-4 py-3 border">{{ $anggota->ortu_nama ?? '-' }}</td>
                                            <td class="px-4 py-3 border">{{ $anggota->ortu_telp_no ?? '-' }}</td>
                                            <td class="px-4 py-3 border">{{ $role }}</td>
                                            <td class="px-4 py-3 border text-center">{{ $keanggotaan->jumlah_hadir }}</td>

                                             @if ($keanggotaan->jumlah_ijin >= 3)
                                                <td class="px-4 py-3 bg-yellow-300 text-slate-800 border text-center">{{ $keanggotaan->jumlah_ijin }}</td>
                                            @else
                                                <td class="px-4 py-3 border text-center">{{ $keanggotaan->jumlah_ijin }}</td>
                                            @endif

                                            @if ($keanggotaan->jumlah_alpha >= 1)
                                                <td class="px-4 py-3 bg-red-800 text-white border text-center">{{ $keanggotaan->jumlah_alpha }}</td>
                                            @else
                                                <td class="px-4 py-3 border text-center">{{ $keanggotaan->jumlah_alpha }}</td>
                                            @endif
                                        </tr>
                                        @php $hitung++; @endphp
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <p class="mb-2 ml-5 mt-3">Keterangan :</p>
                        <p class="mb-2 ml-5">- Kuning (Melebihi dari 3x Ijin)</p>
                        <p class="mb-2 ml-5">- Merah (Terdapat Alpha)</p>
                        
                    </div>   
                </div>
            </div>
        </section>
    </main>
</body>
</html>