<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'game_id', 'ticket_number', 'ticket_type', 'price',
        'purchase_method', 'status', 'seat_section', 'seat_row', 'seat_number',
        'qr_code', 'purchase_data', 'purchased_at', 'used_at'
    ];

    protected $casts = [
        'purchase_data' => 'array',
        'purchased_at' => 'datetime',
        'used_at' => 'datetime',
        'price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    // Generate unique ticket number
    public static function generateTicketNumber($gameId)
    {
        do {
            $ticketNumber = 'TKT' . date('Y') . str_pad($gameId, 3, '0', STR_PAD_LEFT) . strtoupper(Str::random(6));
        } while (self::where('ticket_number', $ticketNumber)->exists());

        return $ticketNumber;
    }

    // Generate QR code for ticket
    public function generateQRCode()
    {
        $this->qr_code = 'QR' . $this->id . '_' . time() . '_' . strtoupper(Str::random(8));
        $this->save();
        return $this->qr_code;
    }

    public function markAsUsed()
    {
        $this->update([
            'status' => 'used',
            'used_at' => now()
        ]);
    }

    // Scopes
    public function scopeForGame($query, $gameId)
    {
        return $query->where('game_id', $gameId);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeValid($query)
    {
        return $query->where('status', 'confirmed');
    }
}
