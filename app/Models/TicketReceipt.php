<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'game_id', 'receipt_image', 'amount', 'purchase_location',
        'status', 'admin_notes', 'points_awarded', 'submitted_at', 'reviewed_at', 'reviewed_by'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function approve($adminId, $pointsAwarded = 0, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => $adminId,
            'points_awarded' => $pointsAwarded,
            'admin_notes' => $notes
        ]);

        // Award points to user
        if ($pointsAwarded > 0) {
            $this->user->addPoints($pointsAwarded, 'Ticket purchase verified', $this);
        }
    }

    public function reject($adminId, $notes = null)
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => $adminId,
            'admin_notes' => $notes
        ]);
    }
}
