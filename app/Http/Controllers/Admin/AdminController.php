<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\Team;
use App\Models\User;
use App\Models\NewsPost;
use App\Models\Game;
use App\Models\Admin;

class AdminController extends Controller
{

    public function showLogin()
    {
        return view('admin.login');
    }

    // public function login(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     if (Auth::attempt($credentials)) {
    //         $user = Auth::user();

    //         if (in_array($user->role, ['admin', 'super_admin'])) {
    //             $request->session()->regenerate();
    //             return redirect()->intended(route('admin.dashboard'));
    //         } else {
    //             Auth::logout();
    //             return back()->withErrors([
    //                 'email' => 'You do not have admin access.',
    //             ]);
    //         }
    //     }

    //     return back()->withErrors([
    //         'email' => 'The provided credentials do not match our records.',
    //     ]);
    // }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Use the admin guard now
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Invalid admin credentials.',
        ]);
    }

    public function dashboard()
    {
        // Get dashboard statistics
        $totalUsers = User::where('role', 'fan')->count();
        $totalTeams = Team::where('is_active', true)->count();
        $totalNews = NewsPost::count();
        $totalGames = Game::where('status', 'scheduled')->count();

        // Get recent users (last 10)
        $recentUsers = User::with('team')
            ->where('role', 'fan')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Get recent news posts (last 5)
        $recentPosts = NewsPost::with('team')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalTeams',
            'totalNews',
            'totalGames',
            'recentUsers',
            'recentPosts'
        ));
    }
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout(); // Logout using admin guard

        $request->session()->invalidate(); // Invalidate session
        $request->session()->regenerateToken(); // Regenerate CSRF token

        return redirect()->route('admin.login'); // Redirect to admin login page
    }
}
