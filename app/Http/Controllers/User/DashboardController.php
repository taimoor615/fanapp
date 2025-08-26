<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\NewsPost;
use App\Models\Game;
use App\Models\User;
use App\Models\GamePayment;
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

        // // Recent points earned
        // $recentPoints = UserPoint::where('user_id', $user->id)
        //     ->with('action')
        //     ->orderBy('created_at', 'desc')
        //     ->take(5)
        //     ->get();

        // User's rank in team
        // $userRank = Leaderboard::where('team_id', $team->id)
        //     ->where('period_type', 'all_time')
        //     ->where('user_id', $user->id)
        //     ->value('rank') ?? 'N/A';

         // Recent points earned
        $recentPoints = UserPoint::where('user_id', $user->id)
            ->with('action')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Calculate user's rank globally based on total_points
        $userRank = User::where('total_points', '>', $user->total_points)->count() + 1;

        $attendedGames = GamePayment::where('user_id', $user->id)
        ->where('status', 'approved')
        ->count();

        return view('user.dashboard', compact(
            'latestNews',
            'upcomingGames',
            'recentPoints',
            'userRank',
            'attendedGames'
        ));
    }

    // Get leaderboard
    public function leaderboard(Request $request)
    {
        $period = $request->get('period', 'all_time'); // all_time, monthly, weekly

        // Start query from users table
        $query = User::select('id', 'first_name','last_name', 'total_points');

        // If you want period filtering, you'll need a reference table for points earned per game.
        // Since total_points is cumulative, filtering by month/week doesn't directly apply.
        // We skip it here unless you track historical points separately.

        $leaderboardresult = $query
            ->orderByDesc('total_points')
            ->limit(50)
            ->get();

        $leaderboard = $leaderboardresult->map(function ($result, $index) {
            return [
                'rank' => $index + 1,
                'first_name' => $result->first_name,
                'last_name' => $result->last_name,
                'total_points' => $result->total_points,
                'user_id' => $result->id,
            ];
        });

        $currentUserId = auth()->id();
        $userRank = $leaderboard->firstWhere('user_id', $currentUserId);
        $userRankPosition = $leaderboard->search(fn($u) => $u['user_id'] === $currentUserId);
        $userRank = $userRankPosition !== false ? $userRankPosition + 1 : null;

        return view('user.leaderboard', compact('leaderboard', 'userRank', 'period'));
    }

    public function store()
    {
        $user = auth()->user();
        $team = $user->team;

        // This would integrate with your merchandise system
        return view('user.store');
    }
}
