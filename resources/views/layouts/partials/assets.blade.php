{{-- resources/views/layouts/partials/assets.blade.php --}}
@once
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <livewire:scripts data-navigate-once />
@endonce