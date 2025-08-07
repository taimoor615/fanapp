@extends('layouts.user-dashboard')

@section('title', 'User Profile')

@section('content')
<div class="page-header">
    <h3 class="page-title">Edit User</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item">Profile</li>
            {{-- <li class="breadcrumb-item active" aria-current="page">Edit {{ $user->full_name }}</li> --}}
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">User Information</h4>

                <form method="POST" action="{{ route('user.profile.update', $user) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Avatar Section -->
                    <div class="form-group text-center">
                        <label class="form-label">Avatar</label>
                        <div class="avatar-upload mb-3">
                            <div class="avatar-preview">
                                {{-- <img src="{{ asset('storage/avatars/') . $user->avatar }}" alt="Avatar" class="rounded-circle" width="120" height="120" id="avatar-preview"> --}}
                                <img src="{{ $user->avatar ? asset("storage/avatars/" . $user->avatar) : asset("assets/images/avatars/default-user.png") }}" alt="Avatar" class="rounded-circle" width="120" height="120" id="avatar-preview">
                            </div>
                            <div class="avatar-edit mt-3">
                                <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;">
                                <label for="avatar" class="btn btn-outline-primary btn-sm">Change Avatar</label>
                                @if($user->avatar)
                                    <button type="button" class="btn btn-outline-danger btn-sm ml-2" onclick="removeCurrentAvatar()">Remove Current</button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Basic Information</h5>

                            <div class="form-group">
                                <label for="first_name">First Name *</label>
                                <input type="text"
                                        class="form-control"
                                        id="first_name"
                                        name="first_name"
                                        value="{{ old('first_name', $user->first_name) }}"
                                        required>
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name *</label>
                                <input type="text"
                                        class="form-control"
                                        id="last_name"
                                        name="last_name"
                                        value="{{ old('last_name', $user->last_name) }}"
                                        required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email *</label>
                                <input type="email"
                                        class="form-control"
                                        id="email"
                                        name="email"
                                        value="{{ old('email', $user->email) }}"
                                        required readonly>
                            </div>

                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel"
                                        class="form-control"
                                        id="phone"
                                        name="phone"
                                        value="{{ old('phone', $user->phone) }}">
                            </div>

                            <div class="form-group">
                                <label for="team_id">Team *</label>
                                <select class="form-select form-select-lg" id="team_id" name="team_id" required>
                                    <option value="">Select Team</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" {{ old('team_id', $user->team_id) == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="mb-3">Additional Information</h5>

                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select class="form-select form-select-lg" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="date_of_birth">Date of Birth</label>
                                <input type="date"
                                        class="form-control"
                                        id="date_of_birth"
                                        name="date_of_birth"
                                        value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}">
                            </div>

                            <div class="form-group">
                                <label for="current_level">Current Level</label>
                                <input type="text"
                                        class="form-control"
                                        id="current_level"
                                        name="current_level"
                                        placeholder="e.g., Beginner, Intermediate, Advanced"
                                        value="{{ old('current_level', $user->current_level) }}" readonly>
                            </div>

                            <div class="form-group">
                                <label for="total_points">Total Points</label>
                                <input type="number"
                                        class="form-control"
                                        id="total_points"
                                        name="total_points"
                                        min="0"
                                        value="{{ old('total_points', $user->total_points) }}" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="mb-3">Change Password (Optional)</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">New Password</label>
                                        <input type="password"
                                                class="form-control"
                                                id="password"
                                                name="password"
                                                placeholder="Leave blank to keep current password">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm New Password</label>
                                        <input type="password"
                                                class="form-control"
                                                id="password_confirmation"
                                                name="password_confirmation"
                                                placeholder="Confirm new password">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4">

                        <button type="submit" class="btn btn-gradient-primary mr-2">
                            <i class="mdi mdi-content-save"></i> Update User
                        </button>
                        <a href="{{ route('user.dashboard') }}" class="btn btn-light mr-2">
                            <i class="mdi mdi-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Avatar preview
document.getElementById('avatar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Remove current avatar (visual only - server handles actual removal on form submit)
function removeCurrentAvatar() {
    if (confirm('This will remove the current avatar when you save. Continue?')) {
        // You might want to add a hidden input to indicate avatar removal
        const form = document.querySelector('form');
        let removeInput = document.querySelector('input[name="remove_avatar"]');
        if (!removeInput) {
            removeInput = document.createElement('input');
            removeInput.type = 'hidden';
            removeInput.name = 'remove_avatar';
            removeInput.value = '1';
            form.appendChild(removeInput);
        }

        // Show default avatar preview
        const defaultAvatar = '{{ asset("assets/images/avatars/default-user.png") }}';
        document.getElementById('avatar-preview').src = defaultAvatar;
    }
}
</script>
@endsection
