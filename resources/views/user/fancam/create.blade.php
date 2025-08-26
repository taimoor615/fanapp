@extends('layouts.user-dashboard')

@section('title', 'Upload Fancam Photos')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fa fa-cloud-upload mr-2"></i>
                        Upload Fancam Photos
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

                    <form action="{{ route('user.fancam.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

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
                                            <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
                                                {{ $game->opponent_team }} - {{ $game->game_date }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">
                                        Only games you've paid for are shown here.
                                    </small>
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
                                            <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                                                {{ $team->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="images">
                                <i class="fa fa-images mr-1"></i>
                                Upload Photos <span class="text-danger">*</span>
                            </label>
                            <input type="file" name="images[]" id="images" class="form-control-file"
                                   multiple accept="image/*" required onchange="previewImages(this)">
                            <small class="text-muted">
                                You can upload up to 5 photos per game. Each photo must be less than 2MB.
                                Supported formats: JPEG, PNG, JPG, GIF
                            </small>

                            <!-- Image Preview -->
                            <div id="imagePreview" class="row mt-3"></div>
                        </div>

                        <div id="photoDetails"></div>

                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fa fa-upload mr-2"></i>
                                Upload Photos
                            </button>
                            <a href="{{ route('user.fancam.index') }}" class="btn btn-secondary btn-lg ml-2">
                                <i class="fa fa-times mr-2"></i>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Upload Info Card -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title text-primary">
                        <i class="fa fa-info-circle mr-1"></i>
                        Important Information
                    </h6>
                    <ul class="mb-0">
                        <li>You can only upload photos for games you've participated in (paid for).</li>
                        <li>Maximum 5 photos per game allowed.</li>
                        <li>Each uploaded photo earns you 10 points.</li>
                        <li>Photos will be reviewed by admin before approval.</li>
                        <li>Make sure your photos are clear and related to the game.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function previewImages(input) {
    const previewContainer = document.getElementById('imagePreview');
    const photoDetailsContainer = document.getElementById('photoDetails');

    previewContainer.innerHTML = '';
    photoDetailsContainer.innerHTML = '';

    if (input.files && input.files.length > 0) {
        if (input.files.length > 5) {
            alert('You can only upload maximum 5 photos at once.');
            input.value = '';
            return;
        }

        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();

            reader.onload = function(e) {
                // Create preview image
                const previewDiv = document.createElement('div');
                previewDiv.className = 'col-md-4 col-sm-6 mb-3';
                previewDiv.innerHTML = `
                    <div class="card">
                        <img src="${e.target.result}" class="card-img-top" style="height: 150px; object-fit: cover;">
                        <div class="card-body p-2">
                            <small class="text-muted">Photo ${index + 1}</small>
                        </div>
                    </div>
                `;
                previewContainer.appendChild(previewDiv);

                // Create detail inputs
                const detailDiv = document.createElement('div');
                detailDiv.className = 'card mb-3';
                detailDiv.innerHTML = `
                    <div class="card-header">
                        <h6 class="mb-0">Photo ${index + 1} Details (Optional)</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" name="titles[${index}]" class="form-control"
                                           placeholder="Enter photo title...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="descriptions[${index}]" class="form-control" rows="3"
                                              placeholder="Describe this photo..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                photoDetailsContainer.appendChild(detailDiv);
            };

            reader.readAsDataURL(file);
        });
    }
}
</script>
@endsection

