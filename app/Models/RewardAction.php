<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\UserPoint;

class RewardAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'points_value', 'action_type', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function userPoints()
    {
        return $this->hasMany(UserPoint::class, 'action_id');
    }
}
