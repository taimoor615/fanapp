<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fancam;
use App\Models\Game;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FancamController extends Controller
{
    public function index()
    {
        $stats = [
            'total_fancams' => Fancam::count(),
            'approved_fancams' => Fancam::approved()->count(),
            'pending_fancams' => Fancam::pending()->count(),
            'total_points_awarded' => Fancam::sum('points'),
            'recent_fancams' => Fancam::with(['user', 'game'])->latest()->limit(5)->get(),
        ];

        return view('admin.fancam.index', compact('stats'));
    }

    public function show(Fancam $fancam)
    {
        $fancam->load(['user', 'game', 'team']);
        return view('admin.fancam.show', compact('fancam'));
    }

    public function updateStatus(Request $request, Fancam $fancam)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $fancam->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Fancam status updated successfully!');
    }

    public function updatePoints(Request $request, Fancam $fancam)
    {
        $request->validate([
            'points' => 'required|integer|min:0|max:100',
        ]);

        $oldPoints = $fancam->points;
        $newPoints = $request->points;
        $pointsDifference = $newPoints - $oldPoints;

        // Update fancam points
        $fancam->update(['points' => $newPoints]);

        // Update user points
        $user = User::find($fancam->user_id);
        if ($pointsDifference > 0) {
            $user->increment('total_points', $pointsDifference);
        } elseif ($pointsDifference < 0) {
            $user->decrement('total_points', abs($pointsDifference));
        }

        return redirect()->back()->with('success', 'Points updated successfully!');
    }

    public function destroy(Fancam $fancam)
    {
        // Deduct points from user
        $user = User::find($fancam->user_id);
        $user->decrement('total_points', $fancam->points);

        // Delete image file
        if ($fancam->image_path && Storage::disk('public')->exists($fancam->image_path)) {
            Storage::disk('public')->delete($fancam->image_path);
        }

        $fancam->delete();

        return redirect()->route('admin.fancam.index')
            ->with('success', 'Fancam deleted successfully! Points deducted from user.');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'fancam_ids' => 'required|array',
            'fancam_ids.*' => 'exists:fancams,id',
        ]);

        $fancams = Fancam::whereIn('id', $request->fancam_ids)->get();
        $deletedCount = 0;

        foreach ($fancams as $fancam) {
            // Deduct points from user
            $user = User::find($fancam->user_id);
            $user->decrement('total_points', $fancam->points);

            // Delete image file
            if ($fancam->image_path && Storage::disk('public')->exists($fancam->image_path)) {
                Storage::disk('public')->delete($fancam->image_path);
            }

            $fancam->delete();
            $deletedCount++;
        }

        return redirect()->route('admin.fancam.index')
            ->with('success', "Successfully deleted {$deletedCount} fancam(s)!");
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'fancam_ids' => 'required|array',
            'fancam_ids.*' => 'exists:fancams,id',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        Fancam::whereIn('id', $request->fancam_ids)
            ->update(['status' => $request->status]);

        return redirect()->route('admin.fancam.index')
            ->with('success', 'Status updated for selected fancams!');
    }

    public function gameStats(Game $game)
    {
        $stats = [
            'total_fancams' => Fancam::where('game_id', $game->id)->count(),
            'approved_fancams' => Fancam::where('game_id', $game->id)->approved()->count(),
            'pending_fancams' => Fancam::where('game_id', $game->id)->pending()->count(),
            'total_participants' => Fancam::where('game_id', $game->id)->distinct('user_id')->count(),
            'total_points_awarded' => Fancam::where('game_id', $game->id)->sum('points'),
        ];

        $fancams = Fancam::with(['user', 'team'])
            ->where('game_id', $game->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('admin.fancam.game-stats', compact('game', 'stats', 'fancams'));
    }

    public function manage(Request $request)
    {
        $query = Fancam::with(['user', 'game', 'team']);

        // Filter by game
        if ($request->filled('game_id')) {
            $query->where('game_id', $request->game_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Search by title or description
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $fancams = $query->orderBy('created_at', 'desc')->paginate(12);

        // Get filter data
        $games = Game::orderBy('opponent_team')->get();
        $users = User::orderBy('first_name')->get();

        return view('admin.fancam.manage', compact('fancams', 'games', 'users'));
    }
}
