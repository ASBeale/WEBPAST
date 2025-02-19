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

    <main class="p-4 md:ml-64 h-auto pt-20">
        <div class="text-center mb-4">
            <h2 class="text-2xl font-semibold leading-7 text-gray-900">Kehadiran Kegiatan</h2>
            <p class="mt-2 text-sm leading-6 text-gray-600">Detail Kehadiran Kegiatan PAST</p>
        </div>

                @if (session()->has('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-medium">Error!</span> {{ session('error') }}
                </div>
                @endif
                
        <div class="mt-6 border-t border-gray-100">
            <dl class="divide-y divide-gray-100">
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">ID</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $kehadirankegiatan->kehadiranKegiatanID }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Judul Kegiatan</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $kehadirankegiatan->jadwalKegiatan->judul_kegiatan }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Jenis Kegiatan</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $kehadirankegiatan->jadwalKegiatan->jenisKegiatan->jenis_kegiatan }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Tanggal dan Jam Mulai Kegiatan</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ \Carbon\Carbon::parse($kehadirankegiatan->jadwalKegiatan->tanggal_jam_mulai_kegiatan)->format('d M Y (H:i)') }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Tanggal dan Jam Selesai Kegiatan</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ \Carbon\Carbon::parse($kehadirankegiatan->jadwalKegiatan->tanggal_jam_selesai_kegiatan)->format('d M Y (H:i)') }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Pengisi Kehadiran</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $kehadirankegiatan->pengisi_kehadiran_kegiatan }}</dd>
                </div>
                <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">List Anggota Hadir ({{ $kehadirankegiatan->kehadiranKegiatanAnggota->where('status_hadir', 'hadir')->count() }})</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        <ul>
                            {{-- PiMi --}}
                            @foreach ($kehadirankegiatan->kehadiranKegiatanAnggota as $kegiatanAnggota)
                                @if ($kegiatanAnggota->anggota->roleID == 4)
                                    <li>{{ $kegiatanAnggota->anggota->nama }} ({{ $kegiatanAnggota->anggota->role->nama_role }})</li>
                                @endif
                            @endforeach

                            {{-- Pengurus --}}
                            @foreach ($kehadirankegiatan->kehadiranKegiatanAnggota as $kegiatanAnggota)
                                @if ($kegiatanAnggota->anggota->roleID == 3)
                                    <li>{{ $kegiatanAnggota->anggota->nama }} ({{ $kegiatanAnggota->anggota->role->nama_role }})</li>
                                @endif
                            @endforeach

                            {{-- Anggota --}}
                            @foreach ($kehadirankegiatan->kehadiranKegiatanAnggota as $kegiatanAnggota)
                                @if ($kegiatanAnggota->anggota->roleID == 2)
                                    <li>{{ $kegiatanAnggota->anggota->nama }} ({{ $kegiatanAnggota->anggota->role->nama_role }})</li>
                                @endif
                            @endforeach
                     
                        </ul>
                    </dd>
                </div>
            </dl>
        </div>
              
        <div class="flex justify-center m-5">
            <div class="mt-5 flex lg:ml-4 lg:mt-0">
                <span class="ml-3">
                    <a href="/kehadirankegiatan">
                        <button type="button" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m15 19-7-7 7-7"/>
                            </svg>
                            Back
                        </button>
                    </a>
                </span>

                <span>
                    <a href="/kehadirankegiatan/{{ $kehadirankegiatan->kehadiranKegiatanID }}/edit">
                        <button type="button" class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28"/>
                            </svg>
                            Edit
                        </button>
                    </a>
                </span>

                <span>
                    <form action="/kehadirankegiatan/{{ $kehadirankegiatan->kehadiranKegiatanID }}" method="post">
                        @method('delete')
                        @csrf
                        <button type="submit" class="inline-flex items-center rounded-md bg-red-500 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50" onclick="return confirm('Hapus data?')">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                </span>
            </div>
        </div>
    </main>
</body>
</html>