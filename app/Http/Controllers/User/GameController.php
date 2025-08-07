<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameAttendance;
use App\Models\GamePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();


        // dd($payment);
        // Get user's team games (assuming user belongs to a team)
        $query = Game::with(['team']);

        // If user has a specific team, show only those games
        if ($user->team_id) {
            $query->forTeam($user->team_id);
        }

        // Filter by status
        $filter = $request->get('filter', 'upcoming');

        switch ($filter) {
            case 'upcoming':
                $query->upcoming();
                break;
            case 'completed':
                $query->completed();
                break;
            case 'featured':
                $query->featured();
                break;
            case 'attended':
                $query->whereHas('attendances', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
                break;
        }

        $games = $query->orderBy('game_date', $filter === 'completed' ? 'desc' : 'asc')
                      ->paginate(10);

        // Get user's attended games for quick reference
        $userAttendedGames = $user->gameAttendances()->pluck('game_id')->toArray();

        return view('user.games.index', compact('games', 'filter', 'userAttendedGames'));
    }

    public function show(Game $game)
    {
        $user = Auth::user();

        // User Payment
        $payments = GamePayment::where('user_id', auth()->id())->get()->keyBy('game_id');

        // Get user's team games (assuming user belongs to a team)
        $query = Game::with(['team']);

        // If user has a specific team, show only those games
        if ($user->team_id) {
            $query->forTeam($user->team_id);
        }

        $user = Auth::user();
        $hasAttended = $game->hasUserAttended($user->id);
        $userAttendance = null;

        if ($hasAttended) {
            $userAttendance = GameAttendance::where('user_id', $user->id)
                                          ->where('game_id', $game->id)
                                          ->first();
        }

        $game->load(['team']);
        $totalAttendees = $game->getTotalAttendanceCount();

        $gameAttendees =  $game->payments()
        ->where('status', 'approved')
        ->count();

        return view('user.games.show', compact('game', 'hasAttended', 'userAttendance', 'totalAttendees','payments','gameAttendees'));
        // return view('user.games.show', compact('game','user'));
    }

    public function payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'game_id' => 'required|exists:games,id',
            'user_id' => 'required|exists:users,id',
            'verification_data' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'verification_method' => 'required|in:gps,qr_code',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $validatedData = $validator->validated();

        // Handle image upload
        if ($request->hasFile('verification_data')) {
            $file = $request->file('verification_data');
            $fileName = time() . '_' . $request->user_id . '.' . $file->getClientOriginalExtension();
            $file->storeAs('screenshots', $fileName, 'public');

            $validatedData['verification_data'] = $fileName;
        }

        GameAttendance::create([
            'game_id' => $validatedData['game_id'],
            'user_id' => $validatedData['user_id'],
            'verification_data' => $validatedData['verification_data'],
            'verification_method' => $validatedData['verification_method'],
        ]);

        return redirect()->route('user.games.index')
            ->with('success', 'Payment submitted successfully!');
    }

    public function markAttendance(Request $request, Game $game)
    {
        $user = Auth::user();

        // Check if user already attended
        if ($game->hasUserAttended($user->id)) {
            return response()->json([
                'success' => false,
                'message' => 'You have already marked attendance for this game.'
            ], 422);
        }

        // Check if game is in the past or live (can't attend future games unless it's live)
        if ($game->game_date > now() && $game->status !== 'live') {
            return response()->json([
                'success' => false,
                'message' => 'You can only mark attendance during or after the game.'
            ], 422);
        }

        // Validate attendance method
        $validator = Validator::make($request->all(), [
            'verification_method' => 'required|in:gps,qr_code,manual',
            'verification_data' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $verificationMethod = $request->verification_method;
        $verificationData = $request->verification_data;

        // Validate based on verification method
        if ($verificationMethod === 'gps') {
            $gpsValidator = Validator::make($verificationData ?? [], [
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180'
            ]);

            if ($gpsValidator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid GPS coordinates provided.'
                ], 422);
            }

            // Here you could add logic to verify if GPS coordinates are near the venue
            // For now, we'll just accept any valid coordinates
        }

        if ($verificationMethod === 'qr_code') {
            $qrValidator = Validator::make($verificationData ?? [], [
                'qr_code' => 'required|string'
            ]);

            if ($qrValidator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid QR code provided.'
                ], 422);
            }

            // Here you could add logic to verify the QR code
            // For now, we'll accept any QR code
        }

        // Mark attendance and award points
        $attendance = $game->markUserAttendance($user->id, $verificationMethod, $verificationData);

        if ($attendance) {
            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully!',
                'points_earned' => $attendance->points_earned,
                'total_points' => $user->fresh()->points
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to mark attendance. Please try again.'
        ], 500);
    }

    public function myAttendances(Request $request)
    {
        $user = Auth::user();

        $attendances = GameAttendance::with(['game.team'])
            ->forUser($user->id)
            ->orderBy('attended_at', 'desc')
            ->paginate(15);

        $stats = [
            'total_games_attended' => $attendances->total(),
            'total_points_earned' => GameAttendance::forUser($user->id)->sum('points_earned'),
            'verification_breakdown' => GameAttendance::forUser($user->id)
                ->selectRaw('verification_method, COUNT(*) as count')
                ->groupBy('verification_method')
                ->pluck('count', 'verification_method')
        ];

        return view('user.games.my-attendances', compact('attendances', 'stats'));
    }

    public function upcomingGames()
    {
        $user = Auth::user();

        $query = Game::with(['team'])->upcoming();

        // If user has a team, prioritize their team's games
        if ($user->team_id) {
            $query->orderByRaw("CASE WHEN team_id = {$user->team_id} THEN 0 ELSE 1 END");
        }

        $upcomingGames = $query->orderBy('game_date', 'asc')->limit(5)->get();

        return view('user.games.upcoming', compact('upcomingGames'));
    }

    public function gameCalendar(Request $request)
    {
        $user = Auth::user();
        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        $games = Game::with(['team'])
            ->whereYear('game_date', $year)
            ->whereMonth('game_date', $month);

        if ($user->team_id) {
            $games->forTeam($user->team_id);
        }

        $games = $games->orderBy('game_date')->get();

        // Format games for calendar display
        $calendarGames = $games->map(function($game) use ($user) {
            return [
                'id' => $game->id,
                'title' => $game->opponent_team,
                'date' => $game->game_date->format('Y-m-d'),
                'time' => $game->game_date->format('H:i'),
                'venue' => $game->venue,
                'type' => $game->home_away,
                'status' => $game->status,
                'attended' => $game->hasUserAttended($user->id)
            ];
        });

        return view('user.games.calendar', compact('calendarGames', 'year', 'month'));
    }

    // Quick attendance for mobile app (QR code scan)
    public function quickAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'game_id' => 'required|exists:games,id',
            'qr_code' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $game = Game::find($request->game_id);
        $user = Auth::user();

        return $this->markAttendance(new Request([
            'verification_method' => 'qr_code',
            'verification_data' => ['qr_code' => $request->qr_code]
        ]), $game);
    }
}
