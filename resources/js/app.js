import './bootstrap';
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

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
