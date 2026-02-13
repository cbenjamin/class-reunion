<section id="where-now-root">
    @once
        {{-- Put styles in <head>. Ensure your layout has @stack('head') --}}
        @push('head')
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
            <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css"/>
            <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css"/>
        @endpush

        {{-- Put scripts before </body>. Ensure your layout has @stack('scripts') --}}
@push('scripts')
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
  <script>
    (function () {
      function initHomeWhereMap() {
        const el = document.getElementById('home-where-map');
        if (!el) return;

        // Prevent double init on the same element
        if (el.dataset.inited === '1') return;
        el.dataset.inited = '1';

        // Ensure marker icon assets resolve
        const iconBase = 'https://unpkg.com/leaflet@1.9.4/dist/images/';
        L.Icon.Default.mergeOptions({
          iconRetinaUrl: iconBase + 'marker-icon-2x.png',
          iconUrl:       iconBase + 'marker-icon.png',
          shadowUrl:     iconBase + 'marker-shadow.png',
        });

        const map = L.map(el, { worldCopyJump: true });
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 18,
          attribution: '&copy; OpenStreetMap',
        }).addTo(map);

        const cluster = L.markerClusterGroup({
          showCoverageOnHover: false,
          spiderfyOnMaxZoom: true
        });

        const data = @json($mapMarkers ?? []);
        const bounds = [];

        (data || []).forEach(p => {
          if (!p.lat || !p.lng) return;
          const m = L.marker([p.lat, p.lng]);
          m.bindPopup(
            `<div class="text-sm">
               <div class="font-medium">${p.name}</div>
               <div class="text-gray-600">${p.label}</div>
             </div>`
          );
          cluster.addLayer(m);
          bounds.push([p.lat, p.lng]);
        });

        map.addLayer(cluster);
        if (bounds.length) {
          map.fitBounds(bounds, { padding: [24, 24] });
        } else {
          map.setView([20, 0], 2);
        }

        // Make sure it sizes correctly if the section animates in
        requestAnimationFrame(() => map.invalidateSize());

        // Optional: quick debug to confirm data on prod
        // console.debug('Home map markers:', data.length, data);
      }

      // Start immediately if DOM is already parsed, otherwise wait
      const start = () => {
        if (document.getElementById('home-where-map')) initHomeWhereMap();
      };

      if (document.readyState !== 'loading') start();
      else document.addEventListener('DOMContentLoaded', start);

      // Livewire soft navigations
      document.addEventListener('livewire:navigated', start);
    })();
  </script>
@endpush
    @endonce

    <div class="mx-auto max-w-6xl px-5 py-8">
        <div class="flex items-center justify-between gap-4 mb-4">
            <div>
                <h1 class="text-2xl font-semibold">Where are we now?</h1>
                <p class="text-sm text-gray-600">Approximate locations of approved classmates (city-level only).</p>
            </div>
            <a href="{{ route('settings.location') }}" class="btn btn-secondary">Update my location</a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-3">
                <div class="rounded-xl overflow-hidden ring-1 ring-black/5" wire:ignore>
                    <div id="where-map" style="height: 520px;"></div>
                </div>
            </div>

            <aside class="lg:col-span-1">
                <div class="rounded-xl bg-white shadow ring-1 ring-black/5 p-4">
                    <h2 class="font-medium">Top cities</h2>
                    <ul class="mt-3 space-y-2">
                        @forelse($topCities as $c)
                            <li class="flex items-center justify-between text-sm">
                                <span>{{ $c['city'] ?: 'Unknown' }}, {{ $c['state'] }}</span>
                                <span class="text-gray-600">{{ $c['count'] }}</span>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">No data yet.</li>
                        @endforelse
                    </ul>
                    <p class="mt-4 text-xs text-gray-500">Only classmates who opt-in are shown.</p>
                </div>
            </aside>
        </div>
    </div>
</section>