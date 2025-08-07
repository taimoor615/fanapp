@extends('layouts.user-dashboard')

@section('title', 'Games')

@section('content')
<div class="container">
    <!-- Featured News Section -->
    @if(count($featuredNews) > 0)
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="mb-4"><i class="fa fa-star text-warning"></i> Featured News</h2>

                <div class="owl-carousel owl-theme">
                    @foreach($featuredNews as $featured)
                        <div class="item position-relative">
                            <img src="{{ asset('storage/news/' . $featured->featured_image) }}"
                                alt="{{ $featured->title }}"
                                class="img-fluid rounded w-100"
                                style="height: 400px; object-fit: cover;">

                            <div class="carousel-caption bg-dark bg-opacity-75 text-white p-3 rounded position-absolute w-100"
                                style="bottom: 0; left: 0;">
                                <h4 class="text-warning">{{ $featured->title }}</h4>
                                <p>{{ $featured->excerpt }}</p>
                                <a href="{{ route('user.news.show', $featured->id) }}" class="btn btn-warning">
                                    Read More <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif



    <!-- Filter & Search Section -->
    {{-- <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <select name="type" class="form-select">
                                <option value="">All News Types</option>
                                <option value="game_result" {{ $type == 'game_result' ? 'selected' : '' }}>Game Results</option>
                                <option value="trade" {{ $type == 'trade' ? 'selected' : '' }}>Trades</option>
                                <option value="press_release" {{ $type == 'press_release' ? 'selected' : '' }}>Press Releases</option>
                                <option value="player_stats" {{ $type == 'player_stats' ? 'selected' : '' }}>Player Stats</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control"
                                    placeholder="Search news..." value="{{ $search }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- News Grid -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3><i class="fa fa-newspaper text-primary"></i> Latest News</h3>
                <span class="text-muted">{{ count($news) }} articles</span>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach($news as $article)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-0 news-card">
                @if($article->featured_image)
                <div class="position-relative">
                    <img src="{{ asset('storage/news/' . $article->featured_image) }}" class="card-img-top" alt="News Image"
                            style="height: 200px; object-fit: cover;">
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-primary rounded-pill">
                            {{ ucfirst(str_replace('_', ' ', $article->post_type)) }}
                        </span>
                    </div>
                </div>
                @endif

                <div class="card-body d-flex flex-column">
                    <div class="mb-2">
                        <small class="text-muted">
                            <i class="fa fa-calendar"></i> {{ date('M d, Y', strtotime($article->created_at)) }}
                        </small>
                    </div>

                    <h5 class="card-title">{{ $article->title }}</h5>
                    <p class="card-text flex-grow-1">{{ Str::limit($article->content, 120) }}</p>

                    <div class="mt-auto">
                        <a href="{{ route('user.news.show', $article->id) }}" class="btn btn-primary">
                            Read More <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <div class="card-footer bg-transparent border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="social-share">
                            <button class="btn btn-sm btn-outline-primary" onclick="shareNews({{ $article->id }})">
                                <i class="fa fa-share-alt"></i> Share
                            </button>
                        </div>
                        @if($article->is_featured)
                        <div>
                            <i class="fa fa-star text-warning" title="Featured"></i>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Load More Button -->
    <div class="row">
        <div class="col-12 text-center">
            <button class="btn btn-outline-primary btn-lg" id="loadMoreBtn">
                <i class="fa fa-plus"></i> Load More News
            </button>
        </div>
    </div>
</div>
<script>
    // News sharing functionality
    function shareNews(newsId) {
        if (navigator.share) {
            navigator.share({
                title: 'Team News',
                url: window.location.origin + '/news/' + newsId
            });
        } else {
            // Fallback to clipboard
            navigator.clipboard.writeText(window.location.origin + '/news/' + newsId);
            alert('Link copied to clipboard!');
        }
    }

    // Load more news (AJAX)
    document.getElementById('loadMoreBtn').addEventListener('click', function() {
        // Implement AJAX loading here
        console.log('Loading more news...');
    });

    // Add hover effects to news cards
    document.querySelectorAll('.news-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'transform 0.3s ease';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
</script>

<style>
    /* .news-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }

    .carousel-item img {
        filter: brightness(0.7);
    }

    .social-links a:hover {
        transform: scale(1.1);
        transition: transform 0.2s ease;
    } */
</style>
@push('owlcarousel')
    <script>
    $(document).ready(function(){
        $(".owl-carousel").owlCarousel({
            loop: true,
            margin: 10,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 5000,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 1
                },
                992: {
                    items: 1
                }
            }
        });
    });
</script>
@endpush

@endsection
