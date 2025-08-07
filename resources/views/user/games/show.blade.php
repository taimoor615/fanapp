@extends('layouts.user-dashboard')

@section('title', 'Game Details')

@section('content')
<div class="container-fluid">
    <!-- Game Hero Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-4 mb-2">
                                <span class="badge badge-light text-primary mr-3">{{ $game->team->name }}</span>
                                <small class="text-white-50">VS</small>
                                <span class="ml-3">{{ $game->opponent_team }}</span>
                            </h1>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="lead mb-1">
                                        <i class="fa fa-calendar"></i> {{ $game->formatted_game_date }}
                                    </p>
                                    <p class="mb-1">
                                        <i class="fa fa-map-marker-alt"></i> {{ $game->venue }}
                                        <span class="badge badge-{{ $game->game_type === 'home' ? 'success' : 'warning' }} ml-2">
                                            {{ ucfirst($game->game_type) }} Game
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    @if($game->is_upcoming)
                                        <div class="text-center">
                                            <h5 class="text-white-50">Game Starts In</h5>
                                            <div id="countdown" class="h3" data-date="{{ $game->game_date->toISOString() }}">
                                                {{ $game->countdown }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="game-status">
                                <span class="badge badge-{{
                                    $game->status === 'completed' ? 'success' :
                                    ($game->status === 'live' ? 'danger' :
                                    ($game->status === 'cancelled' ? 'dark' : 'info'))
                                }} badge-lg">
                                    {{ ucfirst($game->status) }}
                                </span>
                                @if($game->is_featured)
                                    <br><span class="badge badge-warning mt-2">
                                        <i class="fa fa-star"></i> Featured Game
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Score Card (if game is completed or live) -->
            @if($game->status === 'completed' || $game->status === 'live')
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">
                            <i class="fa fa-trophy"></i>
                            {{ $game->status === 'live' ? 'Live Score' : 'Final Score' }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-5">
                                <h2 class="team-name {{ $game->game_type === 'home' ? 'text-primary' : '' }}">
                                    @if($game->game_type === 'home')
                                        {{ $game->team->name }}
                                    @else
                                        {{ $game->opponent_team }}
                                    @endif
                                </h2>
                                <div class="score-display">
                                    <span class="display-1 font-weight-bold">
                                        {{ $game->game_type === 'home' ? ($game->home_score ?? 0) : ($game->away_score ?? 0) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-2 d-flex align-items-center justify-content-center">
                                <div class="vs-separator">
                                    <span class="h4 text-muted">VS</span>
                                </div>
                            </div>
                            <div class="col-5">
                                <h2 class="team-name {{ $game->game_type === 'away' ? 'text-primary' : '' }}">
                                    @if($game->game_type === 'away')
                                        {{ $game->team->name }}
                                    @else
                                        {{ $game->opponent_team }}
                                    @endif
                                </h2>
                                <div class="score-display">
                                    <span class="display-1 font-weight-bold">
                                        {{ $game->game_type === 'away' ? ($game->home_score ?? 0) : ($game->away_score ?? 0) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($game->winner && $game->status === 'completed')
                            <div class="text-center mt-3">
                                @if($game->winner === 'tie')
                                    <span class="badge badge-warning badge-lg">Game Ended in a Tie</span>
                                @else
                                    <span class="badge badge-success badge-lg">
                                        <i class="fa fa-crown"></i> Winner: {{ $game->winner }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Attendance Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-users"></i> Attendance & Rewards
                    </h5>
                </div>
                <div class="card-body">
                    @if($hasAttended)
                        <div class="alert alert-success">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="alert-heading mb-1">
                                        <i class="fa fa-check-circle"></i> You Attended This Game!
                                    </h6>
                                    <p class="mb-1">
                                        Attended on: <strong>{{ $userAttendance->formatted_attended_at }}</strong>
                                    </p>
                                    <p class="mb-0">
                                        Points Earned: <strong>{{ $userAttendance->points_earned }}</strong>
                                        <small class="text-muted">({{ ucfirst($userAttendance->verification_method) }} verification)</small>
                                    </p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <span class="badge badge-success badge-lg">
                                        <i class="fa fa-coins"></i> +{{ $userAttendance->points_earned }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @elseif($game->status === 'completed' || $game->status === 'live')
                        <div class="alert alert-info">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="alert-heading mb-1">
                                        <i class="fa fa-map-marker-alt"></i> Mark Your Attendance
                                    </h6>
                                    <p class="mb-0">
                                        Earn <strong>{{ $game->attendance_points }} points</strong> by checking in at the game!
                                    </p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <button class="btn btn-success attendance-btn" data-game-id="{{ $game->id }}">
                                        <i class="fa fa-map-marker-alt"></i> Check In
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-light">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="mb-1">
                                        <i class="fa fa-coins"></i> Attendance Reward
                                    </h6>
                                    <p class="mb-0">
                                        Attend this game to earn <strong>{{ $game->attendance_points }} points</strong>
                                    </p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <span class="badge badge-primary badge-lg">
                                        {{ $game->attendance_points }} Points
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Total Attendees -->
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fa fa-users"></i>
                            Total Attendees: <strong>{{ $gameAttendees}}</strong>
                        </small>
                    </div>
                </div>
            </div>

            <!-- Game Description -->
            @if($game->description)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fa fa-info-circle"></i> Game Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ $game->description }}</p>
                    </div>
                </div>
            @endif

            <!-- Fan Engagement Section -->
            {{-- <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-camera"></i> Fan Zone
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('user.fan-photos.index') }}?game={{ $game->id }}" class="btn btn-outline-primary btn-block">
                                <i class="fa fa-camera"></i><br>
                                <small>Share Photos</small>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('user.trivia.index') }}?game={{ $game->id }}" class="btn btn-outline-success btn-block">
                                <i class="fa fa-question-circle"></i><br>
                                <small>Game Trivia</small>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button class="btn btn-outline-info btn-block" onclick="shareGame()">
                                <i class="fa fa-share"></i><br>
                                <small>Share Game</small>
                            </button>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Previous Matchups -->
            {{-- <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-history"></i> Previous Matchups
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $previousGames = \App\Models\Game::where('team_id', $game->team_id)
                            ->where('opponent_team', $game->opponent_team)
                            ->where('status', 'completed')
                            ->where('id', '!=', $game->id)
                            ->orderBy('game_date', 'desc')
                            ->limit(3)
                            ->get();
                    @endphp

                    @forelse($previousGames as $prevGame)
                        <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div>
                                <strong>{{ $prevGame->formatted_game_date }}</strong>
                                <br><small class="text-muted">{{ $prevGame->venue }}</small>
                            </div>
                            <div class="text-center">
                                <span class="badge badge-light">
                                    {{ $prevGame->home_score }} - {{ $prevGame->away_score }}
                                </span>
                                @if($prevGame->winner && $prevGame->winner !== 'tie')
                                    <br><small class="text-success">W: {{ $prevGame->winner }}</small>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3">
                            <i class="fa fa-info-circle"></i>
                            No previous matchups found
                        </p>
                    @endforelse
                </div>
            </div> --}}
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Ticket Information -->
            @if($game->is_upcoming && $game->ticket_url)
                <div class="card mb-4 border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fa fa-ticket-alt"></i> Get Tickets
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($game->ticket_price)
                            <p class="card-text">
                                <strong>Starting from: ${{ number_format($game->ticket_price, 2) }}</strong>
                            </p>
                        @endif
                        <a href="{{ $game->ticket_url }}" target="_blank" class="btn btn-success btn-block btn-lg w-100">
                            <i class="fa fa-external-link-alt"></i> Buy Tickets
                        </a>

                        @php
                            $payment = $payments[$game->id] ?? null;
                        @endphp

                        <div class="text-start mt-3">
                            @if(!$payment)
                            <a href="{{ route('user.games.payment', $game) }}" class="btn btn-warning btn-block w-100">
                                <i class="fa fa-upload"></i> Upload Payment Receipt
                            </a>
                            @elseif($payment->status === 'approved')
                                <p class="btn btn-primary btn-sm w-100">
                                    <i class="fa fa-check-circle"></i> Approved
                                </p>
                            @elseif($payment->status === 'pending')
                                <p class="btn btn-secondary btn-sm w-100">
                                    <i class="fa fa-clock"></i> Pending Approval
                                </p>
                            @elseif($payment->status === 'rejected')
                                <p class="btn btn-secondary btn-sm w-100">
                                    <i class="fa fa-times"></i> Payment Rejected
                                </p>
                                <a href="{{ route('user.games.payment', $game) }}" class="btn btn-warning btn-block w-100">
                                    <i class="fa fa-upload"></i> Upload Payment Receipt
                                </a>
                            @endif
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="fa fa-coins"></i>
                            Earn {{ $game->attendance_points }} points by attending!
                        </small>
                    </div>
                </div>
            @endif

            <!-- Venue Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-map-marker-alt"></i> Venue Details
                    </h5>
                </div>
                <div class="card-body">
                    <h6>{{ $game->venue }}</h6>
                    <p class="text-muted">
                        <i class="fa fa-home"></i>
                        {{ $game->game_type === 'home' ? 'Home Game' : 'Away Game' }}
                    </p>

                    <!-- Add venue map or directions link here -->
                    <a href="https://maps.google.com/?q={{ urlencode($game->venue) }}"
                       target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-directions"></i> Get Directions
                    </a>
                </div>
            </div>

            <!-- Game Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-chart-bar"></i> Quick Stats
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary">{{ $gameAttendees }}</h4>
                            <small class="text-muted">Attendees</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $game->attendance_points }}</h4>
                            <small class="text-muted">Points</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Games -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fa fa-calendar-alt"></i> Next Games
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $nextGames = \App\Models\Game::where('team_id', $game->team_id)
                            ->where('id', '!=', $game->id)
                            ->upcoming()
                            ->limit(3)
                            ->get();
                    @endphp

                    @forelse($nextGames as $nextGame)
                        <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div>
                                <strong>{{ $nextGame->opponent_team }}</strong>
                                <br><small class="text-muted">{{ $nextGame->game_date->format('M d, g:i A') }}</small>
                            </div>
                            <a href="{{ route('user.games.show', $nextGame) }}" class="btn btn-sm btn-outline-primary">
                                View
                            </a>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3">
                            No upcoming games
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include the attendance modal from the index page -->
{{-- @include('user.games.partials.attendance-modal') --}}

@push('scripts')
<script>
// Countdown timer
function updateCountdown() {
    const countdownElement = document.getElementById('countdown');
    if (!countdownElement) return;

    const gameDate = new Date(countdownElement.getAttribute('data-date'));
    const now = new Date();
    const timeDiff = gameDate - now;

    if (timeDiff > 0) {
        const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);

        countdownElement.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
    } else {
        countdownElement.innerHTML = 'Game Started!';
    }
}

// Update countdown every second
setInterval(updateCountdown, 1000);
updateCountdown();

// Share game function
function shareGame() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $game->team->name }} vs {{ $game->opponent_team }}',
            text: 'Check out this game on {{ $game->formatted_game_date }}',
            url: window.location.href
        });
    } else {
        // Fallback - copy URL to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Game URL copied to clipboard!');
        });
    }
}

// Attendance functionality (same as in index page)
let currentGameId = null;

$(document).ready(function() {
    $('.attendance-btn').click(function() {
        currentGameId = $(this).data('game-id');
        $('#attendanceModal').modal('show');
    });
});

// Include the same attendance functions from index page
// ... (markAttendance, requestGPS, showQRScanner, showAlert functions)
</script>
@endpush
@endsection
