@extends('layouts.admin')

@section('title', 'View Fancam Photo')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-image mr-2"></i>
                        {{ $fancam->title ?: 'Fancam Photo Details' }}
                    </h4>
                    <div>
                        <a href="{{ route('admin.fancam.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa fa-arrow-left mr-1"></i>
                            Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-8">
                            <!-- Main Image -->
                            <div class="text-center mb-4">
                                <img src="{{ $fancam->image_url }}"
                                     class="img-fluid rounded shadow"
                                     alt="{{ $fancam->title }}"
                                     style="max-height: 500px;">
                            </div>

                            <!-- Description -->
                            @if($fancam->description)
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="fa fa-comment mr-1"></i>
                                            Description
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-0">{{ $fancam->description }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- User Info -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fa fa-user mr-1"></i>
                                        User Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Name:</strong> {{ $fancam->user->first_name ?? 'N/A' }} {{ $fancam->user->last_name ?? 'N/A' }}<br>
                                            <strong>Email:</strong> {{ $fancam->user->email ?? 'N/A' }}<br>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Total Points:</strong> {{ $fancam->user->points ?? 0 }}<br>
                                            <strong>Total Photos:</strong> {{ $fancam->user->fancams()->count() ?? 0 }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Photo Status -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fa fa-info-circle mr-1"></i>
                                        Photo Status
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="font-weight-bold">Current Status:</label><br>
                                        @if($fancam->status == 'approved')
                                            <span class="badge badge-success badge-lg">
                                                <i class="fa fa-check mr-1"></i>Approved
                                            </span>
                                        @elseif($fancam->status == 'pending')
                                            <span class="badge badge-warning badge-lg">
                                                <i class="fa fa-clock mr-1"></i>Pending Review
                                            </span>
                                        @else
                                            <span class="badge badge-danger badge-lg">
                                                <i class="fa fa-times mr-1"></i>Rejected
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Status Update Form -->
                                    <form action="{{ route('admin.fancam.status', $fancam) }}" method="POST" class="mb-3">
                                        @csrf
                                        <div class="form-group">
                                            <label>Update Status:</label>
                                            <select name="status" class="form-control" onchange="this.form.submit()">
                                                <option value="pending" {{ $fancam->status == 'pending' ? 'selected' : '' }}>
                                                    Pending Review
                                                </option>
                                                <option value="approved" {{ $fancam->status == 'approved' ? 'selected' : '' }}>
                                                    Approved
                                                </option>
                                                <option value="rejected" {{ $fancam->status == 'rejected' ? 'selected' : '' }}>
                                                    Rejected
                                                </option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Points Management -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fa fa-star mr-1"></i>
                                        Points Management
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="font-weight-bold">Current Points:</label>
                                        <span class="badge badge-primary ml-2">{{ $fancam->points }}</span>
                                    </div>

                                    <form action="{{ route('admin.fancam.points', $fancam) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label>Update Points:</label>
                                            <input type="number" name="points" class="form-control"
                                                   value="{{ $fancam->points }}" min="0" max="100" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-save mr-1"></i>
                                            Update Points
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Game & Team Info -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fa fa-gamepad mr-1"></i>
                                        Game Details
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <strong>Game:</strong><br>
                                        <span class="text-muted">{{ $fancam->game->opponent_team ?? 'N/A' }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Team:</strong><br>
                                        <span class="text-muted">{{ $fancam->team->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Game Date:</strong><br>
                                        <span class="text-muted">{{ $fancam->game->game_date ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Photo Details -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fa fa-calendar mr-1"></i>
                                        Upload Details
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <strong>Uploaded:</strong><br>
                                        <span class="text-muted">{{ $fancam->created_at->format('M d, Y \a\t H:i A') }}</span>
                                    </div>
                                    @if($fancam->updated_at != $fancam->created_at)
                                        <div class="mb-2">
                                            <strong>Last Updated:</strong><br>
                                            <span class="text-muted">{{ $fancam->updated_at->format('M d, Y \a\t H:i A') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fa fa-cogs mr-1"></i>
                                        Actions
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ $fancam->image_url }}"
                                           download
                                           class="btn btn-outline-primary btn-sm mb-2">
                                            <i class="fa fa-download mr-1"></i>
                                            Download Photo
                                        </a>

                                        <a href="{{ route('admin.fancam.game-stats', $fancam->game_id) }}"
                                           class="btn btn-outline-info btn-sm mb-2">
                                            <i class="fa fa-chart-bar mr-1"></i>
                                            Game Statistics
                                        </a>

                                        <button type="button"
                                                class="btn btn-outline-danger btn-sm"
                                                onclick="deleteFancam({{ $fancam->id }})">
                                            <i class="fa fa-trash mr-1"></i>
                                            Delete Photo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Photos -->
            @if($fancam->user->fancams()->where('id', '!=', $fancam->id)->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fa fa-images mr-1"></i>
                            Other Photos by {{ $fancam->user->name }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($fancam->user->fancams()->where('id', '!=', $fancam->id)->limit(6)->get() as $relatedFancam)
                                <div class="col-md-2 col-sm-4 mb-3">
                                    <div class="card">
                                        <img src="{{ $relatedFancam->image_url }}"
                                             class="card-img-top"
                                             style="height: 100px; object-fit: cover;">
                                        <div class="card-body p-2">
                                            <small class="text-muted">
                                                {{ $relatedFancam->game->name ?? 'Game' }}
                                            </small>
                                            <div class="mt-1">
                                                <a href="{{ route('admin.fancam.show', $relatedFancam) }}"
                                                   class="btn btn-sm btn-outline-primary btn-block">
                                                    View
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
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
<script>
function deleteFancam(fancamId) {
    let url = "{{ route('user.fancam.destroy', ':id') }}";
    url = url.replace(':id', fancamId);
    $('#deleteForm').attr('action', url);
    $('#deleteModal').modal('show');
}
</script>
@endsection
