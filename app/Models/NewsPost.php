<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Team;

class NewsPost extends Model
{
    use HasFactory;
    protected $fillable = [
        'team_id', 'title', 'content', 'excerpt', 'featured_image',
        'media_urls', 'post_type', 'is_featured', 'is_published',
        'published_at', 'views_count'
    ];

    protected $casts = [
        'media_urls' => 'array',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $dates = [
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
                    ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('post_type', $type);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->limit($limit);
    }

    // Accessors
    public function getStatusAttribute()
    {
        if ($this->is_published) {
            return 'published';
        }
        return 'draft';
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('M d, Y');
    }

    public function getFormattedPublishedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('M d, Y g:i A') : null;
    }

    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $readingTime = ceil($wordCount / 200); // Average reading speed: 200 words per minute
        return $readingTime;
    }

    public function getExcerptAttribute($value)
    {
        if ($value) {
            return $value;
        }

        return \Illuminate\Support\Str::limit(strip_tags($this->content), 200);
    }

    // Mutators
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = trim($value);
    }

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = $value;
    }

    // Helper methods
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function getMediaUrlsArray()
    {
        if (is_string($this->media_urls)) {
            return json_decode($this->media_urls, true) ?? [];
        }

        return $this->media_urls ?? [];
    }

    public function hasImage()
    {
        return !empty($this->featured_image);
    }

    public function getImageUrl()
    {
        if ($this->hasImage()) {
            return asset($this->featured_image);
        }

        return asset('images/default-news.jpg'); // Fallback image
    }

    public function getPostTypeFormatted()
    {
        return ucfirst(str_replace('_', ' ', $this->post_type));
    }

    // Relationships (if you have teams, users, etc.)
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    // Static methods for quick stats
    public static function getStats()
    {
        return [
            'total' => self::count(),
            'published' => self::where('is_published', true)->count(),
            'draft' => self::where('is_published', false)->count(),
            'featured' => self::where('is_featured', true)->count(),
            'total_views' => self::sum('views_count'),
        ];
    }

    public static function getTypeStats()
    {
        return self::selectRaw('post_type, COUNT(*) as count')
                  ->groupBy('post_type')
                  ->pluck('count', 'post_type')
                  ->toArray();
    }

    public static function getMonthlyStats($months = 12)
    {
        return self::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as count')
                  ->where('created_at', '>=', Carbon::now()->subMonths($months))
                  ->groupBy('month', 'year')
                  ->orderBy('year', 'asc')
                  ->orderBy('month', 'asc')
                  ->get();
    }
}
