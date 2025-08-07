<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\Models\Team;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teams = Team::all();
        return view('user.profile', compact('user','teams'));
    }
    public function update(Request $request)
    {
        $user = Auth::user();

    $validated = $request->validate([
        'first_name'       => 'required|string|max:255',
        'last_name'        => 'required|string|max:255',
        'phone'            => 'nullable|string|max:20',
        'gender'           => 'nullable|in:male,female,other',
        'date_of_birth'    => 'nullable|date',
        'avatar'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'password'         => 'nullable|confirmed',
    ]);

    // Handle avatar upload
    if ($request->hasFile('avatar')) {
        // Delete old avatar if exists
        if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        $avatarName = time() . '_' . $user->id . '.' . $request->avatar->extension();
        $request->avatar->storeAs('avatars', $avatarName, 'public');
        $user->avatar = $avatarName;
    }

    // Update fields
    // $user->fill(collect($validated)->except('avatar')->toArray());
    $fillableData = collect($validated)->except(['password','avatar'])->toArray();
    $user->fill($fillableData);

    // Password change
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    if ($request->filled('date_of_birth')) {
        $validated['date_of_birth'] = date('Y-m-d', strtotime($request->date_of_birth));
    }

    $user->save();
        return redirect()->route('user.profile')->with('success', 'Profile updated successfully.');
    }
}
