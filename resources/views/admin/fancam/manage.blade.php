@extends('layouts.admin')

@section('title', 'Manage Fancam Photos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-camera mr-2"></i>
                        Manage Fancam Photos
                    </h4>
                </div>
                <div class="card-body">

                    <!-- Filters -->
                    {{-- <div class="card mb-4">
                        <div class="card-body">
                            <h6>Bulk Actions</h6>
                            <form id="bulkActionForm" method="POST">
                                @csrf
                                <div class="row align-items-end">
                                    <div class="col-md-3">
                                        <select id="bulkAction" class="form-control">
                                            <option value="">Select Action</option>
                                            <option value="approve">Approve Selected</option>
                                            <option value="reject">Reject Selected</option>
                                            <option value="pending">Mark as Pending</option>
                                            <option value="delete">Delete Selected</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" id="executeBulkAction" class="btn btn-warning">
                                            <i class="fa fa-play mr-1"></i>
                                            Execute
                                        </button>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" id="selectAll" class="btn btn-outline-primary">
                                            <i class="fa fa-check-square mr-1"></i>
                                            Select All
                                        </button>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" id="deselectAll" class="btn btn-outline-secondary">
                                            <i class="fa fa-square mr-1"></i>
                                            Deselect All
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> --}}

                    @if($fancams->count() > 0)
                        <!-- Stats Row -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h4 class="mb-0">{{ $fancams->total() }}</h4>
                                        <small>Total Photos</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h4 class="mb-0">{{ $fancams->where('status', 'approved')->count() }}</h4>
                                        <small>Approved</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h4 class="mb-0">{{ $fancams->where('status', 'pending')->count() }}</h4>
                                        <small>Pending</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h4 class="mb-0">{{ $fancams->where('status', 'rejected')->count() }}</h4>
                                        <small>Rejected</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Photos Grid -->
                        <div class="row">
                            @foreach($fancams as $fancam)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="card h-100">
                                        <div class="position-relative">
                                            <!-- Selection Checkbox -->
                                            <div class="position-absolute" style="top: 5px; left: 5px; z-index: 10;">
                                                <input type="checkbox" class="fancam-checkbox"
                                                       value="{{ $fancam->id }}"
                                                       style="transform: scale(1.2);">
                                            </div>

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
                                            <span class="badge badge-primary position-absolute" style="bottom: 10px; left: 10px;">
                                                <i class="fa fa-star mr-1"></i>{{ $fancam->points }} pts
                                            </span>
                                        </div>

                                        <div class="card-body d-flex flex-column">
                                            <h6 class="card-title">
                                                {{ $fancam->title ?: 'Untitled' }}
                                            </h6>

                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="fa fa-user mr-1"></i>
                                                    {{ $fancam->user->first_name ?? 'Unknown User' }} {{ $fancam->user->last_name ?? 'Unknown User' }}
                                                </small><br>
                                                <small class="text-muted">
                                                    <i class="fa fa-gamepad mr-1"></i>
                                                    {{ $fancam->game->opponent_team ?? 'Game' }}
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
                                                    {{ Str::limit($fancam->description, 60) }}
                                                </p>
                                            @endif

                                            <div class="mt-auto">
                                                <div class="btn-group w-100 mb-2">
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

                                                <!-- Status Buttons -->
                                                <div class="btn-group w-100">
                                                    <button type="button"
                                                            class="btn btn-sm {{ $fancam->status == 'approved' ? 'btn-success' : 'btn-outline-success' }}"
                                                            onclick="updateStatus({{ $fancam->id }}, 'approved')">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    <button type="button"
                                                            class="btn btn-sm {{ $fancam->status == 'pending' ? 'btn-warning' : 'btn-outline-warning' }}"
                                                            onclick="updateStatus({{ $fancam->id }}, 'pending')">
                                                        <i class="fa fa-clock-o"></i>
                                                    </button>
                                                    <button type="button"
                                                            class="btn btn-sm {{ $fancam->status == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}"
                                                            onclick="updateStatus({{ $fancam->id }}, 'rejected')">
                                                        <i class="fa fa-times"></i>
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
                            {{ $fancams->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa fa-camera text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 text-muted">No Fancam Photos Found</h5>
                            <p class="text-muted">No fancam photos match your current filters.</p>
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

<!-- Bulk Action Confirmation Modal -->
<div class="modal fade" id="bulkConfirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Bulk Action</h5>
                <button type="button" class="close" data-bs-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="bulkConfirmText"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmBulkAction" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>
<script>
function deleteFancam(fancamId) {
    $('#deleteForm').attr('action', `/admin/fancam/${fancamId}`);
    $('#deleteModal').modal('show');
}

function updateStatus(fancamId, status) {
    const form = $('<form>', {
        'method': 'POST',
        'action': `/admin/fancam/${fancamId}/status`
    });

    form.append($('<input>', {
        'type': 'hidden',
        'name': '_token',
        'value': $('meta[name="csrf-token"]').attr('content')
    }));

    form.append($('<input>', {
        'type': 'hidden',
        'name': 'status',
        'value': status
    }));

    $('body').append(form);
    form.submit();
}

function editPoints(fancamId, currentPoints) {
    $('#points').val(currentPoints);
    $('#editPointsForm').attr('action', `/admin/fancam/${fancamId}/points`);
    $('#editPointsModal').modal('show');
}

// // Bulk actions
// $('#selectAll').click(function() {
//     $('.fancam-checkbox').prop('checked', true);
// });

// $('#deselectAll').click(function() {
//     $('.fancam-checkbox').prop('checked', false);
// });

// $('#executeBulkAction').click(function() {
//     const action = $('#bulkAction').val();
//     const selectedIds = $('.fancam-checkbox:checked').map(function() {
//         return this.value;
//     }).get();

//     if (!action) {
//         alert('Please select an action.');
//         return;
//     }

//     if (selectedIds.length === 0) {
//         alert('Please select at least one photo.');
//         return;
//     }

//     let confirmText = `Are you sure you want to ${action} ${selectedIds.length} photo(s)?`;
//     if (action === 'delete') {
//         confirmText += ' This action will deduct points from users.';
//     }

//     $('#bulkConfirmText').text(confirmText);
//     $('#bulkConfirmModal').modal('show');

//     $('#confirmBulkAction').off('click').on('click', function() {
//         let url, data;

//         if (action === 'delete') {
//             url = "{{ route('admin.fancam.bulk-delete') }}";
//             data = { fancam_ids: selectedIds };
//         } else {
//             url = "{{ route('admin.fancam.bulk-status') }}";
//             data = { fancam_ids: selectedIds, status: action };
//         }

//         const form = $('<form>', {
//             'method': 'POST',
//             'action': url
//         });

//         form.append($('<input>', {
//             'type': 'hidden',
//             'name': '_token',
//             'value': $('meta[name="csrf-token"]').attr('content')
//         }));

//         $.each(data, function(key, value) {
//             if (Array.isArray(value)) {
//                 $.each(value, function(i, val) {
//                     form.append($('<input>', {
//                         'type': 'hidden',
//                         'name': key + '[]',
//                         'value': val
//                     }));
//                 });
//             } else {
//                 form.append($('<input>', {
//                     'type': 'hidden',
//                     'name': key,
//                     'value': value
//                 }));
//             }
//         });

//         $('body').append(form);
//         form.submit();
//     });
// });
</script>
@endsection
