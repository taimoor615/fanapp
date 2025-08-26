<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

use App\Models\Team;
use App\Models\Fancam;
use App\Models\GameAttendance;
use App\Models\GamePayment;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'opponent_team',
        'game_date',
        'venue',
        'home_away',
        'status',
        'home_score',
        'away_score',
        'description',
        'ticket_url',
        'ticket_price',
        'attendance_points',
        'is_featured'
    ];

    protected $casts = [
        'game_date' => 'datetime',
        'ticket_price' => 'decimal:2',
        'is_featured' => 'boolean'
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function attendances()
    {
        return $this->hasMany(GameAttendance::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'game_attendances')
                    ->withPivot(['attended_at', 'points_earned', 'verification_method', 'verification_data'])
                    ->withTimestamps();
    }

    public function scopeUpcoming($query)
    {
        return $query->where('game_date', '>', now())
                    ->where('status', 'scheduled')
                    ->orderBy('game_date', 'asc');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')
                    ->orderBy('game_date', 'desc');
    }
     public function scopeForTeam($query, $teamId)
    {
        return $query->where('team_id', $teamId);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Accessors
    public function getFormattedGameDateAttribute()
    {
        return $this->game_date->format('M d, Y \a\t g:i A');
    }

    public function getIsLiveAttribute()
    {
        return $this->status === 'live';
    }

    public function getIsUpcomingAttribute()
    {
        return $this->game_date > now() && $this->status === 'scheduled';
    }

    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    public function getCountdownAttribute()
    {
        if ($this->game_date > now()) {
            return $this->game_date->diffForHumans();
        }
        return null;
    }

    public function getWinnerAttribute()
    {
        if ($this->status !== 'completed' || is_null($this->home_score) || is_null($this->away_score)) {
            return null;
        }

        if ($this->home_score > $this->away_score) {
            return $this->home_away === 'home' ? $this->team->name : $this->opponent_team;
        } elseif ($this->away_score > $this->home_score) {
            return $this->home_away === 'away' ? $this->team->name : $this->opponent_team;
        }

        return 'tie';
    }

    // Methods
    public function hasUserAttended($userId)
    {
        return $this->attendances()->where('user_id', $userId)->exists();
    }

    public function markUserAttendance($userId, $verificationMethod = 'manual', $verificationData = null)
    {
        if ($this->hasUserAttended($userId)) {
            return false; // Already attended
        }

        $attendance = $this->attendances()->create([
            'user_id' => $userId,
            'attended_at' => now(),
            'points_earned' => $this->attendance_points,
            'verification_method' => $verificationMethod,
            'verification_data' => $verificationData
        ]);

        // Update user points
        $user = User::find($userId);
        if ($user) {
            $user->increment('points', $this->attendance_points);
        }

        return $attendance;
    }

    public function payments()
    {
        return $this->hasMany(GamePayment::class);
    }

    public function approvedPayments()
    {
        return $this->hasMany(GamePayment::class)->where('status', 'approved');
    }

    public function getTotalAttendanceCount()
    {
        return $this->attendances()->count();
    }
    public function fancams()
    {
        return $this->hasMany(Fancam::class);
    }
}
