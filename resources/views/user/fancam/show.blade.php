@extends('layouts.user-dashboard')

@section('title', 'View Fancam Photo')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-image mr-2"></i>
                        {{ $fancam->title ?: 'Fancam Photo' }}
                    </h4>
                    <div>
                        <a href="{{ route('user.fancam.edit', $fancam) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fa fa-edit mr-1"></i>
                            Edit
                        </a>
                        <a href="{{ route('user.fancam.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa fa-arrow-left mr-1"></i>
                            Back
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
                        </div>

                        <div class="col-md-4">
                            <!-- Photo Info -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fa fa-info-circle mr-1"></i>
                                        Photo Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="font-weight-bold">Status:</label>
                                        @if($fancam->status == 'approved')
                                            <span class="badge badge-success ml-2">
                                                <i class="fa fa-check mr-1"></i>Approved
                                            </span>
                                        @elseif($fancam->status == 'pending')
                                            <span class="badge badge-warning ml-2">
                                                <i class="fa fa-clock mr-1"></i>Pending Review
                                            </span>
                                        @else
                                            <span class="badge badge-danger ml-2">
                                                <i class="fa fa-times mr-1"></i>Rejected
                                            </span>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <label class="font-weight-bold">Points Earned:</label>
                                        <span class="badge badge-primary ml-2">
                                            <i class="fa fa-star mr-1"></i>{{ $fancam->points }} points
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <label class="font-weight-bold">Game:</label><br>
                                        <span class="text-muted">
                                            <i class="fa fa-gamepad mr-1"></i>
                                            {{ $fancam->game->opponent_team ?? 'N/A' }}
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <label class="font-weight-bold">Team:</label><br>
                                        <span class="text-muted">
                                            <i class="fa fa-shield mr-1"></i>
                                            {{ $fancam->team->name ?? 'N/A' }}
                                        </span>
                                    </div>

                                    <div class="mb-3">
                                        <label class="font-weight-bold">Uploaded On:</label><br>
                                        <span class="text-muted">
                                            <i class="fa fa-calendar mr-1"></i>
                                            {{ $fancam->created_at->format('M d, Y \a\t H:i A') }}
                                        </span>
                                    </div>

                                    @if($fancam->updated_at != $fancam->created_at)
                                        <div class="mb-3">
                                            <label class="font-weight-bold">Last Updated:</label><br>
                                            <span class="text-muted">
                                                <i class="fa fa-edit mr-1"></i>
                                                {{ $fancam->updated_at->format('M d, Y \a\t H:i A') }}
                                            </span>
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

                                        <a href="{{ route('user.fancam.edit', $fancam) }}"
                                           class="btn btn-outline-secondary btn-sm mb-2">
                                            <i class="fa fa-edit mr-1"></i>
                                            Edit Details
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

                            <!-- Status Info -->
                            @if($fancam->status == 'pending')
                                <div class="alert alert-info mt-3">
                                    <i class="fa fa-info-circle mr-1"></i>
                                    <strong>Under Review</strong><br>
                                    Your photo is currently being reviewed by our admin team.
                                </div>
                            @elseif($fancam->status == 'rejected')
                                <div class="alert alert-warning mt-3">
                                    <i class="fa fa-exclamation-triangle mr-1"></i>
                                    <strong>Photo Rejected</strong><br>
                                    This photo didn't meet our guidelines. You can edit and resubmit.
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
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this fancam photo?</p>
                <p class="text-warning">
                    <i class="fa fa-exclamation-triangle mr-1"></i>
                    <strong>Warning:</strong> The points earned from this photo will be deducted from your account.
                </p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash mr-1"></i>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function deleteFancam(fancamId) {
    let url = "{{ route('user.fancam.destroy', ':id') }}";
    url = url.replace(':id', fancamId);
    $('#deleteForm').attr('action', url);
    $('#deleteModal').modal('show');
}
</script>
@endsection
