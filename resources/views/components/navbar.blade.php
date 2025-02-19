{{-- nav bar --}}
<nav class="bg-red-950" x-data="{ isOpen: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            {{-- logo --}}
            <a href="/">
                <img class="h-8 w-8" src="{{ asset('/img/logo-PAST.jpg') }}" alt="logoweb" style="border-radius: 50%;">
            </a>  
          </div>
          {{-- Putra Altar Santo Tarsisius --}}
          <a href="/">
            <h1 style="color: white; margin-left: 15px;">Putra Altar Santo Tarsisius</h1>
          </a>
          
          {{-- nav bar versi full  --}}
          <div class="hidden md:block">
            <div class="ml-10 flex items-baseline space-x-4">
              <x-nav-link href="/" :active="request()->is('/')">Home</x-nav-link>
              <x-nav-link href="/about-PAST" :active="request()->is('about-PAST')">About</x-nav-link>
              <x-nav-link href="/contact-PAST" :active="request()->is('contact-PAST')">Contact</x-nav-link>
              <x-nav-link href="/login" :active="request()->is('login')">Kehadiran</x-nav-link>
              
            </div>
          </div>
        </div>
        
        {{-- nav bar versi mobile --}}
        <div class="-mr-2 flex md:hidden">
          <!-- Mobile menu button -->
          <button type="button" @click="isOpen = !isOpen" class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" aria-controls="mobile-menu" aria-expanded="false">
            <span class="absolute -inset-0.5"></span>
            <span class="sr-only">Open main menu</span>
            <!-- Menu open: "hidden", Menu closed: "block" -->
            <svg :class="{'hidden': isOpen, 'block': !isOpen }" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
            <!-- Menu open: "block", Menu closed: "hidden" -->
            <svg :class="{'block': isOpen, 'hidden': !isOpen }" class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile menu, show/hide based on menu state. -->
    <div x-show="isOpen" class="md:hidden" id="mobile-menu">
      <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
        <x-nav-link-mobile href="/" :active="request()->is('/')">Home</x-nav-link-mobile>
        <x-nav-link-mobile href="/about-PAST" :active="request()->is('about-PAST')">About</x-nav-link-mobile>
        <x-nav-link-mobile href="/contact-PAST" :active="request()->is('contact-PAST')">Contact</x-nav-link-mobile>
        <x-nav-link-mobile href="/login" :active="request()->is('login')">Kehadiran</x-nav-link-mobile>
      </div>
    </div>
  </nav>