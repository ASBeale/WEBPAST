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

    <main class="p-4 md:ml-64 h-auto pt-7">
        <form method="post" action="/kehadiran/{{ $kehadiranMisa->kehadiranMisaID }}">
            @method('put')
            @csrf
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="text-center mb-4">
                        <h2 class="text-2xl font-semibold leading-7 text-gray-900">Edit Kehadiran Kelompok {{ $kelompok->nama_kelompok }}</h2>
                        <p class="mt-2 text-sm leading-6 text-gray-600">Edit Kehadiran Misa Kelompok</p>
                    </div>

                    @if (session()->has('error'))
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                        <span class="font-medium">Error!</span> {{ session('error') }}
                    </div>
                    @endif

                    <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-1">
                        <div class="mt-1 space-y-6">

                            {{-- Jadwal Misa (Statis) --}}
                            <div class="w-full">
                                <label class="block text-sm font-medium leading-6 text-gray-900">Jadwal Misa</label>
                                <div class="mt-2">
                                    <input type="text" 
                                        class="block w-full rounded-md border-0 py-1.5 px-3 text-sm text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 bg-gray-100"
                                        value="{{ $kehadiranMisa->jadwalMisa->jenisMisa->jenis_misa }} - {{ $kehadiranMisa->jadwalMisa->tanggal_jam_misa }}" 
                                        readonly>
                                </div>
                            </div>

                            {{-- Input nama pengisi kehadiran --}}
                            <div class="w-full">
                                <label for="pengisi_kehadiran_misa" class="block text-sm font-medium leading-6 text-gray-900">Pengisi Kehadiran</label>
                                <div class="mt-2">
                                    <input type="text" name="pengisi_kehadiran_misa" id="pengisi_kehadiran_misa"
                                        class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('pengisi_kehadiran_misa') is-invalid @enderror"
                                        value="{{ old('pengisi_kehadiran_misa', auth()->user()->nama . ' (' . auth()->user()->role->nama_role . ')') }}" readonly>
                                </div>
                                @error('pengisi_kehadiran_misa')
                                    <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                        <span class="font-medium">{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <!-- PiMi Section -->
                            <div class="mb-6">
                                <h3 class="text-sm font-medium leading-6 text-gray-900 mb-2">PiMi</h3>
                                <div class="w-full h-40 overflow-y-auto rounded-md border border-gray-300 p-2">
                                    @foreach($keanggotaans as $keanggotaan)
                                        @if($keanggotaan->anggota->roleID == 4 && $keanggotaan->periodeID == $periodeAktif->periodeID) 
                                            <div class="flex items-center p-2">
                                                <span class="block w-1/3 text-sm font-medium text-gray-900">
                                                    {{ $keanggotaan->anggota->nama }}
                                                </span>
                                                
                                                <input type="hidden" name="keanggotaanID[]" value="{{ $keanggotaan->keanggotaanID }}">
                                                
                                                <select name="status_kehadiran[]" class="mt-2 w-1/3 rounded-md border border-gray-300 py-1.5 px-3 text-sm">
                                                    @php
                                                        $existingKehadiran = $kehadiranMisa->kehadiranMisaAnggota
                                                            ->where('keanggotaanID', $keanggotaan->keanggotaanID)
                                                            ->first();
                                                    @endphp
                                                    <option value="" disabled {{ is_null($existingKehadiran) ? 'selected' : '' }}>Pilih Status</option>
                                                    <option value="hadir" {{ $existingKehadiran && $existingKehadiran->status_kehadiran == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                                    <option value="ijin" {{ $existingKehadiran && $existingKehadiran->status_kehadiran == 'ijin' ? 'selected' : '' }}>Izin</option>
                                                    <option value="alpha" {{ $existingKehadiran && $existingKehadiran->status_kehadiran == 'alpha' ? 'selected' : '' }}>Alpha</option>
                                                </select>
                                                
                                                <input type="text" name="alasan_ijin[]" 
                                                    class="mt-2 w-1/3 rounded-md border border-gray-300 py-1.5 px-3 text-sm"
                                                    placeholder="Alasan jika izin"
                                                    value="{{ $existingKehadiran ? $existingKehadiran->alasan_ijin : '' }}">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <!-- Pengurus Section -->
                            <div class="mb-6">
                                <h3 class="text-sm font-medium leading-6 text-gray-900 mb-2">Pengurus</h3>
                                <div class="w-full h-40 overflow-y-auto rounded-md border border-gray-300 p-2">
                                    @foreach($keanggotaans as $keanggotaan)
                                        @if($keanggotaan->anggota->roleID == 3 && $keanggotaan->periodeID == $periodeAktif->periodeID) 
                                            <div class="flex items-center p-2">
                                                <span class="block w-1/3 text-sm font-medium text-gray-900">
                                                    {{ $keanggotaan->anggota->nama }}
                                                </span>
                                                
                                                <input type="hidden" name="keanggotaanID[]" value="{{ $keanggotaan->keanggotaanID }}">
                                                
                                                <select name="status_kehadiran[]" class="mt-2 w-1/3 rounded-md border border-gray-300 py-1.5 px-3 text-sm">
                                                    @php
                                                        $existingKehadiran = $kehadiranMisa->kehadiranMisaAnggota
                                                            ->where('keanggotaanID', $keanggotaan->keanggotaanID)
                                                            ->first();
                                                    @endphp
                                                    <option value="" disabled {{ is_null($existingKehadiran) ? 'selected' : '' }}>Pilih Status</option>
                                                    <option value="hadir" {{ $existingKehadiran && $existingKehadiran->status_kehadiran == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                                    <option value="ijin" {{ $existingKehadiran && $existingKehadiran->status_kehadiran == 'ijin' ? 'selected' : '' }}>Izin</option>
                                                    <option value="alpha" {{ $existingKehadiran && $existingKehadiran->status_kehadiran == 'alpha' ? 'selected' : '' }}>Alpha</option>
                                                </select>
                                                
                                                <input type="text" name="alasan_ijin[]" 
                                                    class="mt-2 w-1/3 rounded-md border border-gray-300 py-1.5 px-3 text-sm"
                                                    placeholder="Alasan jika izin"
                                                    value="{{ $existingKehadiran ? $existingKehadiran->alasan_ijin : '' }}">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <!-- Anggota Section -->
                            <div>
                                <h3 class="text-sm font-medium leading-6 text-gray-900 mb-2">Anggota</h3>
                                <div class="w-full h-80 overflow-y-auto rounded-md border border-gray-300 p-2">
                                    @foreach($keanggotaans as $keanggotaan)
                                        @if($keanggotaan->anggota->roleID == 2 && $keanggotaan->periodeID == $periodeAktif->periodeID) 
                                            <div class="flex items-center p-2">
                                                <span class="block w-1/3 text-sm font-medium text-gray-900">
                                                    {{ $keanggotaan->anggota->nama }}
                                                </span>
                                                
                                                <input type="hidden" name="keanggotaanID[]" value="{{ $keanggotaan->keanggotaanID }}">
                                                
                                                <select name="status_kehadiran[]" class="mt-2 w-1/3 rounded-md border border-gray-300 py-1.5 px-3 text-sm">
                                                    @php
                                                        $existingKehadiran = $kehadiranMisa->kehadiranMisaAnggota
                                                            ->where('keanggotaanID', $keanggotaan->keanggotaanID)
                                                            ->first();
                                                    @endphp
                                                    <option value="" disabled {{ is_null($existingKehadiran) ? 'selected' : '' }}>Pilih Status</option>
                                                    <option value="hadir" {{ $existingKehadiran && $existingKehadiran->status_kehadiran == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                                    <option value="ijin" {{ $existingKehadiran && $existingKehadiran->status_kehadiran == 'ijin' ? 'selected' : '' }}>Izin</option>
                                                    <option value="alpha" {{ $existingKehadiran && $existingKehadiran->status_kehadiran == 'alpha' ? 'selected' : '' }}>Alpha</option>
                                                </select>
                                                
                                                <input type="text" name="alasan_ijin[]" 
                                                    class="mt-2 w-1/3 rounded-md border border-gray-300 py-1.5 px-3 text-sm"
                                                    placeholder="Alasan jika izin"
                                                    value="{{ $existingKehadiran ? $existingKehadiran->alasan_ijin : '' }}">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <!-- Bantuan Section -->
                        <div>
                            <h3 class="text-sm font-medium leading-6 text-gray-900 mb-2">Bantuan</h3>
                            <div class="mb-4">
                                <input type="text" id="searchBantuan" 
                                    class="block w-full rounded-md border border-gray-300 py-1.5 px-3 text-sm" 
                                    placeholder="Cari anggota...">
                            </div>
                            <div class="w-full h-80 overflow-y-auto rounded-md border border-gray-300 p-2" id="bantuanList">
                                @foreach($keanggotaans2 as $keanggotaan)
                                    @if($keanggotaan->periodeID == $periodeAktif->periodeID) 
                                        <div class="flex items-center p-2 bantuan-item">
                                            <span class="block w-1/3 text-sm font-medium text-gray-900 bantuan-nama">
                                                {{ $keanggotaan->anggota->nama }}
                                            </span>
                                            
                                            <input type="hidden" name="keanggotaanID[]" value="{{ $keanggotaan->keanggotaanID }}">
                                            
                                            <select name="status_kehadiran[]" class="mt-2 w-1/3 rounded-md border border-gray-300 py-1.5 px-3 text-sm">
                                                @php
                                                    $existingKehadiran = $kehadiranMisa->kehadiranMisaAnggota
                                                        ->where('keanggotaanID', $keanggotaan->keanggotaanID)
                                                        ->first();
                                                @endphp
                                                <option value="" {{ $existingKehadiran && $existingKehadiran->status_kehadiran == '' ? 'selected' : '' }}>Pilih Status</option>
                                                <option value="hadir" {{ $existingKehadiran && $existingKehadiran->status_kehadiran == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                                
                                            </select>
                                            
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                            
                        </div>
                    </div>
                </div>
              
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="/kehadiran">
                        <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Batal</button>
                    </a>
                    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Simpan</button>
                </div>
            </form>
        </main>

        <script>
            document.getElementById('searchBantuan').addEventListener('input', function () {
                const query = this.value.toLowerCase();
                const items = document.querySelectorAll('.bantuan-item');
        
                items.forEach(item => {
                    const name = item.querySelector('.bantuan-nama').textContent.toLowerCase();
                    if (name.includes(query)) {
                        item.style.display = 'flex'; // Tampilkan item jika sesuai
                    } else {
                        item.style.display = 'none'; // Sembunyikan item jika tidak sesuai
                    }
                });
            });
        </script>
    </body>
</html>