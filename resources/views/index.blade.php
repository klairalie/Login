<x-nav-layout>
  <section 
    x-data="{ openModal: false, slide: 0, total: 3 }"
    x-init="setInterval(() => { slide = (slide + 1) % total }, 5000)"
    class="relative bg-[#F4F7FA] min-h-screen flex flex-col items-center overflow-hidden">

    <!-- HERO SECTION -->
    <div class="relative w-full max-w-7xl mx-auto pt-32 pb-20 px-6 text-center">
      <img 
        src="https://images.unsplash.com/photo-1618828665691-83a3eccc7c4e?auto=format&fit=crop&w=2000&q=80"
        alt="Air conditioning technician"
        class="absolute inset-0 w-full h-full object-cover opacity-20"
      >
      <div class="absolute inset-0 via-transparent to-gray-200/20"></div>
      <div class="relative z-10">
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-sky-900 tracking-tight mb-4 mt-15">
          3RS Air-Conditioning Solution
        </h1>
        <p class="text-base sm:text-lg lg:text-xl text-black max-w-2xl mx-auto leading-relaxed mb-8">
          Your trusted partner in air-conditioning installation, cleaning, and maintenance. Efficient, reliable, and affordable — all in one place.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
          <a href="{{ route('show.bookingindex') }}"
             class="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-full shadow hover:bg-emerald-700 transition flex items-center justify-center gap-2">
            <x-lucide-calendar class="w-5 h-5" />
            Book Service Now
          </a>
          <a href="{{ route('contact') }}"
             class="px-6 py-3 bg-sky-600 text-white font-semibold rounded-full shadow hover:bg-sky-700 transition flex items-center justify-center gap-2">
            <x-lucide-phone class="w-5 h-5" />
            Contact Us
          </a>
          <button @click="openModal = true"
             class="px-6 py-3 bg-gray-100 text-sky-800 font-semibold rounded-full shadow hover:bg-gray-200 transition flex items-center justify-center gap-2">
            <x-lucide-user-plus class="w-5 h-5" />
            Join Our Team
          </button>
        </div>
      </div>
    </div>

    <!-- FEATURE GRID -->
    <div id="features" class="relative w-full max-w-7xl mx-auto py-16 px-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 lg:gap-8">
      @php
        $features = [
          ['title' => 'Installation','desc' => 'Professional setup ensuring reliable and efficient AC performance.','icon' => 'wrench','bg' => 'white','text' => 'text-sky-800'],
          ['title' => 'Repair','desc' => 'Quick, dependable repair service to restore your comfort fast.','icon' => 'hammer','bg' => 'bg-sky-500','text' => 'text-white'],
          ['title' => 'Cleaning','desc' => 'Detailed cleaning service to improve airflow and system lifespan.','icon' => 'sparkles','bg' => 'white','text' => 'text-sky-800'],
          ['title' => 'Buy & Sell Units','desc' => 'Affordable, high-quality new and pre-owned AC units for every need.','icon' => 'shopping-cart','bg' => 'white','text' => 'text-emerald-700'],
        ];
      @endphp
      @foreach ($features as $feature)
        <div class="block {{ $feature['bg'] }} shadow-lg rounded-2xl p-8 text-center hover:shadow-2xl hover:-translate-y-2 transition duration-300">
          <div class="flex justify-center mb-5">
            @switch($feature['icon'])
              @case('wrench')
                <x-lucide-wrench class="w-14 h-14 {{ $feature['text'] }}" />
                @break
              @case('hammer')
                <x-lucide-hammer class="w-14 h-14 {{ $feature['text'] }}" />
                @break
              @case('sparkles')
                <x-lucide-sparkles class="w-14 h-14 {{ $feature['text'] }}" />
                @break
              @case('shopping-cart')
                <x-lucide-shopping-cart class="w-14 h-14 {{ $feature['text'] }}" />
                @break
            @endswitch
          </div>
          <h3 class="text-xl font-bold {{ $feature['text'] }} mb-2">{{ $feature['title'] }}</h3>
          <p class="text-gray-600">{{ $feature['desc'] }}</p>
        </div>
      @endforeach
    </div>

    <!-- SERVICES SECTION -->
    <section class="py-16 bg-gray-50 w-full">
      <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center">Our Specialized Services</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
           <img src="{{ asset('storage/Ac installation.webp') }}" 
     alt="AC Installation" class="w-full h-64 object-cover">


            <div class="p-6 text-center">
              <h3 class="text-xl font-semibold text-gray-800 mb-2">AC Installation</h3>
              <p class="text-gray-600 text-base">Professional setup of air-conditioning units for homes and offices, ensuring optimal performance and energy efficiency.</p>
            </div>
          </div>
          <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
       <img src="{{ asset('storage/Ac_cleaning.jpg') }}" 
     alt="AC Installation" class="w-full h-64 object-cover">
            <div class="p-6 text-center">
              <h3 class="text-xl font-semibold text-gray-800 mb-2">AC Cleaning & Maintenance</h3>
              <p class="text-gray-600 text-base">Thorough cleaning services to improve airflow, hygiene, and lifespan of your AC units.</p>
            </div>
          </div>
          <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
           <img src="{{ asset('storage/AC-repair.webp') }}" 
     alt="AC Installation" class="w-full h-64 object-cover">

            <div class="p-6 text-center">
              <h3 class="text-xl font-semibold text-gray-800 mb-2">AC Repair</h3>
              <p class="text-gray-600 text-base">Fast and reliable AC repair services, fixing all major issues and restoring comfort in your space.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- GALLERY SECTION -->
    <section class="py-16 w-full">
      <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center">Our Work Gallery</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
          <div class="group relative overflow-hidden rounded-lg">
            <img src="{{ asset('storage/progress.jpg') }}" class="w-full h-64 object-cover transition-transform transform group-hover:scale-105">
          </div>
          <div class="group relative overflow-hidden rounded-lg">
            <img src="{{ asset('storage/gallery.jpg') }}" class="w-full h-64 object-cover transition-transform transform group-hover:scale-105">
          </div>
          <div class="group relative overflow-hidden rounded-lg">
             <img src="{{ asset('storage/hays.jpg') }}" class="w-full h-64 object-cover transition-transform transform group-hover:scale-105">
          </div>
          <div class="group relative overflow-hidden rounded-lg">
             <img src="{{ asset('storage/much.jpg') }}" class="w-full h-64 object-cover transition-transform transform group-hover:scale-105">
          </div>
          <div class="group relative overflow-hidden rounded-lg">
             <img src="{{ asset('storage/pro.jpg') }}" class="w-full h-64 object-cover transition-transform transform group-hover:scale-105">
          </div>
          <div class="group relative overflow-hidden rounded-lg">
             <img src="{{ asset('storage/rep.jpg') }}" class="w-full h-64 object-cover transition-transform transform group-hover:scale-105">
          </div>
          <div class="group relative overflow-hidden rounded-lg">
             <img src="{{ asset('storage/team.jpg') }}" class="w-full h-64 object-cover transition-transform transform group-hover:scale-105">
          </div>
          <div class="group relative overflow-hidden rounded-lg">
             <img src="{{ asset('storage/work.jpg') }}" class="w-full h-64 object-cover transition-transform transform group-hover:scale-105">
          </div>
        </div>
      </div>
    </section>

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
           class="px-8 py-3 bg-sky-600 text-white items-center font-semibold rounded-full shadow hover:bg-sky-700 transition flex items-center justify-center gap-2">
          <x-lucide-download class="w-5 h-5 items-center" />
          Download Resume Format
        </a>
        <button 
          @click="openModal = false"
          class="mt-10 px-5 py-2 bg-gray-200 text-gray-800 rounded-full hover:bg-gray-300 transition flex items-center justify-center gap-2">
          <x-lucide-x class="w-5 h-5 items-center" />
          Close
        </button>
      </div>
    </div>
  </section>

      <!-- ABOUT US SECTION -->
    <section class="py-16 bg-gray-200 w-full" id="aboutus">
      <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
        <div>
          <h2 class="text-3xl font-bold text-gray-800 mb-6">About Us</h2>
          <p class="text-gray-600 text-base leading-relaxed">
            3RS Air-Conditioning Solution provides top-notch installation, cleaning, and repair services for residential and commercial AC units. 
            We are committed to reliability, efficiency, and customer satisfaction. Our goal is to ensure your space stays comfortable all year round.
          </p>
          <p class="mt-4 text-gray-600 text-base leading-relaxed">
            Our team of trained professionals uses the latest tools and techniques to deliver high-quality service with guaranteed results.
          </p>
        </div>
        <div>
          <div class="group relative overflow-hidden">
             <img src="{{ asset('storage/about.jpg') }}" class="w-full h-100 object-cover transition-transform transform group-hover:scale-105">
          </div>
        </div>
      </div>
    </section>

    <!-- VISIT US / LOCATION SECTION -->
     <section class="bg-gray-100">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:py-20 lg:px-8">
            
            <!-- Header -->
            <div class="max-w-2xl lg:max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Visit Our Location</h2>
                <p class="mt-4 text-lg text-gray-500">
                    Come and visit us at our main office in Carcar City, Cebu. We’re happy to assist you during business hours.
                </p>
            </div>

            <!-- Map and Info Section -->
            <div class="mt-16 lg:mt-20">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                    <!-- Google Map -->
                    <div class="rounded-lg overflow-hidden shadow-lg">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3949.1871894824865!2d123.64025947484246!3d10.113195972367727!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a9aef97f4f7c97%3A0x9b8c76c3c7a2dc6d!2sOliveros%20Bayong%2C%20Can-asujan%2C%20Carcar%20City%2C%20Cebu%2C%20Philippines!5e0!3m2!1sen!2sph!4v1731234567890!5m2!1sen!2sph"
                            width="100%"
                            height="480"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <div class="max-w-full mx-auto rounded-lg overflow-hidden shadow-lg bg-white">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">Our Address</h3>
                                <p class="mt-1 text-gray-600">
                                    Oliveros Bayong, Can-asujan, Carcar City, Cebu, Philippines
                                </p>
                            </div>

                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900">Hours</h3>
                                <p class="mt-1 text-gray-600">Monday - Friday: 9:00 AM - 5:00 PM</p>
                                <p class="mt-1 text-gray-600">Saturday: 10:00 AM - 4:00 PM</p>
                                <p class="mt-1 text-gray-600">Sunday: Closed</p>
                            </div>

                            <div class="px-6 py-4">
                                <h3 class="text-lg font-medium text-gray-900">Contact Us</h3>
                                <p class="mt-1 text-gray-600">Email: 3rsairconditioningsolution@gmail.com</p>
                                <p class="mt-1 text-gray-600">Phone: 09271374570</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <!-- ANNOUNCEMENTS / QUICK LINKS -->
    {{-- <section class="py-16 bg-white w-full">
      <div class="max-w-7xl mx-auto px-6">
        <h2 class="text-3xl font-bold text-gray-800 mb-10 text-center">Announcements</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {{-- @php
            $announcements = \App\Models\Announcement::latest()->take(6)->get();
          @endphp
          @foreach ($announcements as $announcement)
            <div class="bg-gray-50 p-6 rounded-2xl shadow hover:shadow-lg transition">
              <h3 class="text-lg font-semibold text-sky-800 mb-2">{{ $announcement->title }}</h3>
              <p class="text-gray-600 text-sm">{{ Str::limit($announcement->content, 120) }}</p>
              <p class="mt-2 text-gray-400 text-xs">{{ $announcement->created_at->format('F d, Y') }}</p>
            </div>
          @endforeach --}}
        </div>
      </div>
    </section> 

    <!-- FOOTER -->
    <footer class="bg-sky-900 text-white py-12">
      <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
          <h3 class="text-xl font-bold mb-4">3RS Air-Conditioning</h3>
          <p class="text-gray-200 text-sm">Providing reliable AC installation, maintenance, and repair services with professionalism and care. Your comfort is our priority.</p>
        </div>
        <div>
          <h3 class="text-xl font-bold mb-4">Quick Links</h3>
          <ul class="space-y-2 text-gray-200">
            <li><a href="#features" class="hover:text-emerald-400 transition">Features</a></li>
            <li><a href="#aboutus" class="hover:text-emerald-400 transition">About Us</a></li>
            
            <li><a href="{{ route('show.bookingindex') }}" class="hover:text-emerald-400 transition">Book Service</a></li>
            <li><a href="{{ route('contact') }}" class="hover:text-emerald-400 transition">Contact</a></li>
          </ul>
        </div>
        <div>
          <h3 class="text-xl font-bold mb-4">Connect With Us</h3>
          <p class="text-gray-200 text-sm">Follow us on social media and stay updated with our latest promotions, tips, and announcements.</p>
          <div class="flex gap-4 mt-4">
            <a href="https://www.facebook.com/adrian.genobia.2025" class="hover:text-emerald-400 transition"><x-lucide-facebook class="w-6 h-6" /></a>
           <a href="mailto:3rsairconditioningsolution@gmail.com" class="flex items-center gap-2 hover:text-emerald-400 transition">
    <x-lucide-mail class="w-6 h-6" />
    3rsairconditioningsolution@gmail.com
</a>
          </div>
        </div>
      </div>
      <div class="text-center mt-12 text-gray-400 text-sm">
        &copy; {{ date('Y') }} 3RS Air-Conditioning Solution. All rights reserved.
      </div>
    </footer>


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
