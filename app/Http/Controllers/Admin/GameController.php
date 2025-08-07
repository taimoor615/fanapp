<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Team;
use App\Models\GameAttendance;
use App\Models\GamePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Game::with(['team', 'attendances']);

        // Filter by team
        if ($request->filled('team_id')) {
            $query->where('team_id', $request->team_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->where('game_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('game_date', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('opponent_team', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%");
            });
        }

        $games = $query->orderBy('game_date', 'desc')->paginate(15);
        $teams = Team::orderBy('name')->get();

        return view('admin.games.index', compact('games', 'teams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teams = Team::orderBy('name')->get();
        return view('admin.games.create', compact('teams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'team_id' => 'required|exists:teams,id',
            'opponent_team' => 'required|string|max:255',
            'game_date' => 'required|date|after:now',
            'venue' => 'required|string|max:255',
            'home_away' => 'required|in:home,away',
            'description' => 'nullable|string',
            'ticket_url' => 'nullable|url',
            'ticket_price' => 'nullable|numeric|min:0',
            'attendance_points' => 'required|integer|min:1|max:1000',
            'is_featured' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $game = Game::create([
            'team_id' => $request->team_id,
            'opponent_team' => $request->opponent_team,
            'game_date' => $request->game_date,
            'venue' => $request->venue,
            'home_away' => $request->home_away,
            'description' => $request->description,
            'ticket_url' => $request->ticket_url,
            'ticket_price' => $request->ticket_price,
            'attendance_points' => $request->attendance_points,
            'is_featured' => $request->boolean('is_featured'),
            'status' => 'scheduled'
        ]);

        return redirect()->route('admin.games.index')
            ->with('success', 'Game created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        $game->load(['team', 'attendances.user']);
        $attendanceStats = [
            'total_attendees' => $game->attendances->count(),
            'total_points_given' => $game->attendances->sum('points_earned'),
            'verification_methods' => $game->attendances->groupBy('verification_method')->map->count()
        ];

        // Payment
        $payments = GamePayment::with('user')->where('game_id', $game->id)->get();

        return view('admin.games.show', compact('game', 'attendanceStats','payments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        $teams = Team::orderBy('name')->get();
        return view('admin.games.edit', compact('game', 'teams'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Game $game)
    {
        $validator = Validator::make($request->all(), [
            'team_id' => 'required|exists:teams,id',
            'opponent_team' => 'required|string|max:255',
            'game_date' => 'required|date',
            'venue' => 'required|string|max:255',
            'home_away' => 'required|in:home,away',
            'description' => 'nullable|string',
            'ticket_url' => 'nullable|url',
            'ticket_price' => 'nullable|numeric|min:0',
            'attendance_points' => 'required|integer|min:1|max:1000',
            'is_featured' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $game->update([
            'team_id' => $request->team_id,
            'opponent_team' => $request->opponent_team,
            'game_date' => $request->game_date,
            'venue' => $request->venue,
            'home_away' => $request->home_away,
            'description' => $request->description,
            'ticket_url' => $request->ticket_url,
            'ticket_price' => $request->ticket_price,
            'attendance_points' => $request->attendance_points,
            'is_featured' => $request->boolean('is_featured')
        ]);

        return redirect()->route('admin.games.index')
            ->with('success', 'Game updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        // Check if game has attendances
        if ($game->attendances()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete game with existing attendances. Cancel the game instead.');
        }

        $game->delete();

        return redirect()->route('admin.games.index')
            ->with('success', 'Game deleted successfully!');
    }

    public function paymentsUpdateStatus(Request $request, GamePayment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        // Only update points if status is changing to approved
        $wasNotApproved = $payment->status !== 'approved';
        $isNowApproved = $request->status === 'approved';

        $payment->status = $request->status;
        $payment->save();

        // Award points only when newly approved
        if ($wasNotApproved && $isNowApproved) {
            $game = $payment->game; // Use relationship
            $user = $payment->user;

            if ($game && $game->attendance_points > 0) {
                $user->increment('total_points', $game->attendance_points);
            }
        }

        return back()->with('success', 'Payment status updated successfully.');
    }

    // Additional methods for game management
    public function toggleFeatured(Game $game)
    {
        $game->update(['is_featured' => !$game->is_featured]);

        $status = $game->is_featured ? 'featured' : 'unfeatured';
        return redirect()->back()
            ->with('success', "Game {$status} successfully!");
    }

    public function attendances(Game $game)
    {
        $attendances = $game->attendances()
            ->with('user')
            ->orderBy('attended_at', 'desc')
            ->paginate(20);

        return view('admin.games.attendances', compact('game', 'attendances'));
    }

    public function bulkUpdateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'game_ids' => 'required|array',
            'game_ids.*' => 'exists:games,id',
            'status' => 'required|in:scheduled,live,completed,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        Game::whereIn('id', $request->game_ids)
            ->update(['status' => $request->status]);

        return response()->json(['message' => 'Games updated successfully!']);
    }

    public function stats()
    {
        $stats = [
            'total_games' => Game::count(),
            'upcoming_games' => Game::upcoming()->count(),
            'completed_games' => Game::completed()->count(),
            'total_attendances' => GameAttendance::count(),
            'points_distributed' => GameAttendance::sum('points_earned'),
            'games_by_month' => Game::selectRaw('MONTH(game_date) as month, COUNT(*) as count')
                ->whereYear('game_date', date('Y'))
                ->groupBy('month')
                ->pluck('count', 'month'),
            'top_attended_games' => Game::withCount('attendances')
                ->orderBy('attendances_count', 'desc')
                ->limit(5)
                ->get()
        ];

        return view('admin.games.stats', compact('stats'));
    }
}
