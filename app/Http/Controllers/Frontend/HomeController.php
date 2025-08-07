<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\NewsPost;
use App\Models\Game;

class HomeController extends Controller
{
    public function index()
    {
        $latestNews = NewsPost::with('team')
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();

        $upcomingGames = Game::with('team')
            ->where('status', 'scheduled')
            ->where('game_date', '>', now())
            ->orderBy('game_date')
            ->take(3)
            ->get();

        return view('frontend.home', compact('latestNews', 'upcomingGames'));
    }

    public function news()
    {
        $news = NewsPost::with('team')
            ->where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return view('frontend.news', compact('news'));
    }

    public function games()
    {
        $games = Game::with('team')
            ->orderBy('game_date', 'desc')
            ->paginate(10);

        return view('frontend.games', compact('games'));
    }

    public function rewards()
    {
        return view('frontend.rewards');
    }
}
