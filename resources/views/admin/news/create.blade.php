@extends('layouts.admin')

@section('title', 'Create News')

@section('content')
<div class="container-fluid">
    <!-- Admin Header -->
    {{-- <div class="row">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                    <i class="fa fa-shield-alt"></i> Team Admin
                </a>
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="{{ route('admin.news.analytics') }}">
                        <i class="fa fa-chart-bar"></i> Analytics
                    </a>
                    <a class="nav-link" href="{{ route('admin.trivia.index') }}">
                        <i class="fa fa-brain"></i> Trivia
                    </a>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fa fa-user"></i> Admin
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.logout') }}">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div> --}}

    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fa fa-plus-circle text-primary"></i> Create News</h2>
                    <p class="text-muted">Add a new news article or announcement</p>
                </div>
                <div>
                    <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left"></i> Back to News
                    </a>
                </div>
            </div>

            <!-- Create Form -->
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">News Details</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Left Column -->
                            <input type="hidden" name="team_id" value="1">
                            <div class="col-md-8">
                                <!-- Title -->
                                <div class="mb-4">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Content -->
                                <div class="mb-4">
                                    <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('content') is-invalid @enderror"
                                              id="content" name="content" rows="12" required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Excerpt -->
                                <div class="mb-4">
                                    <label for="excerpt" class="form-label">Excerpt</label>
                                    <textarea class="form-control @error('excerpt') is-invalid @enderror"
                                              id="excerpt" name="excerpt" rows="3"
                                              placeholder="Brief summary (will be auto-generated from content if left empty)">{{ old('excerpt') }}</textarea>
                                    @error('excerpt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Maximum 500 characters</div>
                                </div>

                                <!-- Media URLs -->
                                <div class="mb-4">
                                    <label for="media_urls" class="form-label">Additional Media URLs</label>
                                    <textarea class="form-control @error('media_urls') is-invalid @enderror"
                                              id="media_urls" name="media_urls" rows="4"
                                              placeholder="Enter URLs one per line (videos, additional images, etc.)">{{ old('media_urls') }}</textarea>
                                    @error('media_urls')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">One URL per line</div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-4">
                                <!-- Publishing Options -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">Publishing Options</h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Post Type -->
                                        <div class="mb-3">
                                            <label for="post_type" class="form-label">Post Type <span class="text-danger">*</span></label>
                                            <select class="form-select @error('post_type') is-invalid @enderror"
                                                    id="post_type" name="post_type" required>
                                                <option value="">Choose type...</option>
                                                <option value="news" {{ old('post_type') == 'news' ? 'selected' : '' }}>News</option>
                                                <option value="highlight" {{ old('post_type') == 'highlight' ? 'selected' : '' }}>Highlight</option>
                                                <option value="press" {{ old('post_type') == 'press' ? 'selected' : '' }}>Press</option>
                                                <option value="announcement" {{ old('post_type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                            </select>
                                            @error('post_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Status Toggles -->
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                       id="is_published" name="is_published" value="1"
                                                       {{ old('is_published') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_published">
                                                    Publish immediately
                                                </label>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                       id="is_featured" name="is_featured" value="1"
                                                       {{ old('is_featured') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_featured">
                                                    Mark as Featured
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Publish Date -->
                                        <div class="mb-3" id="publishDateField" style="display: none;">
                                            <label for="published_at" class="form-label">Publish Date</label>
                                            <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror"
                                                   id="published_at" name="published_at" value="{{ old('published_at') }}">
                                            @error('published_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Featured Image -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="mb-0">Featured Image</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <input type="file" class="form-control @error('featured_image') is-invalid @enderror"
                                                   id="featured_image" name="featured_image" accept="image/*">
                                            @error('featured_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Max size: 2MB. Formats: JPG, PNG, GIF</div>
                                        </div>

                                        <div id="imagePreview" style="display: none;">
                                            <img id="previewImg" src="" class="img-fluid rounded" style="max-height: 200px;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="card">
                                    <div class="card-body">
                                        <button type="submit" class="btn btn-primary w-100 mb-2">
                                            <i class="fa fa-save"></i> Create News
                                        </button>
                                        {{-- <button type="submit" name="action" value="draft" class="btn btn-outline-secondary w-100">
                                            <i class="fa fa-file-alt"></i> Save as Draft
                                        </button> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview
document.getElementById('featured_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});

// Show/hide publish date field
document.getElementById('is_published').addEventListener('change', function() {
    const publishDateField = document.getElementById('publishDateField');
    if (this.checked) {
        publishDateField.style.display = 'block';
    } else {
        publishDateField.style.display = 'none';
    }
});

// Character count for excerpt
document.getElementById('excerpt').addEventListener('input', function() {
    const maxLength = 500;
    const currentLength = this.value.length;
    const remaining = maxLength - currentLength;

    // Add or update character counter
    let counter = document.getElementById('excerptCounter');
    if (!counter) {
        counter = document.createElement('div');
        counter.id = 'excerptCounter';
        counter.className = 'form-text';
        this.parentNode.appendChild(counter);
    }

    counter.textContent = `${remaining} characters remaining`;
    counter.className = remaining < 0 ? 'form-text text-danger' : 'form-text text-muted';
});
</script>
@endsection
