@extends('layouts.admin')

@section('title', 'Game Fancam Statistics')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-chart-bar mr-2"></i>
                        Fancam Statistics - {{ $game->opponent_team }}
                    </h4>
                    <div>
                        <a href="{{ route('admin.fancam.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa fa-arrow-left mr-1"></i>
                            Back to All Photos
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Game Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="fa fa-gamepad mr-1"></i>
                                    Game Information
                                </h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <strong>Game Name:</strong> {{ $game->opponent_team }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Date:</strong> {{ $game->game_date ?? 'N/A' }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Location:</strong> {{ $game->venue ?? 'N/A' }}
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Status:</strong> {{ $game->status ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body text-center">
                                    <div class="h4 mb-0">{{ number_format($stats['total_fancams']) }}</div>
                                    <small>Total Photos</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body text-center">
                                    <div class="h4 mb-0">{{ number_format($stats['approved_fancams']) }}</div>
                                    <small>Approved</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                            <div class="card bg-warning text-white h-100">
                                <div class="card-body text-center">
                                    <div class="h4 mb-0">{{ number_format($stats['pending_fancams']) }}</div>
                                    <small>Pending</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                            <div class="card bg-info text-white h-100">
                                <div class="card-body text-center">
                                    <div class="h4 mb-0">{{ number_format($stats['total_participants']) }}</div>
                                    <small>Participants</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                            <div class="card bg-secondary text-white h-100">
                                <div class="card-body text-center">
                                    <div class="h4 mb-0">{{ number_format($stats['total_points_awarded']) }}</div>
                                    <small>Points Awarded</small>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-md-4 col-sm-6 mb-3">
                            <div class="card bg-dark text-white h-100">
                                <div class="card-body text-center">
                                    <div class="h4 mb-0">
                                        {{ $stats['total_fancams'] > 0 ? round($stats['total_points_awarded'] / $stats['total_fancams'], 1) : 0 }}
                                    </div>
                                    <small>Avg Points</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Row -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fa fa-pie-chart mr-1"></i>
                                        Status Distribution
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="h3 text-success mb-1">
                                                {{ $stats['total_fancams'] > 0 ? round(($stats['approved_fancams'] / $stats['total_fancams']) * 100, 1) : 0 }}%
                                            </div>
                                            <small class="text-muted">Approved</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="h3 text-warning mb-1">
                                                {{ $stats['total_fancams'] > 0 ? round(($stats['pending_fancams'] / $stats['total_fancams']) * 100, 1) : 0 }}%
                                            </div>
                                            <small class="text-muted">Pending</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="h3 text-danger mb-1">
                                                {{ $stats['total_fancams'] > 0 ? round((($stats['total_fancams'] - $stats['approved_fancams'] - $stats['pending_fancams']) / $stats['total_fancams']) * 100, 1) : 0 }}%
                                            </div>
                                            <small class="text-muted">Rejected</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fa fa-users mr-1"></i>
                                        Participation Overview
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>Unique Participants:</strong><br>
                                            <span class="text-muted">{{ $stats['total_participants'] }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Photos per Participant:</strong><br>
                                            <span class="text-muted">
                                                {{ $stats['total_participants'] > 0 ? round($stats['total_fancams'] / $stats['total_participants'], 1) : 0 }}
                                            </span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>Total Points:</strong><br>
                                            <span class="text-muted">{{ number_format($stats['total_points_awarded']) }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Points per Participant:</strong><br>
                                            <span class="text-muted">
                                                {{ $stats['total_participants'] > 0 ? round($stats['total_points_awarded'] / $stats['total_participants'], 1) : 0 }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Photos Grid -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="fa fa-images mr-1"></i>
                                All Photos for This Game
                            </h6>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="filterStatus('all')">
                                    All
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-success" onclick="filterStatus('approved')">
                                    Approved
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="filterStatus('pending')">
                                    Pending
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="filterStatus('rejected')">
                                    Rejected
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($fancams->count() > 0)
                                <div class="row">
                                    @foreach($fancams as $fancam)
                                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4 fancam-item" data-status="{{ $fancam->status }}">
                                            <div class="card h-100">
                                                <div class="position-relative">
                                                    <img src="{{ $fancam->image_url }}"
                                                         class="card-img-top"
                                                         alt="{{ $fancam->title }}"
                                                         style="height: 150px; object-fit: cover;">

                                                    <!-- Status Badge -->
                                                    @if($fancam->status == 'approved')
                                                        <span class="badge badge-success position-absolute" style="top: 10px; right: 10px;">
                                                            <i class="fa fa-check mr-1"></i>Approved
                                                        </span>
                                                    @elseif($fancam->status == 'pending')
                                                        <span class="badge badge-warning position-absolute" style="top: 10px; right: 10px;">
                                                            <i class="fa fa-clock mr-1"></i>Pending
                                                        </span>
                                                    @else
                                                        <span class="badge badge-danger position-absolute" style="top: 10px; right: 10px;">
                                                            <i class="fa fa-times mr-1"></i>Rejected
                                                        </span>
                                                    @endif

                                                    <!-- Points Badge -->
                                                    <span class="badge badge-primary position-absolute" style="bottom: 10px; left: 10px;">
                                                        <i class="fa fa-star mr-1"></i>{{ $fancam->points }} pts
                                                    </span>
                                                </div>

                                                <div class="card-body">
                                                    <h6 class="card-title">
                                                        {{ $fancam->title ?: 'Untitled' }}
                                                    </h6>

                                                    <div class="mb-2">
                                                        <small class="text-muted">
                                                            <i class="fa fa-user mr-1"></i>
                                                            {{ $fancam->user->first_name ?? 'Unknown User' }} {{ $fancam->user->last_name ?? 'Unknown User' }}
                                                        </small><br>
                                                        <small class="text-muted">
                                                            <i class="fa fa-shield mr-1"></i>
                                                            {{ $fancam->team->name ?? 'Team' }}
                                                        </small><br>
                                                        <small class="text-muted">
                                                            <i class="fa fa-calendar mr-1"></i>
                                                            {{ $fancam->created_at->format('M d, Y') }}
                                                        </small>
                                                    </div>

                                                    <div class="btn-group w-100">
                                                        <a href="{{ route('admin.fancam.show', $fancam) }}"
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-secondary"
                                                                onclick="editPoints({{ $fancam->id }}, {{ $fancam->points }})">
                                                            <i class="fa fa-star"></i>
                                                        </button>
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="deleteFancam({{ $fancam->id }})">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $fancams->appends(request()->query())->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fa fa-camera text-muted" style="font-size: 4rem;"></i>
                                    <h5 class="mt-3 text-muted">No Photos Found</h5>
                                    <p class="text-muted">No fancam photos have been uploaded for this game yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Fancam Photo</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this fancam photo?</p>
                <p class="text-warning">
                    <i class="fa fa-exclamation-triangle mr-1"></i>
                    <strong>Warning:</strong> Points will be deducted from the user's account.
                </p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash mr-1"></i>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Points Modal -->
<div class="modal fade" id="editPointsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Points</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editPointsForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="points">Points (0-100)</label>
                        <input type="number" id="points" name="points" class="form-control"
                               min="0" max="100" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save mr-1"></i>
                        Update Points
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function deleteFancam(fancamId) {
    $('#deleteForm').attr('action', `/admin/fancam/${fancamId}`);
    $('#deleteModal').modal('show');
}

function editPoints(fancamId, currentPoints) {
    $('#points').val(currentPoints);
    $('#editPointsForm').attr('action', `/admin/fancam/${fancamId}/points`);
    $('#editPointsModal').modal('show');
}

function filterStatus(status) {
    const items = document.querySelectorAll('.fancam-item');

    items.forEach(item => {
        if (status === 'all' || item.dataset.status === status) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });

    // Update button states
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-outline-primary', 'btn-outline-success', 'btn-outline-warning', 'btn-outline-danger');
    });

    event.target.classList.remove('btn-outline-primary', 'btn-outline-success', 'btn-outline-warning', 'btn-outline-danger');
    event.target.classList.add('btn-primary');
}
</script>
@endsection
