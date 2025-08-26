@extends('layouts.user-dashboard')

@section('title', 'My Fancam Photos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-camera mr-2"></i>
                        My Fancam Photos
                    </h4>
                    <a href="{{ route('user.fancam.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus mr-1"></i>
                        Upload Photos
                    </a>
                </div>
                <div class="card-body">

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fa fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($fancams->count() > 0)
                        <div class="row">
                            @foreach($fancams as $fancam)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="card h-100">
                                        <div class="position-relative">
                                            <img src="{{ $fancam->image_url }}"
                                                 class="card-img-top"
                                                 alt="{{ $fancam->title }}"
                                                 style="height: 200px; object-fit: cover;">

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
                                            <span class="badge badge-primary position-absolute" style="top: 10px; left: 10px;">
                                                <i class="fa fa-star mr-1"></i>{{ $fancam->points }} pts
                                            </span>
                                        </div>

                                        <div class="card-body d-flex flex-column">
                                            <h6 class="card-title">
                                                {{ $fancam->title ?: 'Untitled' }}
                                            </h6>

                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="fa fa-gamepad mr-1"></i>
                                                    {{ $fancam->game->name ?? 'Game' }}
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

                                            @if($fancam->description)
                                                <p class="card-text text-muted small">
                                                    {{ Str::limit($fancam->description, 80) }}
                                                </p>
                                            @endif

                                            <div class="mt-auto">
                                                <div class="btn-group w-100">
                                                    <a href="{{ route('user.fancam.show', $fancam) }}"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('user.fancam.edit', $fancam) }}"
                                                       class="btn btn-sm btn-outline-secondary">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-danger"
                                                            onclick="deleteFancam({{ $fancam->id }})">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $fancams->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa fa-camera text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 text-muted">No Photos Yet</h5>
                            <p class="text-muted">Upload your first fancam photo to get started!</p>
                            <a href="{{ route('user.fancam.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus mr-1"></i>
                                Upload Photos
                            </a>
                        </div>
                    @endif
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
<script>
function deleteFancam(fancamId) {
    let url = "{{ route('user.fancam.destroy', ':id') }}";
    url = url.replace(':id', fancamId);
    $('#deleteForm').attr('action', url);
    $('#deleteModal').modal('show');
}
</script>
@endsection
