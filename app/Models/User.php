<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Fancam;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'team_id', 'first_name', 'last_name', 'email', 'phone', 'password',
        'avatar', 'date_of_birth', 'gender', 'total_points', 'current_level',
        'push_token', 'preferences', 'role', 'is_active'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'preferences' => 'array',
        'total_points' => 'integer',
        'is_active' => 'boolean',
    ];
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function userPoints()
    {
        return $this->hasMany(UserPoint::class);
    }

    public function redemptions()
    {
        return $this->hasMany(UserRedemption::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function triviaAttempts()
    {
        return $this->hasMany(UserTriviaAttempt::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Game-related relationships
    public function gameAttendances()
    {
        return $this->hasMany(GameAttendance::class);
    }

    public function attendedGames()
    {
        return $this->belongsToMany(Game::class, 'game_attendances')
                    ->withPivot(['attended_at', 'points_earned', 'verification_method', 'verification_data'])
                    ->withTimestamps();
    }

    // Points system methods
    public function addPoints($amount, $reason = null, $relatedModel = null)
    {
        $this->increment('points', $amount);

        // Log the points transaction (you might want to create a PointsTransaction model)
        $this->pointsTransactions()->create([
            'amount' => $amount,
            'type' => 'earned',
            'reason' => $reason,
            'related_type' => $relatedModel ? get_class($relatedModel) : null,
            'related_id' => $relatedModel ? $relatedModel->id : null,
        ]);

        return $this;
    }

    public function deductPoints($amount, $reason = null, $relatedModel = null)
    {
        if ($this->points < $amount) {
            return false; // Insufficient points
        }

        $this->decrement('points', $amount);

        // Log the points transaction
        $this->pointsTransactions()->create([
            'amount' => $amount,
            'type' => 'spent',
            'reason' => $reason,
            'related_type' => $relatedModel ? get_class($relatedModel) : null,
            'related_id' => $relatedModel ? $relatedModel->id : null,
        ]);

        return $this;
    }

    public function getPointsRank()
    {
        return User::where('points', '>', $this->points)->count() + 1;
    }

    public function getTotalGamesAttended()
    {
        return $this->gameAttendances()->count();
    }

    public function getPointsFromGames()
    {
        return $this->gameAttendances()->sum('points_earned');
    }

    // Scopes
    public function scopeByPointsRanking($query)
    {
        return $query->orderBy('points', 'desc');
    }

    public function scopeActiveUsers($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getFormattedPointsAttribute()
    {
        return number_format($this->points);
    }

    public function getPointsLevelAttribute()
    {
        // Simple level system based on points
        if ($this->points >= 10000) return 'Diamond';
        if ($this->points >= 5000) return 'Gold';
        if ($this->points >= 2000) return 'Silver';
        if ($this->points >= 500) return 'Bronze';
        return 'Rookie';
    }

    public function getPointsLevelColorAttribute()
    {
        switch ($this->points_level) {
            case 'Diamond': return 'primary';
            case 'Gold': return 'warning';
            case 'Silver': return 'secondary';
            case 'Bronze': return 'danger';
            default: return 'success';
        }
    }

    // Check if user attended specific game
    public function hasAttendedGame($gameId)
    {
        return $this->gameAttendances()->where('game_id', $gameId)->exists();
    }

    // Get user's recent game activity
    public function getRecentGameActivity($limit = 5)
    {
        return $this->gameAttendances()
                    ->with('game')
                    ->orderBy('attended_at', 'desc')
                    ->limit($limit)
                    ->get();
    }
    public function fancams()
    {
        return $this->hasMany(Fancam::class);
    }
}
