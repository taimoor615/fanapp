<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TriviaQuestion extends Model
{
    use HasFactory;
    protected $table = 'trivia_questions'; // If your table name is 'trivias'. Adjust if different.

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'team_id',
        'question',
        'options',
        'correct_answer',
        'difficulty',
        'category',
        'type',
        'is_active',
        'points'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'difficulty' => 'medium',
        'is_active' => true,
    ];

    /**
     * Get the team that owns the trivia.
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function userAttempts()
    {
        return $this->hasMany(UserTriviaAttempt::class, 'question_id');
    }

    // Total attempts
    public function getTotalAttemptsAttribute()
    {
        return $this->userAttempts->count();
    }

    // Correct attempts
    public function getCorrectAttemptsAttribute()
    {
        return $this->userAttempts->where('is_correct', 1)->count();
    }

    // Success rate (correct / total * 100)
    public function getSuccessRateAttribute()
    {
        $total = $this->total_attempts;
        if ($total === 0) return 0;

        return round(($this->correct_attempts / $total) * 100);
    }
}
