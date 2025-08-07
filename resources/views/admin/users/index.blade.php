@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-account-multiple text-white"></i>
        </span> users
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>
<div class="row">
    <!-- Recent Users -->
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <h4 class="card-title">All Users</h4>
                    {{-- <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">View All</a> --}}
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Team</th>
                                <th>Points</th>
                                <th>Joined</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users ?? [] as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $user->avatar ? asset("storage/avatars/" . $user->avatar) : asset("assets/images/avatars/default-user.png") }}"
                                             alt="profile" class="rounded-circle me-2" width="30">
                                        <div>
                                            <h6 class="mb-0">{{ $user->full_name }}</h6>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->team->name ?? 'No Team' }}</td>
                                <td><span class="badge bg-success">{{ $user->total_points }}</span></td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="page-title">
                                    <a href="{{ route('admin.users.edit',$user) }}" class="page-title-icon bg-gradient-primary text-white me-2"> <i class="fa fa-pencil"></i> </a>
                                    <a href="#" onclick="deleteUser({{ $user->id }})" class="page-title-icon bg-gradient-danger text-white me-2"><i class="fa fa-trash"></i></a></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No users found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- User Delete Request --}}
<script>
function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        fetch(`/admin/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // or remove the row dynamically
            } else {
                alert('Error deleting user');
            }
        });
    }
}
</script>
@endsection
