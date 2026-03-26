<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityFeed extends Component
{
    use WithPagination;

    public function render()
    {
        $photos = DB::table('photos')
            ->where('status', 'approved')
            ->select([
                'id',
                DB::raw("'photo' as type"),
                'user_id',
                DB::raw("COALESCE(caption, '') as excerpt"),
                DB::raw('0 as is_anonymous'),
                'updated_at as occurred_at',
            ]);

        $stories = DB::table('stories')
            ->where('status', 'approved')
            ->select([
                'id',
                DB::raw("'story' as type"),
                'user_id',
                DB::raw('SUBSTR(memory, 1, 100) as excerpt'),
                'anonymous as is_anonymous',
                'updated_at as occurred_at',
            ]);

        $classmates = DB::table('users')
            ->where('is_approved', true)
            ->whereNotNull('approved_at')
            ->select([
                'id',
                DB::raw("'classmate' as type"),
                'id as user_id',
                'name as excerpt',
                DB::raw('0 as is_anonymous'),
                'approved_at as occurred_at',
            ]);

        $feed = $photos
            ->unionAll($stories)
            ->unionAll($classmates)
            ->orderByDesc('occurred_at')
            ->paginate(15);

        // Single query to resolve all user names — no N+1
        $userIds = collect($feed->items())->pluck('user_id')->unique()->filter();
        $userMap = User::whereIn('id', $userIds)->pluck('name', 'id');

        return view('livewire.dashboard.activity-feed', compact('feed', 'userMap'));
    }
}
