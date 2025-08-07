<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\NewsPost;
class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    // Display all news posts for admin management
    public function index()
    {
        $news = NewsPost::orderBy('created_at', 'desc')->paginate(12);

        // $news = [
        //     (object)[
        //         'id' => 1,
        //         'title' => 'Miami Revenue Runners Win Championship!',
        //         'content' => 'Amazing victory in the finals with a score of 95-87.',
        //         'image' => 'images/news1.jpg',
        //         'type' => 'game_result',
        //         'created_at' => '2024-08-05 14:30:00',
        //         'featured' => true,
        //         'views_count' => 1250,
        //         'status' => 'published'
        //     ],
        //     (object)[
        //         'id' => 2,
        //         'title' => 'New Player Trade Announcement',
        //         'content' => 'We welcome John Smith to our team roster.',
        //         'image' => 'images/news2.jpg',
        //         'type' => 'trade',
        //         'created_at' => '2024-08-04 10:15:00',
        //         'featured' => false,
        //         'views_count' => 890,
        //         'status' => 'published'
        //     ]
        // ];

        return view('admin.news.index', compact('news'));
    }

    // Show single news post with admin details
    public function show($id)
    {
        // $news = News::with('author')->findOrFail($id);

        $news = (object)[
            'id' => $id,
            'title' => 'Miami Revenue Runners Win Championship!',
            'content' => 'Amazing victory in the finals with a score of 95-87. The team showed exceptional performance throughout the game.',
            'image' => 'images/news1.jpg',
            'type' => 'game_result',
            'created_at' => '2024-08-05 14:30:00',
            'featured' => true,
            'views_count' => 1250,
            'status' => 'published',
            'author' => 'Admin User'
        ];

        return view('admin.news.show', compact('news'));
    }

    // Show create form
    public function create()
    {
        return view('admin.news.create');
    }

    // Store new news post
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'team_id' => 'nullable|integer', // optional but adds validation
            'content' => 'required',
            'excerpt' => 'nullable',
            'media_urls' => 'nullable|string',
            'post_type' => 'required|in:news,press,highlight,announcement',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'sometimes|boolean',
            'published_at' => 'required|date',
            'is_published' => 'sometimes|boolean'
        ]);

        // dd($request->all());
        // Handle image upload
        $imageName = null;
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('news', $imageName, 'public'); // saves as storage/app/public/news/imagename.jpg
        }

        // Create news entry
        $news = NewsPost::create([
            'title' => $request->title,
            'team_id' => $request->team_id,
            'content' => $request->content,
            'excerpt' => $request->excerpt,
            'media_urls' => $request->media_urls,
            'post_type' => $request->post_type,
            'featured_image' => $imageName,
            'is_featured' => $request->boolean('is_featured'),
            'status' => 'published',
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->published_at
        ]);

        return redirect()->route('admin.news.index')->with('success', 'News post created successfully!');
    }


    // Show edit form
    public function edit($id)
    {
        // In real application:
        $news = NewsPost::findOrFail($id);

        // Fake object (only for testing or mock purposes)
        // $news = (object)[
        //     'id' => $id,
        //     'title' => 'Miami Revenue Runners Win Championship!',
        //     'content' => 'Amazing victory in the finals with a score of 95-87.',
        //     'excerpt' => 'Amazing victory summary...',
        //     'media_urls' => "https://example.com/video1\nhttps://example.com/image2.jpg",
        //     'image' => 'news/images/news1.jpg',
        //     'post_type' => 'game_result',
        //     'is_featured' => true,
        //     'is_published' => true,
        //     'published_at' => now()->toDateTimeString(),
        //     'team_id' => 1,
        //     'status' => 'published'
        // ];

        return view('admin.news.edit', compact('news'));
    }


    // Update news post
   public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'excerpt' => 'nullable|string',
            'media_urls' => 'nullable|string',
            'post_type' => 'required|in:news,highlight,press,announcement',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'sometimes|boolean',
            'is_published' => 'sometimes|boolean',
            'published_at' => 'required|date',
        ]);

        $news = NewsPost::findOrFail($id);

        // Update fields
        $news->title = $request->title;
        $news->content = $request->content;
        $news->excerpt = $request->excerpt;
        $news->media_urls = $request->media_urls;
        $news->post_type = $request->post_type;
        $news->is_featured = $request->boolean('is_featured');
        $news->is_published = $request->boolean('is_published');
        $news->published_at = $request->published_at;
        // $news->status = 'published'; // Always force published

        // Handle image update
        if ($request->hasFile('featured_image')) {
            // Delete old image if it exists
            if ($news->featured_image && Storage::disk('public')->exists('news/' . $news->featured_image)) {
                Storage::disk('public')->delete('news/' . $news->featured_image);
            }

            $image = $request->file('featured_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('news', $imageName, 'public');
            $news->featured_image = $imageName;
        }

        $news->save();

        return redirect()->route('admin.news.index')->with('success', 'News post updated successfully!');
    }

    // Delete news post
    public function destroy($id)
    {
        $news = NewsPost::findOrFail($id);
        // Delete image file if it exists
        if ($news->featured_image && \Storage::disk('public')->exists('news/' . $news->featured_image)) {
            \Storage::disk('public')->delete('news/' . $news->featured_image);
        }
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'News post deleted successfully!');
    }

    // Bulk actions
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,publish,unpublish,feature,unfeature',
            'selected_items' => 'required|array|min:1'
        ]);

        // $ids = $request->selected_items;

        switch($request->action) {
            case 'delete':
                // News::whereIn('id', $ids)->delete();
                $message = 'Selected news posts deleted successfully!';
                break;
            case 'publish':
                // News::whereIn('id', $ids)->update(['status' => 'published']);
                $message = 'Selected news posts published successfully!';
                break;
            case 'unpublish':
                // News::whereIn('id', $ids)->update(['status' => 'draft']);
                $message = 'Selected news posts unpublished successfully!';
                break;
            case 'feature':
                // News::whereIn('id', $ids)->update(['featured' => true]);
                $message = 'Selected news posts featured successfully!';
                break;
            case 'unfeature':
                // News::whereIn('id', $ids)->update(['featured' => false]);
                $message = 'Selected news posts unfeatured successfully!';
                break;
        }

        return redirect()->route('admin.news.index')->with('success', $message);
    }

    // Analytics
    public function analytics()
    {
        // $stats = [
        //     'total_news' => News::count(),
        //     'published_news' => News::where('status', 'published')->count(),
        //     'featured_news' => News::where('featured', true)->count(),
        //     'total_views' => News::sum('views_count'),
        //     'popular_news' => News::orderBy('views_count', 'desc')->limit(5)->get()
        // ];

        $stats = [
            'total_news' => 25,
            'published_news' => 20,
            'featured_news' => 5,
            'total_views' => 15000,
            'popular_news' => [
                (object)['title' => 'Championship Victory', 'views_count' => 2500],
                (object)['title' => 'New Player Trade', 'views_count' => 1800],
                (object)['title' => 'Season Stats', 'views_count' => 1200]
            ]
        ];

        return view('admin.news.analytics', compact('stats'));
    }
}
