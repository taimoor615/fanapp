<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GamePayment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GamePaymentController extends Controller
{
public function show(Game $game)
{
    return view('user.games.upload-payment', compact('game'));
}

public function store(Request $request, GamePayment $gamePayment, Game $game)
{
   $request->validate([
        'receipt' => 'required|image|max:2048'
    ]);

    if ($request->hasFile('receipt')) {
    if ($gamePayment->receipt_path && Storage::disk('public')->exists($gamePayment->receipt_path)) {
        Storage::disk('public')->delete($gamePayment->receipt_path);
    }

    $receiptName = time() . '_' . $gamePayment->user_id . '.' . $request->receipt->extension();

    $request->receipt->storeAs('receipt', $receiptName, 'public');

    $gamePayment->receipt_path = 'receipt/' . $receiptName;
    }

    GamePayment::create([
        'user_id' => Auth::id(),
        'game_id' => $game->id,
        'receipt_path' => $receiptName,
        'status' => 'pending'
    ]);

    return redirect()->route('user.games.index')->with('success', 'Receipt uploaded successfully and is pending approval.');
}

}
