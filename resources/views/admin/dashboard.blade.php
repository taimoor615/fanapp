<!-- resources/views/admin/dashboard.blade.php -->
@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-home"></i>
        </span> Dashboard
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="card-title text-md text-muted mb-0">Active Teams</p>
                        <h3 class="font-weight-bold mb-0">{{ $totalTeams ?? 0 }}</h3>
                    </div>
                    <div class="icon-circle bg-gradient-success">
                        <i class="mdi mdi-account-group text-white"></i>
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
                        <p class="card-title text-md text-muted mb-0">News Posts</p>
                        <h3 class="font-weight-bold mb-0">{{ $totalNews ?? 0 }}</h3>
                    </div>
                    <div class="icon-circle bg-gradient-warning">
                        <i class="mdi mdi-newspaper text-white"></i>
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
                        <p class="card-title text-md text-muted mb-0">Upcoming Games</p>
                        <h3 class="font-weight-bold mb-0">{{ $totalGames ?? 0 }}</h3>
                    </div>
                    <div class="icon-circle bg-gradient-info">
                        <i class="mdi mdi-soccer text-white"></i>
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
                        <p class="card-title text-md text-muted mb-0">Total Users</p>
                        <h3 class="font-weight-bold mb-0">{{ $totalUsers ?? 0 }}</h3>
                    </div>
                    <div class="icon-circle bg-gradient-primary">
                        <i class="mdi mdi-account-multiple text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Users -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title">Recent Users</h4>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Team</th>
                                <th>Points</th>
                                <th>Joined</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers ?? [] as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $user->avatar ? asset("storage/avatars/" . $user->avatar) : asset("assets/images/avatars/default-user.png") }}"
                                             alt="profile" class="rounded-circle me-2" width="30">
                                        <div>
                                            <h6 class="mb-0">{{ $user->full_name }}</h6>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->team->name ?? 'No Team' }}</td>
                                <td><span class="badge bg-success">{{ $user->total_points }}</span></td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No users found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent News Posts -->
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title">Recent Posts</h4>
                    <a href="{{ route('admin.news.index') }}" class="btn btn-primary btn-sm">View All</a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($recentPosts ?? [] as $post)
                    <div class="list-group-item border-0 px-0">
                        <div class="d-flex align-items-start">
                            @if($post->featured_image)
                            <img src="{{ asset('storage/news/' . $post->featured_image) }}" alt="news" class="rounded me-3" width="50" height="50">
                            @else
                            <div class="bg-gradient-primary rounded me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="mdi mdi-newspaper text-white"></i>
                            </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ Str::limit($post->title, 50) }}</h6>
                                <p class="text-muted mb-1 small">{{ Str::limit($post->excerpt, 80) }}</p>
                                <small class="text-muted">{{ $post->published_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item border-0 px-0 text-center">
                        <p class="text-muted mb-0">No recent posts</p>
                    </div>
                    @endforelse
                </div>
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
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.news.create') }}" class="btn btn-gradient-primary btn-block">
                            <i class="mdi mdi-plus me-2"></i>Add News Post
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.games.create') }}" class="btn btn-gradient-success btn-block">
                            <i class="mdi mdi-plus me-2"></i>Add Game
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.teams.create') }}" class="btn btn-gradient-warning btn-block">
                            <i class="mdi mdi-plus me-2"></i>Add Team
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.trivia.create') }}" class="btn btn-gradient-info btn-block">
                            <i class="mdi mdi-plus me-2"></i>Add Trivia
                        </a>
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
</style>
@endsection

