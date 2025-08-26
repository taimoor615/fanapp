<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Fancam;
use App\Models\Game;
use App\Models\GamePayment;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FancamController extends Controller
{
    public function index()
    {
        $fancams = Fancam::with(['game', 'team'])
            ->byUser(Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('user.fancam.index', compact('fancams'));
    }

    public function create()
    {
        // Get games where user has made payment
        $userGames = GamePayment::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->with('game')
            ->get()
            ->pluck('game');

        $teams = Team::all();

        return view('user.fancam.create', compact('userGames', 'teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'team_id' => 'required|exists:teams,id',
            'images' => 'required|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'titles.*' => 'nullable|string|max:255',
            'descriptions.*' => 'nullable|string|max:500',
        ]);

        // Check if user has participated in the game
        $hasParticipated = GamePayment::where('user_id', Auth::id())
            ->where('game_id', $request->game_id)
            ->where('status', 'approved')
            ->exists();

        if (!$hasParticipated) {
            return redirect()->back()->with('error', 'You have not participated in this game.');
        }

        // Check if user already has 5 photos for this game
        $existingPhotos = Fancam::where('user_id', Auth::id())
            ->where('game_id', $request->game_id)
            ->count();

        $newPhotosCount = count($request->images);

        if (($existingPhotos + $newPhotosCount) > 5) {
            return redirect()->back()->with('error', 'You can only upload maximum 5 photos per game.');
        }

        $uploadedCount = 0;
        $totalPoints = 0;

        foreach ($request->images as $index => $image) {
            $imageName = time() . '_' . Str::random(10) . '.' . $image->extension();
            $imagePath = $image->storeAs('fancams', $imageName, 'public');

            $fancam = Fancam::create([
                'user_id' => Auth::id(),
                'game_id' => $request->game_id,
                'team_id' => $request->team_id,
                'image_path' => $imagePath,
                'title' => $request->titles[$index] ?? null,
                'description' => $request->descriptions[$index] ?? null,
                'points' => 10,
                'status' => 'pending',
            ]);

            if ($fancam) {
                // Award points to user
                $user = User::find(Auth::id());
                $user->increment('total_points', 10);
                $totalPoints += 10;
                $uploadedCount++;
            }
        }

        return redirect()->route('user.fancam.index')
            ->with('success', "Successfully uploaded {$uploadedCount} photos. You earned {$totalPoints} points!");
    }

    public function show(Fancam $fancam)
    {
        // Ensure user can only view their own fancams
        if ($fancam->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.fancam.show', compact('fancam'));
    }

    public function edit(Fancam $fancam)
    {
        // Ensure user can only edit their own fancams
        if ($fancam->user_id !== Auth::id()) {
            abort(403);
        }

        $userGames = GamePayment::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->with('game')
            ->get()
            ->pluck('game');

        $teams = Team::all();

        return view('user.fancam.edit', compact('fancam', 'userGames', 'teams'));
    }

    public function update(Request $request, Fancam $fancam)
    {
        // Ensure user can only update their own fancams
        if ($fancam->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'game_id' => 'required|exists:games,id',
            'team_id' => 'required|exists:teams,id',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'game_id' => $request->game_id,
            'team_id' => $request->team_id,
            'title' => $request->title,
            'description' => $request->description,
        ];

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($fancam->image_path && Storage::disk('public')->exists($fancam->image_path)) {
                Storage::disk('public')->delete($fancam->image_path);
            }

            $imageName = time() . '_' . Str::random(10) . '.' . $request->image->extension();
            $imagePath = $request->image->storeAs('fancams', $imageName, 'public');
            $data['image_path'] = $imagePath;
        }

        $fancam->update($data);

        return redirect()->route('user.fancam.index')
            ->with('success', 'Fancam updated successfully!');
    }

    public function destroy(Fancam $fancam)
    {
        // Ensure user can only delete their own fancams
        if ($fancam->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete image file
        if ($fancam->image_path && Storage::disk('public')->exists($fancam->image_path)) {
            Storage::disk('public')->delete($fancam->image_path);
        }

        // Deduct points from user
        $user = User::find($fancam->user_id);
        $user->decrement('total_points', $fancam->points);

        $fancam->delete();

        return redirect()->route('user.fancam.index')
            ->with('success', 'Fancam deleted successfully! Points deducted.');
    }

    public function getGamesByPayment()
    {
        $games = GamePayment::where('user_id', Auth::id())
            ->where('status', 'approved')
            ->with('game')
            ->get()
            ->pluck('game');

        return response()->json($games);
    }
}
