<div class="max-w-3xl mx-auto p-6">
  <h1 class="text-2xl font-semibold">Location Settings</h1>
  <p class="text-sm text-gray-600 mt-1">Share only your city and state. Your exact address is never collected.</p>

  @if(session('status'))
    <div class="mt-4 rounded bg-green-50 text-green-800 px-4 py-3 text-sm">{{ session('status') }}</div>
  @endif

  <form wire:submit.prevent="save" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-1">
      <label class="label">City</label>
      <input type="text" class="field" wire:model.defer="city" placeholder="e.g., Dallas">
      @error('city') <div class="help">{{ $message }}</div> @enderror
    </div>
    <div class="md:col-span-1">
      <label class="label">State / Region</label>
      <input type="text" class="field" wire:model.defer="state" placeholder="e.g., TX">
      @error('state') <div class="help">{{ $message }}</div> @enderror
    </div>

    <div class="md:col-span-2 flex items-center gap-2 mt-2">
      <input id="share" type="checkbox" wire:model.defer="share_location" class="rounded border-gray-300">
      <label for="share" class="text-sm text-gray-700">Allow classmates to see my approximate city on the map</label>
    </div>

    <div class="md:col-span-2 pt-2">
      <button class="btn btn-primary">Save Location</button>
    </div>
  </form>

  {{-- Optional map preview --}}
  <div class="mt-8">
    <h2 class="text-sm font-medium text-gray-700 mb-2">Preview</h2>
    <div wire:ignore class="rounded-xl ring-1 ring-black/5 overflow-hidden">
      <div id="location-preview-map" style="height: 320px;"></div>
    </div>
  </div>

  {{-- Leaflet (CDN) --}}
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

  <script>
    document.addEventListener('livewire:initialized', () => {
      const el = document.getElementById('location-preview-map');
      if (!el) return;

      let map = L.map(el, { scrollWheelZoom: false });
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap',
        maxZoom: 18
      }).addTo(map);

      let marker = null;

      function render(lat, lng) {
        if (lat && lng) {
          const p = [lat, lng];
          if (!marker) {
            marker = L.marker(p).addTo(map);
          } else {
            marker.setLatLng(p);
          }
          map.setView(p, 8);
        } else {
          map.setView([20,0], 2);
        }
      }

      // initial
      render(@json($lat), @json($lng));

      // when saved
      Livewire.on('refocus-map', () => render(@json($lat), @json($lng)));
    });
  </script>
</div>