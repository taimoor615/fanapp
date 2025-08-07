<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GamePayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'game_id',
        'receipt_path',
        'status',
    ];

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function game()
    {
        return $this->belongsTo(Game::class);
    }

    // Helper method to get full receipt URL
    public function getReceiptUrlAttribute()
    {
        return asset('storage/' . $this->receipt_path);
    }

    // Status Checkers

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isApproved()
    {
        return $this->status === 'approved';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
