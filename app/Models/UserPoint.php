<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\RewardAction;

class UserPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'action_id', 'points_earned', 'description',
        'reference_type', 'reference_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function action()
    {
        return $this->belongsTo(RewardAction::class);
    }
}
