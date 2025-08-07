<!-- resources/views/user/dashboard.blade.php -->
@extends('layouts.user-dashboard')

@section('title', 'Fan Dashboard')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
        </span> Welcome back, {{ auth()->user()->first_name }}!
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Dashboard <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    <!-- User Stats Cards -->
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-title text-md text-muted mb-0">Total Points</p>
                        <h3 class="font-weight-bold mb-0 text-primary">{{ auth()->user()->total_points }}</h3>
                    </div>
                    <div class="icon-circle bg-gradient-primary">
                        <i class="mdi mdi-star text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-title text-md text-muted mb-0">Fan Level</p>
                        <h3 class="font-weight-bold mb-0 text-success">{{ auth()->user()->current_level }}</h3>
                    </div>
                    <div class="icon-circle bg-gradient-success">
                        <i class="mdi mdi-trophy text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-title text-md text-muted mb-0">Team Rank</p>
                        <h3 class="font-weight-bold mb-0 text-warning">#{{ $userRank }}</h3>
                    </div>
                    <div class="icon-circle bg-gradient-warning">
                        <i class="mdi mdi-chart-line text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-title text-md text-muted mb-0">Games Attended</p>
                        <h3 class="font-weight-bold mb-0 text-info">{{ auth()->user()->games_attended ?? 0 }}</h3>
                    </div>
                    <div class="icon-circle bg-gradient-info">
                        <i class="mdi mdi-soccer text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Latest Team News -->
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title">Latest Team News</h4>
                    <a href="{{ route('user.news.index') }}" class="btn btn-primary btn-sm">View All</a>
                </div>
                <div class="row">
                    @forelse($latestNews as $news)
                    <div class="col-md-6 mb-3">
                        <div class="card border-0 shadow-sm">
                            @if($news->featured_image)
                            <img src="{{ $news->featured_image }}" class="card-img-top" alt="{{ $news->title }}" style="height: 150px; object-fit: cover;">
                            @endif
                            <div class="card-body p-3">
                                <h6 class="card-title">{{ Str::limit($news->title, 60) }}</h6>
                                <p class="card-text text-muted small">{{ Str::limit($news->excerpt, 80) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ $news->published_at->format('M d, Y') }}</small>
                                    <a href="{{ route('user.news.show', $news) }}" class="btn btn-sm btn-outline-primary">Read</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">No recent news available</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Games & Points Activity -->
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Upcoming Games</h4>
                @forelse($upcomingGames as $game)
                <div class="d-flex align-items-center mb-3 p-2 border rounded">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">vs {{ $game->opponent_team }}</h6>
                        <p class="text-muted mb-0 small">{{ $game->game_date->format('M d, Y H:i') }}</p>
                        <small class="text-muted">{{ $game->venue }}</small>
                    </div>
                    <div>
                        <a href="{{ route('user.games.show', $game) }}" class="btn btn-sm btn-outline-primary">Details</a>
                    </div>
                </div>
                @empty
                <p class="text-muted">No upcoming games</p>
                @endforelse

                <hr>

                <h5 class="card-title">Recent Points</h5>
                @forelse($recentPoints as $point)
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <small>{{ $point->action->name }}</small>
                        <br>
                        <small class="text-muted">{{ $point->created_at->format('M d') }}</small>
                    </div>
                    <span class="badge bg-success">+{{ $point->points_earned }}</span>
                </div>
                @empty
                <p class="text-muted small">No recent point activity</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Quick Actions</h4>
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <a href="{{ route('user.trivia.index') }}" class="btn btn-gradient-primary btn-block">
                            <i class="mdi mdi-help-circle me-2"></i>Play Trivia
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="{{ route('user.fan-photos.index') }}" class="btn btn-gradient-success btn-block">
                            <i class="mdi mdi-camera me-2"></i>Upload Photo
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="{{ route('user.rewards.catalog') }}" class="btn btn-gradient-warning btn-block">
                            <i class="mdi mdi-gift me-2"></i>Redeem Rewards
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="{{ route('user.store.index') }}" class="btn btn-gradient-info btn-block">
                            <i class="mdi mdi-shopping me-2"></i>Team Store
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="{{ route('user.leaderboard') }}" class="btn btn-gradient-danger btn-block">
                            <i class="mdi mdi-trophy me-2"></i>Leaderboard
                        </a>
                    </div>
                    <div class="col-md-2 mb-3">
                        <a href="{{ route('user.profile') }}" class="btn btn-gradient-dark btn-block">
                            <i class="mdi mdi-account me-2"></i>My Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Team Info Section -->
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card" style="background: linear-gradient(135deg, {{ auth()->user()->team->primary_color ?? '#007bff' }}, {{ auth()->user()->team->secondary_color ?? '#6c757d' }});">
            <div class="card-body text-white">
                <div class="d-flex align-items-center">
                    @if(auth()->user()->team->logo_url)
                    <img src="{{ auth()->user()->team->logo_url }}" alt="Team Logo" class="me-3" style="width: 60px; height: 60px; object-fit: contain;">
                    @endif
                    <div>
                        <h4 class="mb-1">{{ auth()->user()->team->name ?? 'Your Team' }}</h4>
                        <p class="mb-0">{{ auth()->user()->team->description ?? 'Supporting our amazing team!' }}</p>
                        @if(auth()->user()->team->founded_year)
                        <small class="opacity-75">Founded: {{ auth()->user()->team->founded_year }}</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card {
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.btn-gradient-primary {
    background: linear-gradient(45deg, #667eea, #764ba2);
    border: none;
}

.btn-gradient-success {
    background: linear-gradient(45deg, #56ab2f, #a8e6cf);
    border: none;
}

.btn-gradient-warning {
    background: linear-gradient(45deg, #f093fb, #f5576c);
    border: none;
}

.btn-gradient-info {
    background: linear-gradient(45deg, #4facfe, #00f2fe);
    border: none;
}

.btn-gradient-danger {
    background: linear-gradient(45deg, #fa709a, #fee140);
    border: none;
}

.btn-gradient-dark {
    background: linear-gradient(45deg, #434343, #000000);
    border: none;
}
</style>
@endsection
