@extends('layouts.admin')

@section('title', 'Fancam Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-dashboard mr-2"></i>
                        Fancam Dashboard
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                                Total Photos
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold">
                                                {{ number_format($stats['total_fancams']) }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-camera fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                                Approved Photos
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold">
                                                {{ number_format($stats['approved_fancams']) }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card bg-warning text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                                Pending Review
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold">
                                                {{ number_format($stats['pending_fancams']) }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-clock fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card bg-info text-white h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="text-xs font-weight-bold text-uppercase mb-1">
                                                Total Points Awarded
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold">
                                                {{ number_format($stats['total_points_awarded']) }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-star fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Quick Actions -->
                        <div class="col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fa fa-bolt mr-1"></i>
                                        Quick Actions
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.fancam.index', ['status' => 'pending']) }}"
                                           class="btn btn-warning">
                                            <i class="fa fa-clock mr-1"></i>
                                            Review Pending Photos ({{ $stats['pending_fancams'] }})
                                        </a>

                                        <a href="{{ route('admin.fancam.manage') }}"
                                           class="btn btn-primary">
                                            <i class="fa fa-list mr-1"></i>
                                            Manage All Photos
                                        </a>

                                        <a href="{{ route('admin.fancam.index', ['status' => 'approved']) }}"
                                           class="btn btn-success">
                                            <i class="fa fa-check mr-1"></i>
                                            View Approved Photos
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Photos -->
                        <div class="col-lg-8 mb-4">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fa fa-images mr-1"></i>
                                        Recent Uploads
                                    </h6>
                                    <a href="{{ route('admin.fancam.manage') }}" class="btn btn-sm btn-outline-primary">
                                        View All
                                    </a>
                                </div>
                                <div class="card-body">
                                    @if($stats['recent_fancams']->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Photo</th>
                                                        <th>User</th>
                                                        <th>Game</th>
                                                        <th>Status</th>
                                                        <th>Points</th>
                                                        <th>Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($stats['recent_fancams'] as $fancam)
                                                        <tr>
                                                            <td>
                                                                <img src="{{ $fancam->image_url }}"
                                                                     class="img-thumbnail"
                                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                            </td>
                                                            <td>
                                                                <small>{{ $fancam->user->first_name ?? 'N/A' }} {{ $fancam->user->last_name ?? 'N/A' }}</small>
                                                            </td>
                                                            <td>
                                                                <small>{{ Str::limit($fancam->game->opponent_team ?? 'N/A', 20) }}</small>
                                                            </td>
                                                            <td>
                                                                @if($fancam->status == 'approved')
                                                                    <span class="badge badge-success badge-sm">Approved</span>
                                                                @elseif($fancam->status == 'pending')
                                                                    <span class="badge badge-warning badge-sm">Pending</span>
                                                                @else
                                                                    <span class="badge badge-danger badge-sm">Rejected</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-primary">{{ $fancam->points }}</span>
                                                            </td>
                                                            <td>
                                                                <small>{{ $fancam->created_at->format('M d, Y') }}</small>
                                                            </td>
                                                            <td>
                                                                <a href="{{ route('admin.fancam.show', $fancam) }}"
                                                                   class="btn btn-sm btn-outline-primary">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fa fa-camera text-muted" style="font-size: 3rem;"></i>
                                            <p class="text-muted mt-2">No recent uploads found</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Statistics -->
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fa fa-chart-pie mr-1"></i>
                                        Status Distribution
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="border-right">
                                                <div class="h4 text-success mb-0">
                                                    {{ $stats['total_fancams'] > 0 ? round(($stats['approved_fancams'] / $stats['total_fancams']) * 100, 1) : 0 }}%
                                                </div>
                                                <small class="text-muted">Approved</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="border-right">
                                                <div class="h4 text-warning mb-0">
                                                    {{ $stats['total_fancams'] > 0 ? round(($stats['pending_fancams'] / $stats['total_fancams']) * 100, 1) : 0 }}%
                                                </div>
                                                <small class="text-muted">Pending</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="h4 text-danger mb-0">
                                                {{ $stats['total_fancams'] > 0 ? round((($stats['total_fancams'] - $stats['approved_fancams'] - $stats['pending_fancams']) / $stats['total_fancams']) * 100, 1) : 0 }}%
                                            </div>
                                            <small class="text-muted">Rejected</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">
                                        <i class="fa fa-info-circle mr-1"></i>
                                        System Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>Average Points per Photo:</strong><br>
                                            <span class="text-muted">
                                                {{ $stats['total_fancams'] > 0 ? round($stats['total_points_awarded'] / $stats['total_fancams'], 1) : 0 }}
                                            </span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Photos Needing Review:</strong><br>
                                            <span class="text-muted">{{ $stats['pending_fancams'] }}</span>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <strong>Last Updated:</strong><br>
                                            <span class="text-muted">{{ now()->format('M d, Y H:i A') }}</span>
                                        </div>
                                        <div class="col-6">
                                            <strong>Auto Refresh:</strong><br>
                                            <span class="text-muted">Every 5 minutes</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// Auto refresh every 5 minutes
setTimeout(function() {
    location.reload();
}, 300000); // 5 minutes
</script>
@endsection
