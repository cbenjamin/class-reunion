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
                    function initWhereNow() {
                        const el = document.getElementById('where-map');
                        if (!el) return;

                        // Prevent double-initialization on soft navigations
                        if (el.dataset.inited === '1') return;
                        el.dataset.inited = '1';

                        const map = L.map(el, { worldCopyJump: true });
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 18,
                            attribution: '&copy; OpenStreetMap',
                        }).addTo(map);

                        const markers = L.markerClusterGroup({
                            showCoverageOnHover: false,
                            spiderfyOnMaxZoom: true
                        });

                        const data = @json($markers);
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
                            markers.addLayer(m);
                            bounds.push([p.lat, p.lng]);
                        });

                        map.addLayer(markers);
                        if (bounds.length) {
                            map.fitBounds(bounds, { padding: [30, 30] });
                        } else {
                            map.setView([20, 0], 2);
                        }
                    }

                    // First load + Livewire soft navs
                    document.addEventListener('DOMContentLoaded', initWhereNow);
                    document.addEventListener('livewire:navigated', initWhereNow);
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