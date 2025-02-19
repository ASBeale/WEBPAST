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
        <form method="post" action="/kehadirankegiatan">
            @csrf
            <div class="space-y-12">
              
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="text-center mb-4">
                        <h2 class="text-2xl font-semibold leading-7 text-gray-900">Kehadiran Kegiatan</h2>
                        <p class="mt-2 text-sm leading-6 text-gray-600">Tambah kehadiran kegiatan PAST</p>
                    </div>

                    @if (session()->has('error'))
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                        <span class="font-medium">Error!</span> {{ session('error') }}
                    </div>
                    @endif

                    <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-1">
                        <div class="mt-1 space-y-6">

                        {{-- Pilih tanggal dan jam kegiatan yang sudah di assign admin --}}
                        <div class="w-full">
                            <label for="jadwalKegiatanID" class="block text-sm font-medium leading-6 text-gray-900">Kegiatan, Tanggal dan Jam Kegiatan</label>
                            <select name="jadwalKegiatanID" id="jadwalKegiatanID"
                                class="mt-2 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('jadwalKegiatanID') is-invalid @enderror">
                                <option value="" disabled {{ old('jadwalKegiatanID') == '' ? 'selected' : '' }}>Pilih Kegiatan, Tanggal dan Jam Kegiatan</option>
                                @foreach ($jadwalkegiatans as $jadwalkegiatan)
                                    <option value="{{ $jadwalkegiatan->jadwalKegiatanID }}" {{ old('jadwalKegiatanID') == $jadwalkegiatan->jadwalKegiatanID ? 'selected' : '' }}>
                                        ({{ $jadwalkegiatan->jeniskegiatan->jenis_kegiatan }}) {{ $jadwalkegiatan->judul_kegiatan }} ({{ $jadwalkegiatan->tanggal_jam_mulai_kegiatan }} sampai  {{ $jadwalkegiatan->tanggal_jam_selesai_kegiatan }})
                                    </option>
                                @endforeach
                            </select>
                            @error('jadwalKegiatanID')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        {{-- Input nama pengisi kehadiran --}}
                        <div class="w-full">
                            <label for="pengisi_kehadiran_kegiatan" class="block text-sm font-medium leading-6 text-gray-900">Pengisi Kehadiran</label>
                            <div class="mt-2">
                                <input type="text" name="pengisi_kehadiran_kegiatan" id="pengisi_kehadiran_kegiatan"
                                    class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('pengisi_kehadiran_kegiatan') is-invalid @enderror"
                                    value="{{ old('pengisi_kehadiran_kegiatan', auth()->user()->nama . ' (' . auth()->user()->role->nama_role . ')') }}" readonly>
                            </div>
                            @error('pengisi_kehadiran_kegiatan')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                       
                        {{-- Checklist anggota --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Pilih Anggota yang Hadir</label>
                            
                            {{-- Tambahkan input pencarian --}}
                            <div class="mt-2 mb-2">
                                <input type="text" id="anggota-search" placeholder="Cari anggota..." class="w-full px-2 py-1 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="mt-2 border rounded-lg">
                                <div class="max-h-80 overflow-y-auto p-2">
                                    {{-- Daftar PiMi --}}
                                    <div class="mb-2">
                                        <h3 class="text-sm font-semibold text-gray-600">PiMi</h3>
                                        @foreach ($anggotas as $anggota)
                                            @if ($anggota->status_anggota == 1 && $anggota->roleID == 4)
                                                <div class="anggota-item flex items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-600" data-nama="{{ strtolower($anggota->nama) }}">
                                                    <input type="checkbox" name="anggota_ids[]" value="{{ $anggota->anggotaID }}" id="anggota_{{ $anggota->anggotaID }}" 
                                                        {{ in_array($anggota->anggotaID, old('anggota_ids', [])) ? 'checked' : '' }} 
                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                                    <label for="anggota_{{ $anggota->anggotaID }}" class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                        {{ $anggota->nama }} ({{ $anggota->role->nama_role }})
                                                    </label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    {{-- Daftar Pengurus --}}
                                    <div class="mb-2">
                                        <h3 class="text-sm font-semibold text-gray-600">Pengurus</h3>
                                        @foreach ($anggotas as $anggota)
                                            @if ($anggota->status_anggota == 1 && $anggota->roleID == 3)
                                                <div class="anggota-item flex items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-600" data-nama="{{ strtolower($anggota->nama) }}">
                                                    <input type="checkbox" name="anggota_ids[]" value="{{ $anggota->anggotaID }}" id="anggota_{{ $anggota->anggotaID }}" 
                                                        {{ in_array($anggota->anggotaID, old('anggota_ids', [])) ? 'checked' : '' }} 
                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                                    <label for="anggota_{{ $anggota->anggotaID }}" class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                        {{ $anggota->nama }} ({{ $anggota->role->nama_role }})
                                                    </label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>

                                    {{-- Daftar Anggota --}}
                                    <div class="mb-2">
                                        <h3 class="text-sm font-semibold text-gray-600">Anggota</h3>
                                        @foreach ($anggotas as $anggota)
                                            @if ($anggota->status_anggota == 1 && $anggota->roleID == 2)
                                                <div class="anggota-item flex items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-600" data-nama="{{ strtolower($anggota->nama) }}">
                                                    <input type="checkbox" name="anggota_ids[]" value="{{ $anggota->anggotaID }}" id="anggota_{{ $anggota->anggotaID }}" 
                                                        {{ in_array($anggota->anggotaID, old('anggota_ids', [])) ? 'checked' : '' }} 
                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                                    <label for="anggota_{{ $anggota->anggotaID }}" class="w-full ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                                        {{ $anggota->nama }} ({{ $anggota->role->nama_role }})
                                                    </label>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @error('anggota_ids')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        </div>
                    </div>
                </div>
              
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="/kehadirankegiatan">
                    <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                    </a>
                    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                </div>
            </form>
        </main>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('anggota-search');
            const anggotaItems = document.querySelectorAll('.anggota-item');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();

                anggotaItems.forEach(item => {
                    const nama = item.getAttribute('data-nama');
                    
                    if (nama.includes(searchTerm)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
        </script>
    </body>
    </html>