@extends('layouts.admin')

@section('title', 'Trivia Managment')

@section('content')
<div class="container-fluid">
    <!-- Admin Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-info mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-chart-line"></i> Trivia Analytics
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('admin.trivia.index') }}">
                    <i class="fas fa-arrow-left"></i> Back to Questions
                </a>
            </div>
        </div>
    </nav>

    <!-- Analytics Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body text-center">
                    <i class="fas fa-question-circle fa-3x mb-3"></i>
                    <h3>{{ $stats['total_questions'] }}</h3>
                    <p class="mb-0">Total Questions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-gradient-success text-white">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <h3>{{ $stats['active_questions'] }}</h3>
                    <p class="mb-0">Active Questions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-gradient-info text-white">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x mb-3"></i>
                    <h3>{{ $stats['unique_players'] }}</h3>
                    <p class="mb-0">Unique Players</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-gradient-warning text-white">
                <div class="card-body text-center">
                    <i class="fas fa-percentage fa-3x mb-3"></i>
                    <h3>{{ $stats['average_accuracy'] }}%</h3>
                    <p class="mb-0">Average Accuracy</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-pie"></i> Questions by Difficulty</h5>
                </div>
                <div class="card-body">
                    <canvas id="difficultyChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-bar"></i> Questions by Category</h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-history"></i> Recent Player Activity</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Player</th>
                                    <th>Question</th>
                                    <th>Result</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stats['recent_activity'] as $activity)
                                <tr>
                                    <td>{{ $activity->user }}</td>
                                    <td>{{ Str::limit($activity->question, 40) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $activity->is_correct ? 'success' : 'danger' }}">
                                            {{ $activity->is_correct ? 'Correct' : 'Incorrect' }}
                                        </span>
                                    </td>
                                    <td><small class="text-muted">Just now</small></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-trophy"></i> Top Performing Questions</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Team founding year?</strong>
                                <br><small class="text-muted">Easy • 150 attempts</small>
                            </div>
                            <span class="badge bg-success rounded-pill">80%</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Championship wins?</strong>
                                <br><small class="text-muted">Medium • 89 attempts</small>
                            </div>
                            <span class="badge bg-warning rounded-pill">65%</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Player records?</strong>
                                <br><small class="text-muted">Hard • 45 attempts</small>
                            </div>
                            <span class="badge bg-danger rounded-pill">40%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Difficulty Distribution Chart
    const difficultyData = @json($stats['difficulty_breakdown']);
    const difficultyLabels = difficultyData.map(item => item.difficulty.charAt(0).toUpperCase() + item.difficulty.slice(1));
    const difficultyCounts = difficultyData.map(item => item.count);

    new Chart(document.getElementById('difficultyChart'), {
        type: 'doughnut',
        data: {
            labels: difficultyLabels,
            datasets: [{
                data: difficultyCounts,
                backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Category Distribution Chart
    const categoryData = @json($stats['category_breakdown']);
    const categoryLabels = categoryData.map(item => item.category || 'Uncategorized');
    const categoryCounts = categoryData.map(item => item.count);

    new Chart(document.getElementById('categoryChart'), {
        type: 'bar',
        data: {
            labels: categoryLabels,
            datasets: [{
                label: 'Questions',
                data: categoryCounts,
                backgroundColor: '#17a2b8',
                borderColor: '#138496',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
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
</script>
@endsection
