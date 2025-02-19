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

        <main class="p-4 md:ml-64 h-auto pt-10">
            
            <form method="post" action="/informations/{{ $information->slug }}" enctype="multipart/form-data">
              @method('put')
                @csrf
                <div class="space-y-12">
              
                  <div class="border-b border-gray-900/10 pb-12">
                    <div class="text-center mb-4">
                        <h2 class="text-2xl font-semibold leading-7 text-gray-900">Informasi</h2>
                        <p class="mt-2 text-sm leading-6 text-gray-600">Edit Informasi seputar Putra Altar Santo Tarsisius</p>
                    </div>

                    @if (session()->has('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                    <span class="font-medium">Error!</span> {{ session('error') }}
                </div>
                @endif
                    <div class="mt-5 grid grid-cols-1 gap-x-6 gap-y-5 sm:grid-cols-1">
                      <div class="mt-1 space-y-6">
                        {{-- input title --}}
                        <div class="w-full">
                            <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Title</label>
                            <div class="mt-2">
                                <input type="text" name="title" id="title" 
                                    class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('title') is-invalid @enderror" 
                                    value="{{ old('title', $information->title) }}" autofocus>
                            </div>
                            @error('title')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        {{-- input slug --}}
                        <div class="w-full">
                            <label for="slug" class="block text-sm font-medium leading-6 text-gray-900">Slug</label>
                            <div class="mt-2">
                                <input type="text" name="slug" id="slug" 
                                    class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 @error('slug') is-invalid @enderror" 
                                    value="{{ old('slug', $information->slug) }}" autofocus disabled readonly>
                            </div>
                            @error('slug')
                                <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                    <span class="font-medium">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        {{-- input gambar --}}
                        <div class="w-full">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="image">Foto Informasi</label>
                            {{-- untuk mengirimkan data gambar lama --}}
                            <input type="hidden" name="oldImage" value="{{ $information->image }}">
                            @if ($information->image)
                                <img src="{{ asset('/storage/' . $information->image) }}" class="img-preview w-full h-auto mb-3 max-w-xs">
                            @else
                                <img class="img-preview w-full h-auto mb-3 max-w-xs"> 
                            @endif
                            
                            <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
                            @error('image') is-invalid @enderror" id="image" name="image" type="file" onchange="previewImage()">

                            @error('image')
                            <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                <span class="font-medium">{{ $message }}</span>
                            </div>
                            @enderror
                        </div>

                      {{-- input body --}}
                      <div class="w-full">
                        <label for="body" class="block text-sm font-medium leading-6 text-gray-900">Body</label>
                        <div class="mt-2">
                                <textarea name="body" id="body" rows="4" class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 
                                @error('body') is-invalid @enderror">{{ old('body', $information->body) }}</textarea>
                        </div>
                        @error('body')
                            <div class="mt-2 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                                <span class="font-medium">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    </div>
                  </div>
                </div>
              
                <div class="mt-6 flex items-center justify-end gap-x-6">
                  <a href="/informations">
                  <button type="button" class="text-sm font-semibold leading-6 text-gray-900">Cancel</button>
                  </a>
                  <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Update</button>
                </div>
              </form>
        </main>

        
        <script>
            //slug otomatis
            const title = document.querySelector('#title');
            const slug = document.querySelector('#slug');

            title.addEventListener('change', function() {
                fetch('/informations/checkSlug?title=' + title.value)
                  .then(response => response.json())
                  .then(data => slug.value = data.slug)
            });

            function previewImage() {
                const image = document.querySelector('#image');
                const imgPreview = document.querySelector('.img-preview');

                imgPreview.style.display = 'block';

                const oFReader = new FileReader();

                oFReader.readAsDataURL(image.files[0]);

                oFReader.onload = function(oFREvent) {
                    imgPreview.src = oFREvent.target.result;
                }
            }
        </script>
    </body>
    </html>