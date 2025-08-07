<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Ticket;
use App\Models\TicketReceipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    // Purchase ticket (internal system)
    public function purchase(Request $request, Game $game)
    {
        $request->validate([
            'ticket_type' => 'required|in:general,vip',
            'quantity' => 'required|integer|min:1|max:6'
        ]);

        $user = Auth::user();
        $ticketPrice = $this->getTicketPrice($game, $request->ticket_type);
        $totalAmount = $ticketPrice * $request->quantity;

        // Here you would integrate with payment processor (Stripe, PayPal, etc.)
        // For demo purposes, we'll create confirmed tickets

        $tickets = [];
        for ($i = 0; $i < $request->quantity; $i++) {
            $ticket = Ticket::create([
                'user_id' => $user->id,
                'game_id' => $game->id,
                'ticket_number' => Ticket::generateTicketNumber($game->id),
                'ticket_type' => $request->ticket_type,
                'price' => $ticketPrice,
                'purchase_method' => 'app',
                'status' => 'confirmed',
                'purchased_at' => now(),
                'purchase_data' => [
                    'payment_intent_id' => 'pi_' . uniqid(),
                    'payment_method' => 'card'
                ]
            ]);

            $ticket->generateQRCode();
            $tickets[] = $ticket;
        }

        // Award purchase points
        $purchasePoints = 25 * $request->quantity;
        $user->addPoints($purchasePoints, 'Ticket purchase', $game);

        return response()->json([
            'success' => true,
            'message' => 'Tickets purchased successfully!',
            'tickets' => $tickets,
            'points_earned' => $purchasePoints
        ]);
    }

    // Upload receipt for external ticket verification
    public function uploadReceipt(Request $request, Game $game)
    {
        $request->validate([
            'receipt_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'amount' => 'required|numeric|min:0',
            'purchase_location' => 'required|string|max:255'
        ]);

        $user = Auth::user();

        // Check if user already submitted receipt for this game
        $existingReceipt = TicketReceipt::where('user_id', $user->id)
            ->where('game_id', $game->id)
            ->first();

        if ($existingReceipt && $existingReceipt->status !== 'rejected') {
            return response()->json([
                'success' => false,
                'message' => 'You have already submitted a receipt for this game.'
            ], 422);
        }

        // Store receipt image
        $imagePath = $request->file('receipt_image')->store('receipts', 'public');

        $receipt = TicketReceipt::create([
            'user_id' => $user->id,
            'game_id' => $game->id,
            'receipt_image' => $imagePath,
            'amount' => $request->amount,
            'purchase_location' => $request->purchase_location,
            'status' => 'pending',
            'submitted_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Receipt submitted successfully! It will be reviewed by admin.',
            'receipt' => $receipt
        ]);
    }

    // User's tickets
    public function myTickets()
    {
        $user = Auth::user();

        $tickets = Ticket::with('game.team')
            ->where('user_id', $user->id)
            ->orderBy('purchased_at', 'desc')
            ->paginate(15);

        return view('user.tickets.index', compact('tickets'));
    }

    // Show ticket details with QR code
    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket); // Policy to ensure user owns ticket

        $ticket->load('game.team');

        return view('user.tickets.show', compact('ticket'));
    }

    private function getTicketPrice($game, $ticketType)
    {
        $basePrices = [
            'general' => $game->ticket_price ?? 50,
            'vip' => ($game->ticket_price ?? 50) * 2
        ];

        return $basePrices[$ticketType];
    }
}
