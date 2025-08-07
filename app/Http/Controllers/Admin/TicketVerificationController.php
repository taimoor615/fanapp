<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketReceipt;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketVerificationController extends Controller
{
    public function index()
    {
        $receipts = TicketReceipt::with(['user', 'game.team', 'reviewer'])
            ->orderBy('submitted_at', 'desc')
            ->paginate(20);

        return view('admin.tickets.receipts', compact('receipts'));
    }

    public function show(TicketReceipt $receipt)
    {
        $receipt->load(['user', 'game.team']);
        return view('admin.tickets.receipt-detail', compact('receipt'));
    }

    public function approve(Request $request, TicketReceipt $receipt)
    {
        $request->validate([
            'points_awarded' => 'required|integer|min:0|max:200',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $receipt->approve(
            auth('admin')->id(),
            $request->points_awarded,
            $request->admin_notes
        );

        return redirect()->back()->with('success', 'Receipt approved successfully!');
    }

    public function reject(Request $request, TicketReceipt $receipt)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500'
        ]);

        $receipt->reject(auth('admin')->id(), $request->admin_notes);

        return redirect()->back()->with('success', 'Receipt rejected.');
    }

    // Verify ticket at venue (QR code scan)
    public function verifyTicket(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string'
        ]);

        $ticket = Ticket::where('qr_code', $request->qr_code)
            ->with(['user', 'game'])
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid ticket QR code'
            ], 404);
        }

        if ($ticket->status === 'used') {
            return response()->json([
                'success' => false,
                'message' => 'Ticket already used',
                'ticket' => $ticket
            ], 422);
        }

        if ($ticket->status !== 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid ticket status: ' . $ticket->status
            ], 422);
        }

        // Mark ticket as used
        $ticket->markAsUsed();

        // Also mark attendance if not already marked
        if (!$ticket->game->hasUserAttended($ticket->user_id)) {
            $ticket->game->markUserAttendance($ticket->user_id, 'qr_code', [
                'qr_code' => $request->qr_code,
                'ticket_id' => $ticket->id
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ticket verified successfully!',
            'ticket' => $ticket,
            'user' => $ticket->user,
            'game' => $ticket->game
        ]);
    }
}
