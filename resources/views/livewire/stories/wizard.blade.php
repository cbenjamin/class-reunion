<div class="max-w-3xl mx-auto px-4 py-10" x-data="storyWizard()">
  <h1 class="text-2xl font-bold mb-1">Share a Memory</h1>
  <p class="text-gray-600 mb-6">A few quick prompts. You can choose to appear as “Anonymous”.</p>

  {{-- Progress --}}
  <div class="mb-6">
    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
      <div class="h-full bg-indigo-600 transition-all" :style="`width: ${step*33.33}%`"></div>
    </div>
    <div class="mt-2 text-xs text-gray-500" x-text="`Step ${step} of 3`"></div>
  </div>

  {{-- STEP 1 --}}
  <div x-show="step === 1" x-transition class="bg-white rounded-xl shadow p-6 space-y-3">
    <label class="block text-sm font-medium">
      Favorite memory <span class="text-gray-400">(10–600 chars)</span>
    </label>
    <textarea x-model.trim="memory" rows="5" class="w-full rounded-md border-gray-300"
              placeholder="Tell us about a moment you'll never forget…"></textarea>
    <template x-if="errors.memory">
      <p class="text-sm text-red-600" x-text="errors.memory[0]"></p>
    </template>

    <div class="flex justify-end gap-2 pt-4">
      <button type="button"
              @click="next()"
              class="px-4 py-2 rounded bg-indigo-600 text-white disabled:opacity-50"
              :disabled="busy">
        <span x-show="!busy">Next</span>
        <span x-show="busy">Checking…</span>
      </button>
    </div>
  </div>

  {{-- STEP 2 --}}
  <div x-show="step === 2" x-transition class="bg-white rounded-xl shadow p-6 space-y-4">
    <div>
      <label class="block text-sm font-medium">Teacher shoutout (optional)</label>
      <input type="text" x-model.trim="teacher" class="w-full rounded-md border-gray-300" placeholder="Mrs. Smith"/>
      <template x-if="errors.teacher">
        <p class="text-sm text-red-600" x-text="errors.teacher[0]"></p>
      </template>
    </div>
    <div>
      <label class="block text-sm font-medium">Song that defined HS (optional)</label>
      <input type="text" x-model.trim="song" class="w-full rounded-md border-gray-300" placeholder="‘Wonderwall’ – Oasis"/>
      <template x-if="errors.song">
        <p class="text-sm text-red-600" x-text="errors.song[0]"></p>
      </template>
    </div>

    <div class="flex justify-between gap-2 pt-4">
      <button type="button" @click="back()" class="px-4 py-2 rounded border">Back</button>
      <button type="button"
              @click="next()"
              class="px-4 py-2 rounded bg-indigo-600 text-white disabled:opacity-50"
              :disabled="busy">
        <span x-show="!busy">Next</span>
        <span x-show="busy">Checking…</span>
      </button>
    </div>
  </div>

  {{-- STEP 3 --}}
  <div x-show="step === 3" x-transition class="bg-white rounded-xl shadow p-6 space-y-4">
    <div>
      <label class="block text-sm font-medium">Where I am now (optional)</label>
      <textarea x-model.trim="now" rows="3" class="w-full rounded-md border-gray-300"
                placeholder="City, career, family, a line or two…"></textarea>
      <template x-if="errors.now">
        <p class="text-sm text-red-600" x-text="errors.now[0]"></p>
      </template>
    </div>

    <label class="inline-flex items-center gap-2">
      <input type="checkbox" x-model="anonymous" class="rounded border-gray-300">
      <span class="text-sm text-gray-700">Post as Anonymous</span>
    </label>

    <div class="flex justify-between gap-2 pt-4">
      <button type="button" @click="back()" class="px-4 py-2 rounded border">Back</button>
      <button type="button"
              @click="submit()"
              class="px-4 py-2 rounded bg-green-600 text-white disabled:opacity-50"
              :disabled="busy">
        <span x-show="!busy">Submit Story</span>
        <span x-show="busy">Submitting…</span>
      </button>
    </div>
  </div>
</div>