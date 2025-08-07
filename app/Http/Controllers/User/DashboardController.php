<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\NewsPost;
use App\Models\Game;
use App\Models\UserPoint;
use App\Models\Leaderboard;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $team = $user->team;

        // Latest news for user's team
        $latestNews = NewsPost::where('team_id', $team->id)
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        // Upcoming games for user's team
        $upcomingGames = Game::where('team_id', $team->id)
            ->where('status', 'scheduled')
            ->where('game_date', '>', now())
            ->orderBy('game_date')
            ->take(3)
            ->get();

        // Recent points earned
        $recentPoints = UserPoint::where('user_id', $user->id)
            ->with('action')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // User's rank in team
        $userRank = Leaderboard::where('team_id', $team->id)
            ->where('period_type', 'all_time')
            ->where('user_id', $user->id)
            ->value('rank') ?? 'N/A';

        return view('user.dashboard', compact(
            'latestNews',
            'upcomingGames',
            'recentPoints',
            'userRank'
        ));
    }

    public function leaderboard()
    {
        $user = auth()->user();
        $team = $user->team;

        $leaderboard = Leaderboard::where('team_id', $team->id)
            ->where('period_type', 'all_time')
            ->with('user')
            ->orderBy('rank')
            ->take(50)
            ->get();

        return view('user.leaderboard', compact('leaderboard'));
    }

    public function store()
    {
        $user = auth()->user();
        $team = $user->team;

        // This would integrate with your merchandise system
        return view('user.store');
    }
}
