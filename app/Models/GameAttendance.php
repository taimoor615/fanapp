<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Game;

class GameAttendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'game_id',
        'attended_at',
        'points_earned',
        'verification_method',
        'verification_data'
    ];

    protected $casts = [
        'attended_at' => 'datetime',
        'verification_data' => 'array'
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

    // Scopes
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForGame($query, $gameId)
    {
        return $query->where('game_id', $gameId);
    }

    public function scopeVerifiedBy($query, $method)
    {
        return $query->where('verification_method', $method);
    }

    // Accessors
    public function getFormattedAttendedAtAttribute()
    {
        return $this->attended_at->format('M d, Y \a\t g:i A');
    }
}
