<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\NewsPost;
use App\Models\Game;
use App\Models\Product;
use App\Models\FanPhoto;

class Team extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'slug', 'logo_url', 'primary_color', 'secondary_color',
        'founded_year', 'description', 'social_links', 'is_active'
    ];
    protected $casts = [
        'social_links' => 'array',
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function newsPosts()
    {
        return $this->hasMany(NewsPost::class);
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function fanPhotos()
    {
        return $this->hasMany(FanPhoto::class);
    }
}
