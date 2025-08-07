<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsPost;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    // Display news feed for users
    public function index(Request $request)
    {
        $type = $request->get('post_type');
        // $search = $request->get('search');

        $news = NewsPost::orderBy('created_at', 'desc')->paginate(12);

        $query = NewsPost::where('is_published', 'published')
                     ->orderBy('featured_image', 'desc')
                     ->orderBy('created_at', 'desc');

        // if ($type) {
        //     $query->where('post_type', $type);
        // }
        // $news = $query->paginate(9);

        // dd($news);

        $featuredNews = NewsPost::where('is_featured', true)->where('is_published', true)->limit(3)->get();

        $featuredNews = $news->filter(fn($item) => $item->is_featured);

        // dd($featuredNews);
        // return view('user.news.index', compact('news', 'featuredNews', 'type', 'search'));
        return view('user.news.index', compact('news', 'featuredNews','type'));
        // if ($search) {
        //     $query->where(function($q) use ($search) {
        //         $q->where('title', 'like', "%{$search}%")
        //           ->orWhere('content', 'like', "%{$search}%");
        //     });
        // }


        // $news = [
        //     (object)[
        //         'id' => 1,
        //         'title' => 'Miami Revenue Runners Win Championship!',
        //         'content' => 'Amazing victory in the finals with a score of 95-87.',
        //         'image' => 'images/news1.jpg',
        //         'type' => 'game_result',
        //         'created_at' => '2024-08-05 14:30:00',
        //         'featured' => true,
        //         'excerpt' => 'The team delivered an outstanding performance...'
        //     ],
        //     (object)[
        //         'id' => 2,
        //         'title' => 'New Player Trade Announcement',
        //         'content' => 'We welcome John Smith to our team roster.',
        //         'image' => 'images/news2.jpg',
        //         'type' => 'trade',
        //         'created_at' => '2024-08-04 10:15:00',
        //         'featured' => false,
        //         'excerpt' => 'Exciting addition to strengthen our lineup...'
        //     ]
        // ];


        // return view('user.news.index', compact('news', 'featuredNews', 'type', 'search'));
    }

    // Show single news post for users
    public function show($id)
    {
        $news = NewsPost::where('is_published', true)->findOrFail($id);
        $news->increment('views_count'); // Track views
        $relatedNews = NewsPost::where('post_type', $news->post_type)
                          ->where('id', '!=', $id)
                          ->where('is_published', true)
                          ->limit(3)
                          ->get();

        // $news = (object)[
        //     'id' => $id,
        //     'title' => 'Miami Revenue Runners Win Championship!',
        //     'content' => 'Amazing victory in the finals with a score of 95-87. The team showed exceptional performance throughout the game, with outstanding plays from all team members.',
        //     'image' => 'images/news1.jpg',
        //     'type' => 'game_result',
        //     'created_at' => '2024-08-05 14:30:00',
        //     'featured' => true,
        //     'views_count' => 1251,
        //     'author' => 'Team Reporter'
        // ];

        // $relatedNews = [
        //     (object)['id' => 3, 'title' => 'Season Highlights', 'image' => 'images/news3.jpg'],
        //     (object)['id' => 4, 'title' => 'Player Interviews', 'image' => 'images/news4.jpg']
        // ];

        return view('user.news.show', compact('news', 'relatedNews'));
    }

    // API endpoint for mobile app news feed
    public function apiIndex(Request $request)
    {
        $limit = $request->get('limit', 10);
        $type = $request->get('type');
        $offset = $request->get('offset', 0);

        // $query = News::where('status', 'published')
        //              ->orderBy('featured', 'desc')
        //              ->orderBy('created_at', 'desc')
        //              ->limit($limit)
        //              ->offset($offset);
        //
        // if ($type) {
        //     $query->where('type', $type);
        // }
        //
        // $news = $query->get();

        $news = [
            [
                'id' => 1,
                'title' => 'Miami Revenue Runners Win Championship!',
                'content' => 'Amazing victory in the finals with a score of 95-87.',
                'excerpt' => 'The team delivered an outstanding performance...',
                'image' => url('images/news1.jpg'),
                'type' => 'game_result',
                'featured' => true,
                'created_at' => '2024-08-05T14:30:00Z',
                'views_count' => 1250
            ],
            [
                'id' => 2,
                'title' => 'New Player Trade Announcement',
                'content' => 'We welcome John Smith to our team roster.',
                'excerpt' => 'Exciting addition to strengthen our lineup...',
                'image' => url('images/news2.jpg'),
                'type' => 'trade',
                'featured' => false,
                'created_at' => '2024-08-04T10:15:00Z',
                'views_count' => 890
            ]
        ];

        return response()->json([
            'status' => 'success',
            'data' => $news,
            'pagination' => [
                'current_page' => 1,
                'has_more' => false,
                'total' => count($news)
            ]
        ]);
    }

    // Get featured news for mobile
    public function apiFeatured()
    {
        // $featured = News::where('featured', true)
        //                 ->where('status', 'published')
        //                 ->limit(5)
        //                 ->get();

        $featured = [
            [
                'id' => 1,
                'title' => 'Championship Victory!',
                'image' => url('images/news1.jpg'),
                'type' => 'game_result'
            ]
        ];

        return response()->json([
            'status' => 'success',
            'data' => $featured
        ]);
    }
}
