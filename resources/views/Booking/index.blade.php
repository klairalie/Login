<x-nav-layout>
<section class="container mx-auto px-6 pt-28 pb-20 min-h-screen">

  <!-- Header and Filters -->
  <div class="flex flex-wrap items-center justify-between mb-10 gap-6">
    <div>
      <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
        Endorsed Air-Conditioning Units
      </h1>
      <p class="text-gray-500 mt-1 text-sm md:text-base">
        Browse our endorsed air-conditioning units. Use filters or sorting to find the perfect model for your needs.
      </p>
    </div>

    <!-- Sort & Search -->
    <div class="flex flex-wrap items-center gap-3 bg-white border rounded-2xl px-4 py-3 shadow-sm">
      <form method="GET" action="{{ route('show.bookingindex') }}" class="flex items-center gap-2">
        <label for="sort" class="text-sm text-gray-600 font-medium">Sort:</label>
        <select name="sort" id="sort" onchange="this.form.submit()" 
                class="border-gray-300 rounded-md text-sm px-3 py-1.5 focus:ring-2 focus:ring-purple-500 focus:outline-none">
          <option value="name" {{ $sortBy == 'name' ? 'selected' : '' }}>Name</option>
          <option value="brand" {{ $sortBy == 'brand' ? 'selected' : '' }}>Brand</option>
          <option value="base_price" {{ $sortBy == 'base_price' ? 'selected' : '' }}>Price</option>
          <option value="capacity" {{ $sortBy == 'capacity' ? 'selected' : '' }}>Capacity</option>
          <option value="category" {{ $sortBy == 'category' ? 'selected' : '' }}>Category</option>
        </select>

        <input type="hidden" name="direction" value="{{ $direction === 'asc' ? 'desc' : 'asc' }}">
        <button type="submit" class="px-3 py-1 rounded-md text-sm border border-gray-300 hover:bg-purple-50 transition-colors">
          {{ $direction === 'asc' ? '↑' : '↓' }}
        </button>
      </form>

      <div class="relative">
        <input type="text" placeholder="Search unit or brand..." class="pl-10 pr-4 py-2 text-sm border rounded-md focus:ring-2 focus:ring-purple-500 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.1-5.4a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
      </div>
    </div>
  </div>

  <!-- Summary Line -->
  <div class="flex justify-between items-center text-sm text-gray-600 mb-6">
    <p><span class="font-medium">{{ count($aircons) }}</span> units available</p>
    <p class="italic">Last updated: {{ now()->format('F d, Y') }}</p>
  </div>

  <!-- Grid -->
  <div id="airconGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-8">
    @forelse($aircons as $unit)
      <article class="bg-white rounded-2xl p-5 shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-2 border border-gray-100 group animate-fadeIn">
        <div class="relative h-48 flex items-center justify-center bg-gray-50 rounded-xl overflow-hidden">
          <img src="{{ $unit->image ? asset('storage/' . $unit->image) : asset('images/aircon-placeholder.png') }}"
               alt="{{ $unit->brand }} {{ $unit->model }}"
               class="h-44 object-contain transition-transform duration-300 group-hover:scale-105">
          <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition"></div>
        </div>

        <div class="mt-5">
          <h2 class="text-lgs font-semibold text-gray-800 truncate"> {{ $unit->name }}
          </h2>
          <h3 class="text-xs font-semibold text-gray-800 truncate mt-3">

            {{ $unit->brand }} {{ $unit->model }}
          </h3>
          {{-- <p class="text-sm text-gray-500">{{ $unit->category ?? 'Split' }} • {{ $unit->capacity ?? '—' }} BTU</p>

          <p class="text-sm text-gray-600 mt-3 line-clamp-3 leading-relaxed">
            {{ Str::limit($unit->description ?? 'Energy efficient and durable air-conditioning unit.', 100) }}
          </p> --}}

          <div class="mt-5 flex justify-between items-center">
            <span class="text-lg font-bold text-purple-700">
              {{ $unit->base_price ? '₱' . number_format($unit->base_price) : 'Price on request' }}
            </span>
            <button type="button"
                    class="js-quickview text-sm text-purple-600 font-medium hover:underline transition"
                    data-unit='@json($unit)'>
              View Details →
            </button>
          </div>
        </div>
      </article>
    @empty
      <p class="col-span-full text-center text-gray-500 py-10">No air-conditioning units found.</p>
    @endforelse
  </div>
</section>

<!-- === Unit Quick View Modal === -->
<div id="unitModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm p-4 transition-all duration-300 ease-out">
  <div id="unitModalContent" class="bg-white rounded-2xl mt-50 max-w-3xl w-full mx-auto shadow-2xl max-h-[90vh] overflow-hidden transform scale-95 opacity-0 transition-all duration-300 ease-out">
    <div class="flex justify-between items-start p-5 border-b bg-gray-50">
      <h2 id="modalTitle" class="text-xl font-semibold text-gray-800">Title</h2>
      <button id="modalClose" aria-label="Close modal" class="text-gray-400 hover:text-gray-800 text-2xl font-bold leading-none">×</button>
    </div>

    <div class="p-10 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="col-span-1 flex justify-center items-center bg-gray-50 rounded-xl">
        <img id="modalImage" src="" alt="" class="h-56 object-contain">
      </div>

      <div class="md:col-span-2">
        <p id="modalCategory" class="text-sm text-gray-500 mb-2"></p>
        <div id="modalPrice" class="text-2xl font-bold text-purple-700 mb-4"></div>
        <ul id="modalSpecs" class="text-sm text-gray-700 space-y-1 mb-4"></ul>
        <p id="modalDescription" class="text-sm text-gray-600 mb-6"></p>

        <div class="flex gap-3">
          <button id="openQuoteForm" 
                  class="flex-1 px-4 py-2 rounded-md bg-purple-600 text-white text-sm font-semibold hover:bg-purple-700 transition text-center">
             Request Quote
          </button>
          <a id="modalProduct" href="#" 
             class="flex-1 px-4 py-2 rounded-md border text-sm hover:bg-gray-50 transition text-center">
             View Product
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- === Request Quote Modal === -->
<div id="quoteModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm p-4 transition-all duration-300 ease-out">
  <div id="quoteModalContent" class="bg-white rounded-2xl max-w-3xl w-full mx-auto shadow-2xl max-h-[90vh] overflow-y-auto transform scale-95 opacity-0 transition-all duration-300 ease-out">
    <div class="flex justify-between items-start p-5 border-b bg-gray-50">
      <h2 class="text-xl font-semibold text-gray-800">Request a Quotation</h2>
      <button id="quoteClose" aria-label="Close modal" class="text-gray-400 hover:text-gray-800 text-2xl font-bold leading-none">×</button>
    </div>

    <form id="quoteForm" class="p-6 space-y-4">
      <input type="hidden" id="quoteProductId" name="product_id" />

      <!-- Customer Info -->
      <div class="rounded-md border">
        <div class="px-4 py-3 border-b bg-gray-50">
          <h6 class="font-medium text-gray-700"><i class="fas fa-user mr-2"></i>Customer Information</h6>
        </div>

        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name *</label>
            <input type="text" id="full_name" name="full_name" required
                   class="mt-1 block w-full rounded-md border px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none" />
          </div>

          <div>
            <label for="business_name" class="block text-sm font-medium text-gray-700">Business Name</label>
            <input type="text" id="business_name" name="business_name"
                   class="mt-1 block w-full rounded-md border px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none" />
          </div>

          <div>
            <label for="contact_info" class="block text-sm font-medium text-gray-700">Contact Info</label>
            <input type="text" id="contact_info" name="contact_info" placeholder="Phone number"
                   class="mt-1 block w-full rounded-md border px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none" />
          </div>

          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email"
                   class="mt-1 block w-full rounded-md border px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none" />
          </div>
        </div>
      </div>

      <!-- Addresses -->
      <div class="rounded-md border">
        <div class="px-4 py-3 flex items-center justify-between border-b bg-gray-50">
          <h6 class="font-medium text-gray-700"><i class="fas fa-map-marker-alt mr-2"></i>Addresses</h6>
          <button type="button" id="addAddressBtnModal" class="inline-flex items-center gap-2 rounded-md border px-3 py-1 text-sm text-purple-700 hover:bg-purple-50">
            <i class="fas fa-plus"></i> Add Address
          </button>
        </div>
        <div id="addAddressesContainer" class="p-4 space-y-4"></div>
      </div>

      <!-- Service Information (Added Section) -->
      <div class="rounded-md border mt-4">
        <div class="px-4 py-3 border-b bg-gray-50">
          <h6 class="font-medium text-gray-700"><i class="fas fa-cogs mr-2"></i>Service Information</h6>
        </div>

        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Type of Service -->
          <div class="col-span-2">
            <label for="service_type" class="block text-sm font-medium text-gray-700">Type of Service *</label>
            <select id="service_type" name="service_type" required
                    class="mt-1 block w-full rounded-md border px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
              <option value="">-- Select Type of Service --</option>
              <option value="Buy and Installation">Buy and Installation</option>
              <option value="Buy Only">Buy Only</option>
              <option value="Installation Only">Installation Only</option>
            </select>
          </div>

          <!-- Product Info -->
          <div class="col-span-2 bg-gray-50 rounded-lg p-4 border mt-2">
            <h6 class="font-medium text-gray-700 mb-2"><i class="fas fa-box mr-2"></i>Product Details</h6>
            <p class="text-sm text-gray-700"><span class="font-semibold">Name:</span> <span id="service_product_name">—</span></p>
            <p class="text-sm text-gray-700"><span class="font-semibold">Brand:</span> <span id="service_product_brand">—</span></p>
            <p class="text-sm text-gray-700"><span class="font-semibold">Price:</span> <span id="service_product_price">—</span></p>
            <p class="text-sm text-gray-700"><span class="font-semibold">Description:</span> <span id="service_product_description">—</span></p>
          </div>

          <!-- Number of Units -->
          <div class="col-span-2">
            <label for="unit_quantity" class="block text-sm font-medium text-gray-700 mt-2">How many units? *</label>
            <input type="number" id="unit_quantity" name="unit_quantity" min="1" required
                   class="mt-1 block w-full rounded-md border px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none"
                   placeholder="Enter quantity" />
          </div>
        </div>
      </div>

      <div class="flex justify-end gap-3 pt-4 border-t">
        <button type="button" id="quoteCancel" class="px-4 py-2 text-sm border rounded-md hover:bg-gray-50">Cancel</button>
        <button type="submit" class="px-4 py-2 text-sm bg-purple-600 text-white rounded-md hover:bg-purple-700">Submit Request</button>
      </div>
    </form>
  </div>
</div>

<!-- Hidden Address Template -->
<div id="addAddressTemplate" class="hidden">
  <div class="add-address-item border rounded-md p-4 bg-white">
    <div class="flex items-start justify-between mb-3">
      <h6 class="text-sm font-medium">Address <span class="add-address-number">1</span></h6>
      <div class="flex items-center gap-2">
        <label class="inline-flex items-center text-sm text-gray-600">
          <input type="checkbox" class="add-address-default mr-2" name="add_address_default[]"> Default
        </label>
        <button type="button" 
        class="remove-add-address inline-flex items-center justify-center w-8 h-8 rounded-md bg-black text-white hover:bg-gray-500">
  <i data-lucide="trash-2" class="w-4 h-4"></i>
</button>

      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
      <div>
        <label class="block text-sm text-gray-700">Label</label>
        <input type="text" class="add-address-label mt-1 block w-full rounded-md border px-3 py-2 text-sm" name="add_address_label[]" placeholder="Home, Office">
      </div>
      <div>
        <label class="block text-sm text-gray-700">Barangay</label>
        <input type="text" class="add-address-barangay mt-1 block w-full rounded-md border px-3 py-2 text-sm" name="add_address_barangay[]" placeholder="Barangay">
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
      <div class="md:col-span-2">
        <label class="block text-sm text-gray-700">Street</label>
        <input type="text" class="add-address-street mt-1 block w-full rounded-md border px-3 py-2 text-sm" name="add_address_street[]" placeholder="Street address">
      </div>
      <div>
        <label class="block text-sm text-gray-700">ZIP</label>
        <input type="text" class="add-address-zip mt-1 block w-full rounded-md border px-3 py-2 text-sm" name="add_address_zip[]" placeholder="ZIP">
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div>
        <label class="block text-sm text-gray-700">City</label>
        <input type="text" class="add-address-city mt-1 block w-full rounded-md border px-3 py-2 text-sm" name="add_address_city[]" placeholder="City">
      </div>
      <div>
        <label class="block text-sm text-gray-700">Province</label>
        <input type="text" class="add-address-province mt-1 block w-full rounded-md border px-3 py-2 text-sm" name="add_address_province[]" placeholder="Province">
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const unitModal = document.getElementById('unitModal');
  const unitContent = document.getElementById('unitModalContent');
  const quoteModal = document.getElementById('quoteModal');
  const quoteContent = document.getElementById('quoteModalContent');
  const quoteForm = document.getElementById('quoteForm');

  const serviceName = document.getElementById('service_product_name');
  const serviceBrand = document.getElementById('service_product_brand');
  const servicePrice = document.getElementById('service_product_price');
  const serviceDesc = document.getElementById('service_product_description');

  const showModal = (modal, content) => {
    modal.classList.remove('hidden');
    setTimeout(() => {
      content.classList.remove('opacity-0', 'scale-95');
    }, 10);
  };

  const hideModal = (modal, content) => {
    content.classList.add('opacity-0', 'scale-95');
    setTimeout(() => {
      modal.classList.add('hidden');
    }, 200);
  };

  document.querySelectorAll('.js-quickview').forEach(btn => {
    btn.addEventListener('click', () => {
      const unit = JSON.parse(btn.dataset.unit);
      document.getElementById('modalTitle').textContent = `${unit.brand} ${unit.model}`;
      document.getElementById('modalImage').src = unit.image ? `/storage/${unit.image}` : '/images/aircon-placeholder.png';
      document.getElementById('modalCategory').textContent = `${unit.category ?? ''} • ${unit.capacity ?? ''} BTU`;
      document.getElementById('modalPrice').textContent = unit.base_price ? `₱${Number(unit.base_price).toLocaleString()}` : 'Price on request';
      document.getElementById('modalDescription').textContent = unit.description ?? '—';
      document.getElementById('quoteProductId').value = unit.id;

      // Fill in service info for quote modal
      serviceName.textContent = `${unit.brand} ${unit.model}`;
      serviceBrand.textContent = unit.brand ?? '—';
      servicePrice.textContent = unit.base_price ? `₱${Number(unit.base_price).toLocaleString()}` : '—';
      serviceDesc.textContent = unit.description ?? '—';

      showModal(unitModal, unitContent);
    });
  });

  document.getElementById('modalClose').addEventListener('click', () => hideModal(unitModal, unitContent));
  document.getElementById('openQuoteForm').addEventListener('click', () => {
    hideModal(unitModal, unitContent);
    setTimeout(() => showModal(quoteModal, quoteContent), 300);
  });
  document.getElementById('quoteClose').addEventListener('click', () => hideModal(quoteModal, quoteContent));
  document.getElementById('quoteCancel').addEventListener('click', () => hideModal(quoteModal, quoteContent));

  // Add address logic
  const addAddressBtn = document.getElementById('addAddressBtnModal');
  const addressContainer = document.getElementById('addAddressesContainer');
  const addressTemplate = document.getElementById('addAddressTemplate').innerHTML;

  addAddressBtn.addEventListener('click', () => {
    addressContainer.insertAdjacentHTML('beforeend', addressTemplate);
    updateAddressNumbers();
  });

  addressContainer.addEventListener('click', e => {
    if (e.target.closest('.remove-add-address')) {
      e.target.closest('.add-address-item').remove();
      updateAddressNumbers();
    }
  });

  const updateAddressNumbers = () => {
    document.querySelectorAll('.add-address-number').forEach((el, idx) => el.textContent = idx + 1);
  };

  // Form submit (demo)
  quoteForm.addEventListener('submit', e => {
    e.preventDefault();
    alert('Quote request submitted successfully!');
    hideModal(quoteModal, quoteContent);
  });
});
</script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
  lucide.createIcons();
</script>

</x-nav-layout>
