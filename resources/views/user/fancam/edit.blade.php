@extends('layouts.user-dashboard')

@section('title', 'Edit Fancam Photo')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-edit mr-2"></i>
                        Edit Fancam Photo
                    </h4>
                </div>
                <div class="card-body">

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-circle mr-2"></i>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('user.fancam.update', $fancam) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Current Image -->
                        <div class="form-group">
                            <label>Current Photo</label>
                            <div class="text-center mb-3">
                                <img src="{{ $fancam->image_url }}"
                                     class="img-thumbnail"
                                     alt="Current photo"
                                     style="max-height: 200px;">
                            </div>
                        </div>

                        <!-- Replace Image -->
                        <div class="form-group">
                            <label for="image">
                                <i class="fa fa-image mr-1"></i>
                                Replace Photo (Optional)
                            </label>
                            <input type="file" name="image" id="image" class="form-control-file"
                                   accept="image/*" onchange="previewImage(this)">
                            <small class="text-muted">
                                Leave empty to keep current photo. Max size: 2MB. Formats: JPEG, PNG, JPG, GIF
                            </small>

                            <!-- New Image Preview -->
                            <div id="newImagePreview" class="mt-3" style="display: none;">
                                <label>New Photo Preview:</label>
                                <div class="text-center">
                                    <img id="previewImg" class="img-thumbnail" style="max-height: 200px;">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="game_id">
                                        <i class="fa fa-gamepad mr-1"></i>
                                        Select Game <span class="text-danger">*</span>
                                    </label>
                                    <select name="game_id" id="game_id" class="form-control" required>
                                        <option value="">Choose a game...</option>
                                        @foreach($userGames as $game)
                                            <option value="{{ $game->id }}"
                                                {{ (old('game_id', $fancam->game_id) == $game->id) ? 'selected' : '' }}>
                                                {{ $game->opponent_team }} - {{ $game->game_date }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="team_id">
                                        <i class="fa fa-shield mr-1"></i>
                                        Select Team <span class="text-danger">*</span>
                                    </label>
                                    <select name="team_id" id="team_id" class="form-control" required>
                                        <option value="">Choose a team...</option>
                                        @foreach($teams as $team)
                                            <option value="{{ $team->id }}"
                                                {{ (old('team_id', $fancam->team_id) == $team->id) ? 'selected' : '' }}>
                                                {{ $team->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="title">
                                <i class="fa fa-tag mr-1"></i>
                                Photo Title
                            </label>
                            <input type="text" name="title" id="title" class="form-control"
                                   value="{{ old('title', $fancam->title) }}"
                                   placeholder="Enter photo title...">
                        </div>

                        <div class="form-group">
                            <label for="description">
                                <i class="fa fa-comment mr-1"></i>
                                Description
                            </label>
                            <textarea name="description" id="description" class="form-control" rows="4"
                                      placeholder="Describe your photo...">{{ old('description', $fancam->description) }}</textarea>
                        </div>

                        <!-- Photo Stats -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fa fa-star text-warning mb-2" style="font-size: 1.5rem;"></i>
                                        <h6 class="mb-0">Points Earned</h6>
                                        <span class="text-muted">{{ $fancam->points }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        @if($fancam->status == 'approved')
                                            <i class="fa fa-check-circle text-success mb-2" style="font-size: 1.5rem;"></i>
                                            <h6 class="mb-0">Status</h6>
                                            <span class="text-success">Approved</span>
                                        @elseif($fancam->status == 'pending')
                                            <i class="fa fa-clock text-warning mb-2" style="font-size: 1.5rem;"></i>
                                            <h6 class="mb-0">Status</h6>
                                            <span class="text-warning">Pending</span>
                                        @else
                                            <i class="fa fa-times-circle text-danger mb-2" style="font-size: 1.5rem;"></i>
                                            <h6 class="mb-0">Status</h6>
                                            <span class="text-danger">Rejected</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fa fa-calendar text-info mb-2" style="font-size: 1.5rem;"></i>
                                        <h6 class="mb-0">Uploaded</h6>
                                        <span class="text-muted">{{ $fancam->created_at->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-save mr-2"></i>
                                Update Photo
                            </button>
                            <a href="{{ route('user.fancam.show', $fancam) }}" class="btn btn-secondary btn-lg ml-2">
                                <i class="fa fa-times mr-2"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title text-info">
                        <i class="fa fa-lightbulb mr-1"></i>
                        Tips for Better Photos
                    </h6>
                    <ul class="mb-0">
                        <li>Use clear, high-quality images that showcase the game atmosphere.</li>
                        <li>Add descriptive titles and descriptions to make your photos more engaging.</li>
                        <li>Make sure your photos are appropriate and follow community guidelines.</li>
                        <li>Photos that violate guidelines may be rejected and points deducted.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('newImagePreview').style.display = 'block';
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        document.getElementById('newImagePreview').style.display = 'none';
    }
}
</script>
@endsection
