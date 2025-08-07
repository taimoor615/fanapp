@extends('layouts.user-dashboard')

@section('title', 'Games')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h2 class="card-title mb-2">
                        <i class="fa fa-gamepad"></i> Games
                    </h2>
                    <p class="card-text">Track games, mark attendance, and earn points!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ $filter === 'upcoming' ? 'active' : '' }}"
                               href="{{ route('user.games.index', ['filter' => 'upcoming']) }}">
                                <i class="fa fa-clock-o"></i> Upcoming
                                <span class="badge badge-dark ml-1">
                                    {{ \App\Models\Game::upcoming()->count() }}
                                </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $filter === 'completed' ? 'active' : '' }}"
                               href="{{ route('user.games.index', ['filter' => 'completed']) }}">
                                <i class="fa fa-check-circle"></i> Completed
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $filter === 'featured' ? 'active' : '' }}"
                               href="{{ route('user.games.index', ['filter' => 'featured']) }}">
                                <i class="fa fa-star"></i> Featured
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $filter === 'attended' ? 'active' : '' }}"
                               href="{{ route('user.games.index', ['filter' => 'attended']) }}">
                                <i class="fa fa-user-check"></i> My Attended
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Games Grid -->
    <div class="row">
        @forelse($games as $game)
            <div class="col-lg-6 col-xl-4 mb-4">
                <div class="card h-100 {{ $game->is_featured ? 'border-warning' : '' }}">
                    @if($game->is_featured)
                        <div class="card-header bg-warning text-dark">
                            <i class="fa fa-star"></i> Featured Game
                        </div>
                    @endif

                    <div class="card-body p-3">
                        <!-- Game Header -->
                        <div class="d-flex justify-content-end align-items-start mb-3">
                            <span class="badge badge-{{
                                $game->status === 'completed' ? 'success' :
                                ($game->status === 'live' ? 'danger' :
                                ($game->status === 'cancelled' ? 'dark' : 'info'))
                            }}">
                                {{ ucfirst($game->status) }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h5 class="card-title mb-0">
                                <span class="badge badge-primary">{{ $game->team->name }}</span>
                                <small class="text-muted">vs</small>
                                {{ $game->opponent_team }}
                            </h5>
                        </div>

                        <!-- Game Details -->
                        <div class="mb-3">
                            <p class="mb-1">
                                <i class="fa fa-calendar"></i>
                                <strong>{{ $game->formatted_game_date }}</strong>
                            </p>
                            <p class="mb-1">
                                <i class="fa fa-map-marker-alt"></i>
                                {{ $game->venue }}
                                <span class="badge badge-{{ $game->home_away === 'home' ? 'success' : 'warning' }} ml-2">
                                    {{ ucfirst($game->home_away) }}
                                </span>
                            </p>
                            @if($game->is_upcoming)
                                <p class="mb-1 text-primary">
                                    <i class="fa fa-hourglass-half"></i>
                                    {{ $game->countdown }}
                                </p>
                            @endif
                        </div>

                        <!-- Score (if completed) -->
                        @if($game->status === 'completed' && $game->home_score !== null)
                            <div class="alert alert-info mb-3">
                                <h6 class="mb-0">Final Score</h6>
                                <strong>{{ $game->home_score }} - {{ $game->away_score }}</strong>
                                @if($game->winner && $game->winner !== 'tie')
                                    <br><small class="text-success">Winner: {{ $game->winner }}</small>
                                @elseif($game->winner === 'tie')
                                    <br><small class="text-warning">Game ended in a tie</small>
                                @endif
                            </div>
                        @endif

                        <!-- Attendance Status -->
                        {{-- @if(in_array($game->id, $userAttendedGames))
                            <div class="alert alert-success mb-3">
                                <i class="fa fa-check-circle"></i>
                                <strong>Attended!</strong> You earned {{ $game->attendance_points }} points
                            </div>
                        @elseif($game->status === 'completed' || $game->status === 'live')
                            <div class="mb-3">
                                <button class="btn btn-outline-success btn-sm attendance-btn"
                                        data-game-id="{{ $game->id }}"
                                        {{ in_array($game->id, $userAttendedGames) ? 'disabled' : '' }}>
                                    <i class="fa fa-map-marker-alt"></i>
                                    Mark Attendance ({{ $game->attendance_points }} pts)
                                </button>
                            </div>
                        @endif --}}

                        <!-- Points Info -->
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fa fa-coins"></i>
                                Attendance worth {{ $game->attendance_points }} points
                            </small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center gap-2">
                            <a href="{{ route('user.games.show', $game) }}" class="btn btn-primary btn-sm">
                                <i class="fa fa-eye"></i> View Details
                            </a>
                            @if($game->ticket_url && $game->is_upcoming)
                                <a href="{{ $game->ticket_url }}" target="_blank" class="btn btn-success btn-sm">
                                    <i class="fa fa-ticket-alt"></i> Buy Tickets
                                    @if($game->ticket_price)
                                        <small>({{ $game->ticket_price }})</small>
                                    @endif
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fa fa-gamepad fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No games found</h5>
                        <p class="text-muted">
                            @if($filter === 'upcoming')
                                No upcoming games scheduled at the moment.
                            @elseif($filter === 'completed')
                                No completed games to show.
                            @elseif($filter === 'featured')
                                No featured games available.
                            @elseif($filter === 'attended')
                                You haven't attended any games yet.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($games->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $games->appends(['filter' => $filter])->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Attendance Modal -->
<div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Attendance</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>How would you like to verify your attendance?</p>
                <div class="btn-group-vertical w-100" role="group">
                    <button type="button" class="btn btn-outline-primary mb-2" onclick="markAttendance('manual')">
                        <i class="fa fa-hand-point-up"></i> Manual Check-in
                    </button>
                    <button type="button" class="btn btn-outline-success mb-2" onclick="requestGPS()">
                        <i class="fa fa-map-marker-alt"></i> GPS Verification
                    </button>
                    <button type="button" class="btn btn-outline-info" onclick="showQRScanner()">
                        <i class="fa fa-qrcode"></i> Scan QR Code
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentGameId = null;

$(document).ready(function() {
    // Attendance button click
    $('.attendance-btn').click(function() {
        currentGameId = $(this).data('game-id');
        $('#attendanceModal').modal('show');
    });
});

function markAttendance(method, data = null) {
    if (!currentGameId) return;

    const requestData = {
        _token: '{{ csrf_token() }}',
        verification_method: method
    };

    if (data) {
        requestData.verification_data = data;
    }

    $.ajax({
        url: `/dashboard/games/${currentGameId}/attend`,
        method: 'POST',
        data: requestData,
        success: function(response) {
            $('#attendanceModal').modal('hide');
            if (response.success) {
                // Show success message
                showAlert('success', `Attendance marked! You earned ${response.points_earned} points. Total points: ${response.total_points}`);
                // Reload page to update UI
                setTimeout(() => location.reload(), 2000);
            } else {
                showAlert('error', response.message);
            }
        },
        error: function(xhr) {
            $('#attendanceModal').modal('hide');
            const message = xhr.responseJSON?.message || 'Failed to mark attendance';
            showAlert('error', message);
        }
    });
}

function requestGPS() {
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const gpsData = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                };
                markAttendance('gps', gpsData);
            },
            function(error) {
                showAlert('error', 'Unable to get your location. Please try manual check-in.');
            }
        );
    } else {
        showAlert('error', 'Geolocation is not supported by this browser.');
    }
}

function showQRScanner() {
    // This would integrate with a QR code scanner library
    // For now, we'll simulate with a prompt
    const qrCode = prompt('Enter QR code:');
    if (qrCode) {
        markAttendance('qr_code', { qr_code: qrCode });
    }
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `;

    // Insert at the top of the container
    $('.container-fluid').prepend(alertHtml);

    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endpush
@endsection
