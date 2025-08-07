@extends('layouts.admin')

@section('title', 'Trivia Managment')

@section('content')
<div class="container-fluid">
    <!-- Admin Header -->
    {{-- <nav class="navbar navbar-expand-lg navbar-dark bg-success mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <i class="fa fa-brain"></i> Trivia Admin
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('admin.trivia.analytics') }}">
                    <i class="fa fa-chart-line"></i> Analytics
                </a>
                <a class="nav-link" href="{{ route('admin.trivia.performance') }}">
                    <i class="fa fa-trophy"></i> Performance
                </a>
                <a class="nav-link" href="{{ route('admin.news.index') }}">
                    <i class="fa fa-newspaper"></i> News
                </a>
            </div>
        </div>
    </nav> --}}

    <div class="row">
        <div class="col-12">
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Questions</h6>
                                    <h3>{{ count($questions) ?? 0 }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fa fa-question-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Active Questions</h6>
                                    <h3>{{ $questions->where('is_active', true)->count() ?? 0 }}</h3>
                                    {{-- <h3>{{ count(array_filter($questions, fn($q) => $q->is_active)) * 8 }}</h3> --}}
                                </div>
                                <div class="align-self-center">
                                    <i class="fa fa-check-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Total Attempts</h6>
                                    <h3>{{ $questions->sum('total_attempts') ?? 0 }}</h3>
                                    {{-- <h3>{{ array_sum(array_column($questions, 'total_attempts')) * 15 }}</h3> --}}
                                </div>
                                <div class="align-self-center">
                                    <i class="fa fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Avg. Success Rate</h6>
                                    <h3>72.5%</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fa fa-percentage fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>

            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fa fa-brain text-success"></i> Trivia Management</h2>
                    <p class="text-muted">Manage team trivia questions and games</p>
                </div>
                <div>
                    <a href="{{ route('admin.trivia.create') }}" class="btn btn-success">
                        <i class="fa fa-plus"></i> Add Question
                    </a>
                    {{-- <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#bulkActionsModal">
                        <i class="fa fa-tasks"></i> Bulk Actions
                    </button> --}}
                </div>
            </div>

            <!-- Filter & Search -->
            {{-- <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-2">
                            <select name="difficulty" class="form-select">
                                <option value="">All Difficulties</option>
                                <option value="easy">Easy</option>
                                <option value="medium">Medium</option>
                                <option value="hard">Hard</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="multiple_choice">Multiple Choice</option>
                                <option value="true_false">True/False</option>
                                <option value="text">Text Answer</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Search questions...">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-success w-100">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div> --}}

            <!-- Questions Grid -->
            <div class="row">
                @foreach($questions as $question)
                <div class="col-md-6 col-xl-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <span class="badge bg-{{ $question->difficulty == 'easy' ? 'success' : ($question->difficulty == 'medium' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($question->difficulty) }}
                                </span>
                                <span class="badge bg-primary ms-1">{{ $question->points ?? 10 }} pts</span>
                            </div>
                            <div>
                                <input type="checkbox" class="form-check-input question-check" value="{{ $question->id }}">
                            </div>
                        </div>

                        <div class="card-body">
                            <h6 class="card-title">{{ Str::limit($question->question, 60) }}</h6>

                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fa fa-list"></i> {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                </small>
                            </div>

                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <small class="text-muted d-block">Attempts</small>
                                    <strong>{{ $question->total_attempts ?? 0 }}</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Correct</small>
                                    <strong class="text-success">{{ $question->correct_attempts ?? 0 }}</strong>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Success Rate</small>
                                    <strong class="text-{{ $question->success_rate >= 70 ? 'success' : ($question->success_rate >= 50 ? 'warning' : 'danger') }}">
                                        {{ $question->success_rate ?? 0 }}%
                                    </strong>
                                </div>
                            </div>

                            <div class="mb-3">
                                @if($question->is_active)
                                    <span class="badge bg-success"><i class="fa fa-check"></i> Active</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fa fa-pause"></i> Inactive</span>
                                @endif
                            </div>

                            <div class="btn-group w-100" role="group">
                                <a href="{{ route('admin.trivia.show', $question->id) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fa fa-eye"></i> View
                                </a>
                                <a href="{{ route('admin.trivia.edit', $question->id) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.trivia.destroy', $question->id) }}" class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this question?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="card-footer text-muted">
                            <small><i class="fa fa-calendar"></i> {{ date('M d, Y', strtotime($question->created_at)) }}</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.trivia.bulk-action') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Bulk Actions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Action</label>
                        <select name="action" class="form-select" required onchange="toggleDifficultyField(this.value)">
                            <option value="">Choose action...</option>
                            <option value="activate">Activate Selected</option>
                            <option value="deactivate">Deactivate Selected</option>
                            <option value="change_difficulty">Change Difficulty</option>
                            <option value="delete">Delete Selected</option>
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="difficultyField">
                        <label class="form-label">New Difficulty</label>
                        <select name="difficulty" class="form-select">
                            <option value="easy">Easy</option>
                            <option value="medium">Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>
                    <input type="hidden" name="selected_items" id="selectedQuestions">
                    <p class="text-muted">Selected questions: <span id="selectedQuestionCount">0</span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Apply Action</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    // Bulk selection functionality
    document.querySelectorAll('.question-check').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedQuestionCount);
    });

    function updateSelectedQuestionCount() {
        const checked = document.querySelectorAll('.question-check:checked');
        const count = checked.length;
        document.getElementById('selectedQuestionCount').textContent = count;

        const ids = Array.from(checked).map(cb => cb.value);
        document.getElementById('selectedQuestions').value = JSON.stringify(ids);
    }

    function toggleDifficultyField(action) {
        const difficultyField = document.getElementById('difficultyField');
        if (action === 'change_difficulty') {
            difficultyField.classList.remove('d-none');
        } else {
            difficultyField.classList.add('d-none');
        }
    }
</script>
@endsection
