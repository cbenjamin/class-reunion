// resources/js/app.js
import './bootstrap'
import Alpine from 'alpinejs'

// Guard against double-starts
if (!window.Alpine) {
  window.Alpine = Alpine

  document.addEventListener('alpine:init', () => {
    // single global nav store used by hamburger + drawer
    if (!Alpine.store('nav')) {
      Alpine.store('nav', {
        open: false,
        toggle() { this.open = !this.open },
        close()  { this.open = false },
        show()   { this.open = true },
      })
    }
  })

  Alpine.start()
}

// Quill (rich text) - import once
import Quill from 'quill';
import 'quill/dist/quill.snow.css';
window.Quill = Quill;

// Global store for the mobile drawer
Alpine.store('nav', { open: false })

window.reactionBar = function (photoId, initialCounts = {}, initialMine = null) {
  return {
    // state
    counts: { ...initialCounts },
    mine: initialMine,
    open: false,
    busy: false,

    // config
    types: ['love', 'laugh', 'wow', 'like', 'party', 'sad'],
    icons: { love: '❤️', laugh: '😂', wow: '😮', like: '👍', party: '🎉', sad: '😢' },
    heartType: 'love',

    // computed
    heartCount() { return this.counts[this.heartType] || 0; },
    summaryTypes() {
      // show other reactions that have counts, plus the one you chose (if not heart)
      return this.types.filter(t =>
        t !== this.heartType && ((this.counts[t] || 0) > 0 || this.mine === t)
      );
    },

    togglePopover() { this.open = !this.open; },
    close() { this.open = false; },

    // optimistic, no-reload toggle
    async react(type) {
      if (this.busy) return;
      this.busy = true;

      const prevMine = this.mine;
      const prevCounts = { ...this.counts };

      // optimistic update
      if (prevMine === type) {
        // toggle off
        this.counts[type] = Math.max(0, (this.counts[type] || 0) - 1);
        if (this.counts[type] === 0) delete this.counts[type];
        this.mine = null;
      } else {
        if (prevMine) {
          this.counts[prevMine] = Math.max(0, (this.counts[prevMine] || 0) - 1);
          if (this.counts[prevMine] === 0) delete this.counts[prevMine];
        }
        this.counts[type] = (this.counts[type] || 0) + 1;
        this.mine = type;
      }

      try {
        const res = await fetch(`/photos/${photoId}/react.json`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
          },
          body: JSON.stringify({ type }),
        });
        if (res.status === 401) { window.location.href = '/login'; return; }
        if (!res.ok) throw new Error('Bad response');
        const data = await res.json();
        this.counts = data.counts || {};
        this.mine = data.mine || null;
      } catch (e) {
        // rollback on error
        this.counts = prevCounts;
        this.mine = prevMine;
        console.error('Reaction failed', e);
      } finally {
        this.busy = false;
      }
    },

    async choose(type) {
      await this.react(type);
      this.close();
    },
  };
};

// Story wizard controller (client-side steps + server submit)
window.storyWizard = function (initial = {}) {
  return {
    step: 1,
    memory: '',
    teacher: '',
    song: '',
    now: '',
    anonymous: false,
    busy: false,
    errors: {},

    next() {
      // simple client-side check for step 1
      this.errors = {};
      if (this.step === 1) {
        const len = (this.memory || '').trim().length;
        if (len < 10) { this.errors.memory = ['Please share at least 10 characters.']; return; }
        if (len > 600) { this.errors.memory = ['Please keep it under 600 characters.']; return; }
      }
      this.step = Math.min(this.step + 1, 3);
    },

    back() { this.step = Math.max(this.step - 1, 1); },

    async submit() {
      this.busy = true;
      this.errors = {};
      try {
        const res = await fetch('/stories.json', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
          },
          body: JSON.stringify({
            memory: this.memory,
            teacher: this.teacher || null,
            song: this.song || null,
            now: this.now || null,
            anonymous: !!this.anonymous,
          }),
        });

        if (res.status === 401) { window.location.href = '/login'; return; }

        if (res.status === 422) {
          const data = await res.json();
          this.errors = data.errors || {};
          // bounce user back to step 1 if the main memory failed
          if (this.errors.memory) this.step = 1;
          return;
        }

        if (!res.ok) throw new Error('Request failed');

        const data = await res.json();
        // redirect with a flashy query param the dashboard can show
        window.location.href = '/dashboard?status=story_submitted';
      } catch (e) {
        console.error(e);
        alert('Sorry, something went wrong submitting your story.');
      } finally {
        this.busy = false;
      }
    }
  }
}

//Alpine.start();

