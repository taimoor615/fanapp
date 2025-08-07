<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create()
    {
        $teams = Team::where('is_active', true)->get();
        return view('auth.register', compact('teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'team_id' => 'required|exists:teams,id',
            'phone' => 'nullable|string|max:15',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'team_id' => $request->team_id,
            'phone' => $request->phone,
            'role' => 'fan',
            'total_points' => 100, // Welcome bonus
        ]);

        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Welcome! You have been registered successfully and earned 100 welcome points!');
    }
}
