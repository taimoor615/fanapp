@extends('layouts.user-dashboard')

@section('title', 'Games')

@section('content')
<div class="container">
    <!-- Welcome Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="jumbotron bg-gradient-success text-white p-5 rounded shadow">
                <h1 class="display-4">
                    <i class="fa fa-brain"></i> Team Trivia Challenge
                </h1>
                <p class="lead">Test your knowledge about the Miami Revenue Runners and earn rewards!</p>
                <hr class="my-4 border-white">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h3>{{ $userStats['total_points'] }}</h3>
                        <p>Total Points</p>
                    </div>
                    <div class="col-md-3">
                        <h3>{{ $userStats['accuracy_percentage'] }}%</h3>
                        <p>Accuracy Rate</p>
                    </div>
                    <div class="col-md-3">
                        <h3>#{{ $userStats['rank'] }}</h3>
                        <p>Your Rank</p>
                    </div>
                    <div class="col-md-3">
                        <h3>{{ $userStats['streak'] }}</h3>
                        <p>Current Streak</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Challenge -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-warning shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fa fa-calendar-day"></i> Daily Challenge
                        <span class="badge bg-dark ms-2">{{ $dailyChallenge->points }} Bonus Points</span>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5>{{ $dailyChallenge->question }}</h5>
                            <p class="text-muted mb-0">
                                <i class="fa fa-clock"></i> Available for 24 hours
                                <span class="ms-3"><i class="fa fa-medal"></i> {{ ucfirst($dailyChallenge->difficulty) }} Level</span>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('user.trivia.daily-challenge') }}" class="btn btn-warning btn-lg">
                                <i class="fa fa-play"></i> Take Challenge
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Game Modes -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-4"><i class="fa fa-gamepad"></i> Game Modes</h3>
        </div>
    </div>

    <div class="row">
        <!-- Quick Play -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow border-0 game-mode-card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fa fa-bolt fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">Quick Play</h5>
                    <p class="card-text">5 random questions, mixed difficulty. Perfect for a quick brain workout!</p>
                    <div class="mb-3">
                        <span class="badge bg-info">5 Questions</span>
                        <span class="badge bg-secondary">Mixed Difficulty</span>
                    </div>
                    {{-- <a href="{{ route('user.trivia.play') }}?mode=quick" class="btn btn-primary btn-lg w-100">
                        <i class="fa fa-play"></i> Quick Play
                    </a> --}}
                </div>
            </div>
        </div>

        <!-- Practice Mode -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow border-0 game-mode-card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fa fa-dumbbell fa-3x text-success"></i>
                    </div>
                    <h5 class="card-title">Practice Mode</h5>
                    <p class="card-text">Choose your difficulty level and practice specific topics at your own pace.</p>
                    <div class="mb-3">
                        <span class="badge bg-success">Easy</span>
                        <span class="badge bg-warning">Medium</span>
                        <span class="badge bg-danger">Hard</span>
                    </div>
                    {{-- <div class="btn-group-vertical w-100">
                        <a href="{{ route('user.trivia.practice', 'easy') }}" class="btn btn-success">Easy Practice</a>
                        <a href="{{ route('user.trivia.practice', 'medium') }}" class="btn btn-warning">Medium Practice</a>
                        <a href="{{ route('user.trivia.practice', 'hard') }}" class="btn btn-danger">Hard Practice</a>
                    </div> --}}
                </div>
            </div>
        </div>

        <!-- Championship Mode -->
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow border-0 game-mode-card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fa fa-trophy fa-3x text-warning"></i>
                    </div>
                    <h5 class="card-title">Championship Mode</h5>
                    <p class="card-text">15 challenging questions. Only the true fans can master this!</p>
                    <div class="mb-3">
                        <span class="badge bg-warning">15 Questions</span>
                        <span class="badge bg-danger">Hard Mode</span>
                    </div>
                    {{-- <a href="{{ route('user.trivia.play') }}?mode=championship" class="btn btn-warning btn-lg w-100">
                        <i class="fa fa-crown"></i> Championship
                    </a> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Performance -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5><i class="fa fa-chart-line"></i> Your Recent Performance</h5>
                </div>
                <div class="card-body">
                    <canvas id="performanceChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header">
                    <h5><i class="fa fa-award"></i> Your Achievements</h5>
                </div>
                <div class="card-body">
                    <div class="achievement-list">
                        <div class="achievement-item mb-3 p-2 bg-light rounded">
                            <i class="fa fa-fire text-danger"></i>
                            <strong>Hot Streak</strong>
                            <br><small class="text-muted">Answer 5 in a row correctly</small>
                        </div>
                        <div class="achievement-item mb-3 p-2 bg-light rounded">
                            <i class="fa fa-star text-warning"></i>
                            <strong>Rising Star</strong>
                            <br><small class="text-muted">Score 500+ points</small>
                        </div>
                        <div class="achievement-item mb-3 p-2 bg-light rounded">
                            <i class="fa fa-brain text-info"></i>
                            <strong>Trivia Master</strong>
                            <br><small class="text-muted">Complete 50 questions</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-primary text-white shadow">
                <div class="card-body text-center">
                    <h4><i class="fa fa-rocket"></i> Ready to Challenge Yourself?</h4>
                    <p class="mb-4">Jump right into the action with our most popular game modes!</p>
                    <div class="btn-group" role="group">
                        <a href="{{ route('user.trivia.play') }}" class="btn btn-warning btn-lg">
                            <i class="fa fa-play"></i> Start Playing
                        </a>
                        <a href="{{ route('user.trivia.leaderboard') }}" class="btn btn-light btn-lg">
                            <i class="fa fa-trophy"></i> View Rankings
                        </a>
                        <a href="{{ route('user.trivia.history') }}" class="btn btn-outline-light btn-lg">
                            <i class="fa fa-history"></i> My History
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-success text-white mt-5 py-4">
    <div class="container text-center">
        <h5>Miami Revenue Runners Trivia</h5>
        <p>Test your knowledge, earn rewards, and show your team spirit!</p>
    </div>
</footer>
<script>
    // Performance Chart
    const ctx = document.getElementById('performanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'This Week'],
            datasets: [{
                label: 'Accuracy %',
                data: [65, 68, 72, 75, {{ $userStats['accuracy_percentage'] }}],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // Game mode card hover effects
    document.querySelectorAll('.game-mode-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
            this.style.transition = 'transform 0.3s ease';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
</script>

<style>
    .jumbotron {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .game-mode-card:hover {
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }

    .achievement-item {
        transition: all 0.3s ease;
    }

    .achievement-item:hover {
        background-color: #e9ecef !important;
        transform: scale(1.05);
    }
</style>
@endsection
