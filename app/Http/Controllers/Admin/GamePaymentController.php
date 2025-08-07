<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GamePayment;
use Illuminate\Http\Request;

class GamePaymentController extends Controller
{
    public function index(Game $game)
    {
        $payments = GamePayment::with('user')->where('game_id', $game->id)->get();

        return view('admin.games.show', compact('game', 'payments'));
    }

    public function updateStatus(Request $request, GamePayment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $payment->status = $request->status;
        $payment->save();

        return back()->with('success', 'Payment status updated successfully.');
    }
}
