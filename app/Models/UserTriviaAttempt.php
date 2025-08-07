<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTriviaAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'question_id',
        'selected_answer',
        'is_correct',
        'completed_at',
        'points_earned'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'points_earned' => 'integer',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function question()
    {
        return $this->belongsTo(TriviaQuestion::class, 'question_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public static function getUserStats($userId)
    {
        $attempts = self::where('user_id', $userId)->get();

        $totalAttempts = $attempts->count();
        $correctAnswers = $attempts->where('is_correct', true)->count();
        $totalPoints = $attempts->sum('points_earned');
        $streak = self::getCurrentStreak($userId); // We'll define this below
        $rank = self::calculateUserRank($userId);  // Optional: if you want ranking

        $accuracy = $totalAttempts > 0 ? round(($correctAnswers / $totalAttempts) * 100) : 0;

        return [
            'total_points' => $totalPoints,
            'accuracy_percentage' => $accuracy,
            'rank' => $rank,
            'streak' => $streak,
        ];
    }

    public static function getCurrentStreak($userId)
    {
        $attempts = self::where('user_id', $userId)
            ->orderByDesc('completed_at')
            ->get();

        $streak = 0;
        foreach ($attempts as $attempt) {
            if ($attempt->is_correct) {
                $streak++;
            } else {
                break;
            }
        }

        return $streak;
    }

    public static function calculateUserRank($userId)
    {
        $userPoints = self::where('user_id', $userId)->sum('points_earned');

        $userScores = self::selectRaw('user_id, SUM(points_earned) as total_points')
            ->groupBy('user_id')
            ->orderByDesc('total_points')
            ->get();

        foreach ($userScores as $index => $user) {
            if ($user->user_id == $userId) {
                return $index + 1; // Rank starts at 1
            }
        }

        return null; // Not found
    }


}
