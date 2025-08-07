@extends('layouts.admin')

@section('title', 'Game Statistics')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h2 class="card-title mb-2">
                        <i class="fas fa-chart-bar"></i> Game Statistics Dashboard
                    </h2>
                    <p class="card-text">Comprehensive analytics for fan engagement and game performance</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ number_format($stats['total_games']) }}</h4>
                            <p class="mb-0">Total Games</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-gamepad fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ number_format($stats['upcoming_games']) }}</h4>
                            <p class="mb-0">Upcoming Games</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-plus fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ number_format($stats['total_attendances']) }}</h4>
                            <p class="mb-0">Total Check-ins</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ number_format($stats['points_distributed']) }}</h4>
                            <p class="mb-0">Points Distributed</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-coins fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Games Performance Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line"></i> Games & Attendance Trends
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="gamesChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Performing Games -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-trophy"></i> Top Attended Games
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($stats['top_attended_games'] as $game)
                        <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div>
                                <strong>{{ Str::limit($game->opponent_team, 15) }}</strong>
                                <br><small class="text-muted">{{ $game->game_date->format('M d, Y') }}</small>
                            </div>
                            <div class="text-right">
                                <span class="badge badge-primary">{{ $game->attendances_count }}</span>
                                <br><small class="text-muted">attendees</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3">No games with attendance yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Attendance Analytics -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie"></i> Verification Methods
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="verificationChart" height="150"></canvas>
                    <div class="mt-3">
                        @php
                            $verificationMethods = \App\Models\GameAttendance::selectRaw('verification_method, COUNT(*) as count')
                                ->groupBy('verification_method')
                                ->pluck('count', 'verification_method');
                        @endphp
                        <div class="row text-center">
                            <div class="col-4">
                                <h6 class="text-primary">{{ $verificationMethods['manual'] ?? 0 }}</h6>
                                <small class="text-muted">Manual</small>
                            </div>
                            <div class="col-4">
                                <h6 class="text-success">{{ $verificationMethods['gps'] ?? 0 }}</h6>
                                <small class="text-muted">GPS</small>
                            </div>
                            <div class="col-4">
                                <h6 class="text-info">{{ $verificationMethods['qr_code'] ?? 0 }}</h6>
                                <small class="text-muted">QR Code</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock"></i> Recent Check-ins
                    </h5>
                </div>
                <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                    @php
                        $recentAttendances = \App\Models\GameAttendance::with(['user', 'game'])
                            ->orderBy('attended_at', 'desc')
                            ->limit(10)
                            ->get();
                    @endphp

                    @forelse($recentAttendances as $attendance)
                        <div class="d-flex justify-content-between align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div>
                                <strong>{{ $attendance->user->name }}</strong>
                                <br><small class="text-muted">{{ $attendance->game->opponent_team }}</small>
                            </div>
                            <div class="text-right">
                                <span class="badge badge-{{
                                    $attendance->verification_method === 'manual' ? 'primary' :
                                    ($attendance->verification_method === 'gps' ? 'success' : 'info')
                                }}">
                                    {{ ucfirst($attendance->verification_method) }}
                                </span>
                                <br><small class="text-muted">{{ $attendance->attended_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3">No recent check-ins</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Fan Engagement Metrics -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users"></i> Fan Engagement Analysis
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            <h4 class="text-primary">{{ number_format($stats['avg_attendance'] ?? 0, 1) }}</h4>
                            <small class="text-muted">Avg Attendance per Game</small>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <h4 class="text-success">{{ number_format($stats['attendance_rate'] ?? 0, 1) }}%</h4>
                            <small class="text-muted">Check-in Rate</small>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <h4 class="text-info">{{ number_format($stats['repeat_attendees'] ?? 0) }}</h4>
                            <small class="text-muted">Repeat Attendees</small>
                        </div>
                        <div class="col-md-3 text-center mb-3">
                            <h4 class="text-warning">{{ number_format($stats['avg_points_per_game'] ?? 0) }}</h4>
                            <small class="text-muted">Avg Points per Game</small>
                        </div>
                    </div>

                    <!-- Engagement Trends Chart -->
                    <canvas id="engagementChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Team Performance -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar"></i> Team Performance
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $teamStats = \App\Models\Game::selectRaw('
                            team_id,
                            COUNT(*) as total_games,
                            SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_games,
                            AVG(CASE WHEN status = "completed" AND home_score IS NOT NULL THEN
                                CASE WHEN (game_type = "home" AND home_score > away_score) OR
                                         (game_type = "away" AND away_score > home_score)
                                THEN 1 ELSE 0 END
                            END) * 100 as win_rate
                        ')
                        ->with('team')
                        ->groupBy('team_id')
                        ->having('completed_games', '>', 0)
                        ->get();
                    @endphp

                    @forelse($teamStats as $stat)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>{{ $stat->team->name }}</strong>
                                <span class="badge badge-{{ $stat->win_rate >= 50 ? 'success' : 'warning' }}">
                                    {{ number_format($stat->win_rate, 1) }}% Win Rate
                                </span>
                            </div>
                            <div class="progress mt-1" style="height: 6px;">
                                <div class="progress-bar bg-{{ $stat->win_rate >= 50 ? 'success' : 'warning' }}"
                                     style="width: {{ $stat->win_rate }}%"></div>
                            </div>
                            <small class="text-muted">
                                {{ $stat->completed_games }} games played
                            </small>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3">No completed games yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Analytics Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table"></i> Game-by-Game Analytics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="gamesAnalyticsTable">
                            <thead>
                                <tr>
                                    <th>Game</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Attendees</th>
                                    <th>Points Given</th>
                                    <th>Engagement Rate</th>
                                    <th>Verification Methods</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $detailedGames = \App\Models\Game::with(['team', 'attendances'])
                                        ->withCount('attendances')
                                        ->orderBy('game_date', 'desc')
                                        ->limit(20)
                                        ->get();
                                @endphp

                                @foreach($detailedGames as $game)
                                    <tr>
                                        <td>
                                            <strong>{{ $game->team->name }}</strong><br>
                                            <small class="text-muted">vs {{ $game->opponent_team }}</small>
                                        </td>
                                        <td>{{ $game->game_date->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge badge-{{
                                                $game->status === 'completed' ? 'success' :
                                                ($game->status === 'live' ? 'danger' : 'info')
                                            }}">
                                                {{ ucfirst($game->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $game->attendances_count }}</span>
                                        </td>
                                        <td>
                                            {{ $game->attendances->sum('points_earned') }}
                                        </td>
                                        <td>
                                            @php
                                                $engagementRate = $game->attendances_count > 0 ?
                                                    ($game->attendances->where('verification_method', '!=', 'manual')->count() / $game->attendances_count) * 100 : 0;
                                            @endphp
                                            <span class="badge badge-{{ $engagementRate >= 50 ? 'success' : 'warning' }}">
                                                {{ number_format($engagementRate, 1) }}%
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $methods = $game->attendances->groupBy('verification_method');
                                            @endphp
                                            <small>
                                                @if($methods->has('manual'))
                                                    <span class="badge badge-secondary">M:{{ $methods['manual']->count() }}</span>
                                                @endif
                                                @if($methods->has('gps'))
                                                    <span class="badge badge-success">G:{{ $methods['gps']->count() }}</span>
                                                @endif
                                                @if($methods->has('qr_code'))
                                                    <span class="badge badge-info">Q:{{ $methods['qr_code']->count() }}</span>
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.games.show', $game) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.games.attendances', $game) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-users"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#gamesAnalyticsTable').DataTable({
        "pageLength": 10,
        "order": [[ 1, "desc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 7 }
        ]
    });

    // Games Trend Chart
    const gamesCtx = document.getElementById('gamesChart').getContext('2d');
    new Chart(gamesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_keys($stats['games_by_month']->toArray())) !!}.map(month => {
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                return months[month - 1];
            }),
            datasets: [{
                label: 'Games',
                data: {!! json_encode(array_values($stats['games_by_month']->toArray())) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Verification Methods Chart
    const verificationCtx = document.getElementById('verificationChart').getContext('2d');
    const verificationData = {!! json_encode($verificationMethods) !!};

    new Chart(verificationCtx, {
        type: 'doughnut',
        data: {
            labels: ['Manual', 'GPS', 'QR Code'],
            datasets: [{
                data: [
                    verificationData.manual || 0,
                    verificationData.gps || 0,
                    verificationData.qr_code || 0
                ],
                backgroundColor: [
                    '#6c757d',
                    '#28a745',
                    '#17a2b8'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Engagement Chart
    const engagementCtx = document.getElementById('engagementChart').getContext('2d');
    // This would need actual engagement data from your controller
    new Chart(engagementCtx, {
        type: 'bar',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
            datasets: [{
                label: 'Engagement Score',
                data: [85, 92, 78, 95], // This should come from your controller
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
});
</script>
@endpush
@endsection
