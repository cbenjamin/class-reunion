<x-page-header title="Event Details" description="Everything you need to know about the reunion." />

<div class="max-w-5xl mx-auto px-4 py-10">
  @if(session('status'))
    <div class="mb-4 rounded bg-green-50 text-green-800 px-4 py-3 text-sm">{{ session('status') }}</div>
  @endif
@if(request('status') === 'story_submitted')
  <div class="mb-4 rounded bg-green-50 text-green-800 px-4 py-3 text-sm">
    Thanks! Your story was submitted for review.
  </div>
@endif
  <!-- Event Details section -->
  <div class="mt-8 grid md:grid-cols-2 gap-6">
    <div class="bg-white shadow rounded-xl p-6">
      <h2 class="font-semibold mb-4">When & Where</h2>
      <dl class="text-sm text-gray-700 space-y-2">
        <div class="flex justify-between gap-3">
          <dt class="text-gray-500">Date</dt>
          <dd class="font-medium">{{ $event['date'] }}</dd>
        </div>
        <div class="flex justify-between gap-3">
          <dt class="text-gray-500">Time</dt>
          <dd class="font-medium">{{ $event['time'] }}</dd>
        </div>
        <div class="flex justify-between gap-3">
          <dt class="text-gray-500">Venue</dt>
          <dd class="font-medium text-right">{{ $event['venue'] }}</dd>
        </div>
        <div class="flex justify-between gap-3">
          <dt class="text-gray-500">Address</dt>
          <dd class="font-medium text-right">{{ $event['address'] }}</dd>
        </div>
      </dl>

      <div class="mt-4 flex gap-3">
        <a href="{{ $mapUrl }}" target="_blank" rel="noopener"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-700 text-white text-sm font-medium hover:bg-red-800 transition">
          <i class="fa-solid fa-diamond-turn-right text-xs"></i> Get Directions
        </a>
        {{-- Optional: add-to-calendar later --}}
      </div>
    </div>

    <div class="bg-white shadow rounded-xl p-6">
      <h2 class="font-semibold mb-4">Notes</h2>
      <p class="text-sm text-gray-700 leading-relaxed">
        {!! $event['notes'] !!}
      </p>
      <hr class="my-4">
      <h3 class="font-medium mb-2">Quick Links</h3>
      <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
        <li><a class="hover:underline" href="{{ route('photos.index') }}">Upload Photos</a></li>
        @can('admin')
          <li><a class="hover:underline" href="{{ route('admin.invites.index') }}">Review Invitation Requests</a></li>
          <li><a class="hover:underline" href="{{ route('admin.photos.index') }}">Moderate Photos</a></li>
        @endcan
      </ul>
    </div>
  </div>

  <livewire:dashboard.activity-feed />
</div>