@extends('layouts.admin')

@section('title', 'Update Game')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card bg-transparent">
                <div class="card-header bg-transparent border-0 p-0">
                    <div class="card-tools">
                        <a href="{{ route('admin.games.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back to Games
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <!-- Statistics Cards -->
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="card-title text-md text-muted mb-0">Total Atendances</p>
                            <h3 class="font-weight-bold mb-0">{{ $attendanceStats->total_attendees ?? 0 }}</h3>
                        </div>
                        <div class="icon-circle bg-gradient-success">
                            <i class="mdi mdi-account-group text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="card-title text-md text-muted mb-0">News Posts</p>
                            <h3 class="font-weight-bold mb-0">{{ $totalNews ?? 0 }}</h3>
                        </div>
                        <div class="icon-circle bg-gradient-warning">
                            <i class="mdi mdi-newspaper text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="card-title text-md text-muted mb-0">Upcoming Games</p>
                            <h3 class="font-weight-bold mb-0">{{ $totalGames ?? 0 }}</h3>
                        </div>
                        <div class="icon-circle bg-gradient-info">
                            <i class="mdi mdi-soccer text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="card-title text-md text-muted mb-0">Total Users</p>
                            <h3 class="font-weight-bold mb-0">{{ $totalUsers ?? 0 }}</h3>
                        </div>
                        <div class="icon-circle bg-gradient-primary">
                            <i class="mdi mdi-account-multiple text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Receipt</th>
                        <th>Status</th>
                        <th>Update</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->user->first_name }}</td>
                            <td>{{ $payment->user->email }}</td>
                            <td>
                                @if($payment->receipt_path)
                                    <a href="{{ asset('storage/receipt/' . $payment->receipt_path) }}" target="_blank">
                                        View Receipt
                                    </a>
                                @else
                                    <span class="text-muted">No Receipt</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge
                                    @if($payment->status == 'approved') bg-success
                                    @elseif($payment->status == 'pending') bg-warning
                                    @else bg-danger @endif">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.game.payments.update', $payment->id) }}" method="POST">
                                    @csrf
                                    <select name="status" class="form-select form-select-sm d-inline w-50">
                                        <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ $payment->status == 'approved' ? 'selected' : '' }}>Approve</option>
                                        <option value="rejected" {{ $payment->status == 'rejected' ? 'selected' : '' }}>Reject</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No payment records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


@push('scripts')
<script>
$(document).ready(function() {
    // Set minimum date to today
    const now = new Date();
    const today = now.toISOString().slice(0, 16); // Format: YYYY-MM-DDTHH:mm
    $('#game_date').attr('min', today);
});
</script>
@endpush
@endsection
