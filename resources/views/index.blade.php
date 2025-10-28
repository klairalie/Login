<x-nav-layout>
  <section 
    x-data="{ openModal: false, slide: 0, total: 3 }"
    x-init="setInterval(() => { slide = (slide + 1) % total }, 5000)"
    class="relative bg-[#F4F7FA] min-h-screen flex flex-col items-center overflow-hidden">

    <!-- HERO SECTION -->
    <div class="relative w-full max-w-7xl mx-auto pt-32 pb-20 px-6 text-center">
      <!-- Background Image -->
      <img 
        src="https://images.unsplash.com/photo-1618828665691-83a3eccc7c4e?auto=format&fit=crop&w=2000&q=80"
        alt="Air conditioning technician"
        class="absolute inset-0 w-full h-full object-cover opacity-20"
      >

      <!-- Overlay gradient -->
      <div class="absolute inset-0 via-transparent to-gray-200/20"></div>

      <!-- Text content -->
      <div class="relative z-10">
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-sky-900 tracking-tight mb-4 mt-15">
          3RS Air-Conditioning Solution
        </h1>
        <p class="text-base sm:text-lg lg:text-xl text-black max-w-2xl mx-auto leading-relaxed mb-8">
          Your trusted partner in air-conditioning installation, cleaning, and maintenance.  
          Efficient, reliable, and affordable — all in one place.
        </p>

        <!-- CTA Buttons -->
      <div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
  <!-- Explore Services -->
  {{-- <a href="#services"
     class="px-6 py-3 bg-sky-600 text-white font-semibold rounded-full shadow hover:bg-sky-700 transition flex items-center justify-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>
    Explore Services
  </a> --}}

  <!-- Book Service -->
  <a href="{{ route('show.bookingindex') }}"
     class="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-full shadow hover:bg-emerald-700 transition flex items-center justify-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-10 8h10m2-16h-14a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2z" />
    </svg>
    Book Service Now
  </a>

  <!-- Contact -->
  <a href="#contact"
     class="px-6 py-3 bg-sky-600 text-white font-semibold rounded-full shadow hover:bg-sky-700 transition flex items-center justify-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10a9 9 0 11-18 0 9 9 0 0118 0z" />
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0z" />
    </svg>
    Contact Us
  </a>

  <!-- Join Our Team -->
  <button @click="openModal = true"
     class="px-6 py-3 bg-gray-100 text-sky-800 font-semibold rounded-full shadow hover:bg-gray-200 transition flex items-center justify-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
    </svg>
    Join Our Team
  </button>
</div>

      </div>
    </div>

    <!-- FEATURE GRID -->
    <div id="features" class="relative w-full max-w-7xl mx-auto py-16 px-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 lg:gap-8">
      
      @php
        $features = [
          [
            'title' => 'Installation',
            'desc' => 'Professional setup ensuring reliable and efficient AC performance.',
            'icon' => 'https://cdn-icons-png.flaticon.com/512/2432/2432584.png',
            'bg' => 'white',
            'text' => 'text-sky-800',
          ],
          [
            'title' => 'Repair',
            'desc' => 'Quick, dependable repair service to restore your comfort fast.',
            'icon' => 'https://cdn-icons-png.flaticon.com/512/1087/1087840.png',
            'bg' => 'bg-sky-500 text-white',
            'text' => 'text-white',
          ],
         [
  'title' => 'Cleaning',
  'desc'  => 'Detailed cleaning service to improve airflow and system lifespan.',
  'icon'  => 'https://cdn-icons-png.flaticon.com/512/7416/7416232.png',
  'bg'    => 'white',
  'text'  => 'text-sky-800',
],

          [
            'title' => 'Buy & Sell Units',
            'desc' => 'Affordable, high-quality new and pre-owned AC units for every need.',
            'icon' => 'https://cdn-icons-png.flaticon.com/512/679/679720.png',
            'bg' => 'white',
            'text' => 'text-emerald-700',
          ],
        ];
      @endphp

      @foreach ($features as $feature)
        <div class="block {{ $feature['bg'] }} shadow-lg rounded-2xl p-8 text-center hover:shadow-2xl hover:-translate-y-2 transition duration-300">
          <div class="flex justify-center mb-5">
            <img src="{{ $feature['icon'] }}" alt="{{ $feature['title'] }}" class="w-14 h-14 object-contain">
          </div>
          <h3 class="text-xl font-bold {{ $feature['text'] }} mb-2">{{ $feature['title'] }}</h3>
          <p class="text-gray-600">{{ $feature['desc'] }}</p>
        </div>
      @endforeach
    </div>

  {{-- <!-- CAROUSEL SHOWCASE -->
<div id="services" 
     x-data="{ slide: 0, total: 3 }" 
     x-init="setInterval(() => { slide = (slide + 1) % total }, 5000)"
     class="relative w-full max-w-7xl mx-auto py-16 px-6">

  <div class="overflow-hidden rounded-2xl shadow-2xl">
    <div class="flex transition-transform duration-700" 
         :style="`transform: translateX(-${slide * 100}%)`">

      <!-- Slide 1: Aircon Units -->
      <div class="w-full flex-shrink-0">
        <img src="{{ asset('storage/air.jpg') }}" 
             class="w-full h-96 object-center" alt="Aircon Units">
        <div class="bg-white p-6 text-center">
          <h3 class="text-2xl font-bold text-sky-800 mb-2">Aircon Units</h3>
          <p class="text-gray-600 max-w-lg mx-auto">
            Efficient and modern cooling systems for homes, offices, and commercial buildings.
          </p>
        </div>
      </div>

      <!-- Slide 2: Parts & Accessories -->
      <div class="w-full flex-shrink-0">
        <img src="" 
             class="w-full h-96 object-cover" alt="Parts & Accessories">
        <div class="bg-white p-6 text-center">
          <h3 class="text-2xl font-bold text-emerald-700 mb-2">Parts & Accessories</h3>
          <p class="text-gray-600 max-w-lg mx-auto">
            Genuine components and accessories to enhance performance and reliability.
          </p>
        </div>
      </div>

      <!-- Slide 3: Maintenance Services -->
      <div class="w-full flex-shrink-0">
        <img src="https://images.unsplash.com/photo-1603791440384-56cd371ee9a7?auto=format&fit=crop&w=1600&q=80" 
             class="w-full h-96 object-cover" alt="Maintenance Services">
        <div class="bg-white p-6 text-center">
          <h3 class="text-2xl font-bold text-sky-800 mb-2">Maintenance Services</h3>
          <p class="text-gray-600 max-w-lg mx-auto">  
            Professional installation, cleaning, and preventive maintenance by certified technicians.
          </p>
        </div>
      </div>

    </div>
  </div>

  <!-- Carousel Indicators -->
  <div class="flex justify-center mt-6 space-x-2">
    <template x-for="i in total" :key="i">
      <button 
        @click="slide = i - 1" 
        :class="slide === i - 1 ? 'bg-sky-800 scale-110' : 'bg-gray-300'"
        class="w-4 h-4 rounded-full transition transform"></button>
    </template>
  </div>
</div> --}}

    <!-- APPLICATION MODAL -->
    <div 
      x-show="openModal"
      x-transition
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-60 z-50 p-4"
      style="display: none;">
      <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-8 text-center">
        <h2 class="text-2xl font-bold text-sky-900 mb-4">Before You Apply</h2>
        <p class="text-gray-800 mb-6 leading-relaxed">
          Please download and use our official resume format before submitting your application.
        </p>

        <a href="{{ route('resume.download') }}" 
           onclick="downloadAndRedirect(event)"
           class="inline-block px-6 py-3 bg-sky-600 text-white font-semibold rounded-full shadow hover:bg-sky-700 transition">
          Download Resume Format
        </a>

        <button 
          @click="openModal = false"
          class="mt-6 px-5 py-2 bg-gray-200 text-gray-800 rounded-full hover:bg-gray-300 transition">
          Close
        </button>
      </div>
    </div>
  </section>

  <script>
    function downloadAndRedirect(event) {
      event.preventDefault();
      const link = document.createElement('a');
      link.href = "{{ route('resume.download') }}";
      link.download = '';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      setTimeout(() => {
        window.location.href = "{{ route('show.applicationform') }}"; 
      }, 1000);
    }
  </script>
</x-nav-layout>
