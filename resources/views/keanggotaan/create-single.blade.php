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
        <form method="POST" action="{{ route('keanggotaan.store') }}">
            @csrf
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="text-center mb-4">
                        <h2 class="text-2xl font-semibold leading-7 text-gray-900">Keanggotaan</h2>
                        <p class="mt-2 text-sm leading-6 text-gray-600">Pilih kelompok serta jabatan untuk keanggotaan</p>
                    </div>

                    @if (session()->has('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-medium">Error!</span> {{ session('error') }}
                </div>
                @endif

                    <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-1">

                        {{-- periode aktif --}}
                        <div class="w-full">
                            <label class="block text-sm font-medium leading-6 text-gray-900">Periode Aktif</label>
                            <input type="text" 
                                   class="mt-2 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 bg-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 sm:text-sm sm:leading-6" 
                                   value="{{ $periodes->where('status_periode', 1)->first()->nama_periode }} ({{ $periodes->where('status_periode', 1)->first()->tanggal_mulai_periode->format('d M Y') }} - {{ $periodes->where('status_periode', 1)->first()->tanggal_selesai_periode->format('d M Y') }})" 
                                   readonly>
                            <input type="hidden" name="periodeID" value="{{ $periodes->where('status_periode', 1)->first()->periodeID }}">
                        </div>
                        
                        <!-- Pilih Anggota -->
                        <div class="w-full">
                            <label for="anggotaID" class="block text-sm font-medium leading-6 text-gray-900">Pilih Anggota</label>
                            <select name="anggota" id="anggotaID" 
                                class="mt-2 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('anggotaID') is-invalid @enderror">
                                <option value="" disabled {{ old('anggotaID') == '' ? 'selected' : '' }}>Pilih Anggota</option>

                                {{-- PiMi --}}
                                @foreach ($anggotas as $anggota)
                                @if ($anggota->status_anggota == 1 && $anggota->roleID == 4)
                                <option value="{{ $anggota->anggotaID }}" data-role="{{ $anggota->roleID }}" {{ old('anggotaID') == $anggota->anggotaID ? 'selected' : '' }}>
                                    {{ $anggota->nama }} ({{ $anggota->role->nama_role }})
                                </option>
                                @endif
                                @endforeach

                                {{-- pengurus --}}
                                @foreach ($anggotas as $anggota)
                                @if ($anggota->status_anggota == 1 && $anggota->roleID == 3)
                                <option value="{{ $anggota->anggotaID }}" data-role="{{ $anggota->roleID }}" {{ old('anggotaID') == $anggota->anggotaID ? 'selected' : '' }}>
                                    {{ $anggota->nama }} ({{ $anggota->role->nama_role }})
                                </option>
                                @endif
                                @endforeach

                                {{-- Anggota --}}
                                @foreach ($anggotas as $anggota)
                                @if ($anggota->status_anggota == 1 && $anggota->roleID == 2)
                                <option value="{{ $anggota->anggotaID }}" data-role="{{ $anggota->roleID }}" {{ old('anggotaID') == $anggota->anggotaID ? 'selected' : '' }}>
                                    {{ $anggota->nama }} ({{ $anggota->role->nama_role }})
                                </option>
                                @endif
                                @endforeach

                            </select>
                            @error('anggotaID')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Pilih Kelompok -->
                        <div class="w-full">
                            <label for="kelompokID" class="block text-sm font-medium leading-6 text-gray-900">Pilih Kelompok</label>
                            <select name="kelompokID" id="kelompokID" 
                                class="mt-2 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('kelompokID') is-invalid @enderror">
                                <option value="" disabled selected>Pilih Kelompok</option>
                                @foreach ($kelompoks as $kelompok)
                                    <option value="{{ $kelompok->kelompokID }}">{{ $kelompok->nama_kelompok }}</option>
                                @endforeach
                            </select>
                            @error('kelompokID')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Pilih Jabatan -->
                        <div id="jabatan-field" class="w-full">
                            <label for="jabatanID" class="block text-sm font-medium leading-6 text-gray-900">Pilih Jabatan</label>
                            <select name="jabatan_{{ old('anggota') }}" id="jabatanID"
                                class="mt-2 block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('jabatanID') is-invalid @enderror">
                                <option value="" disabled selected>Pilih Jabatan</option>
                                @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->jabatanID }}">{{ $jabatan->nama_jabatan }}</option>
                                @endforeach
                            </select>
                            @error('jabatanID')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="{{ route('keanggotaan.index') }}">
                        <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                    </a>
                    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                </div>
            </div>
        </form>
    </main>

    <script>
        const anggotaSelect = document.getElementById('anggotaID');
        const jabatanField = document.getElementById('jabatan-field');
        const jabatanSelect = document.getElementById('jabatanID');
    
        anggotaSelect.addEventListener('change', () => {
            // Reset nilai field
            jabatanSelect.value = '';

            const selectedAnggotaId = anggotaSelect.value;
        
            // Update nama field
            jabatanSelect.name = `jabatan_${selectedAnggotaId}`;
            
            // Toggle tampilan field berdasarkan role
            const isAnggota = anggotaSelect.options[anggotaSelect.selectedIndex].getAttribute('data-role') === '2';
            jabatanField.style.display = isAnggota ? 'none' : 'block';
        });
    </script>
</body>
</html>