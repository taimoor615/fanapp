@extends('layouts.admin')

@section('title', 'Edit News')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2><i class="fa fa-edit text-primary"></i> Edit News</h2>
                    <p class="text-muted">Update news post details</p>
                </div>
                <div>
                    <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-arrow-left"></i> Back to News
                    </a>
                </div>
            </div>

            <!-- Form -->
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">News Details</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.news.update', $news->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <input type="hidden" name="team_id" value="{{ $news->team_id }}">

                            <!-- Left Column -->
                            <div class="col-md-8">
                                <!-- Title -->
                                <div class="mb-4">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title', $news->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Content -->
                                <div class="mb-4">
                                    <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('content') is-invalid @enderror"
                                              id="content" name="content" rows="12" required>{{ old('content', $news->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Excerpt -->
                                <div class="mb-4">
                                    <label for="excerpt" class="form-label">Excerpt</label>
                                    <textarea class="form-control @error('excerpt') is-invalid @enderror"
                                              id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $news->excerpt) }}</textarea>
                                    @error('excerpt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Maximum 500 characters</div>
                                </div>

                                <!-- Media URLs -->
                                <div class="mb-4">
                                    <label for="media_urls" class="form-label">Additional Media URLs</label>
                                    <textarea class="form-control @error('media_urls') is-invalid @enderror"
                                              id="media_urls" name="media_urls" rows="4">{{ old('media_urls', $news->media_urls) }}</textarea>
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
                                                <option value="news" {{ old('post_type', $news->post_type) == 'news' ? 'selected' : '' }}>News</option>
                                                <option value="highlight" {{ old('post_type', $news->post_type) == 'highlight' ? 'selected' : '' }}>Highlight</option>
                                                <option value="press" {{ old('post_type', $news->post_type) == 'press' ? 'selected' : '' }}>Press</option>
                                                <option value="announcement" {{ old('post_type', $news->post_type) == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                            </select>
                                            @error('post_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Publish Now -->
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                       id="is_published" name="is_published" value="1"
                                                       {{ old('is_published', $news->is_published) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_published">
                                                    Publish immediately
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Featured -->
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                       id="is_featured" name="is_featured" value="1"
                                                       {{ old('is_featured', $news->is_featured) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_featured">
                                                    Mark as Featured
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Published At -->
                                        <div class="mb-3" id="publishDateField" style="{{ old('is_published', $news->is_published) ? '' : 'display: none;' }}">
                                            <label for="published_at" class="form-label">Publish Date</label>
                                            <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror"
                                                   id="published_at" name="published_at"
                                                   value="{{ old('published_at', \Carbon\Carbon::parse($news->published_at)->format('Y-m-d\TH:i')) }}">
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
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Max size: 2MB. Formats: JPG, PNG, GIF</div>
                                        </div>

                                        <!-- Preview existing image -->
                                        @if($news->featured_image)
                                            <div id="imagePreview">
                                                <img id="previewImg" src="{{ asset('storage/news/' . $news->featured_image) }}"
                                                     class="img-fluid rounded" style="max-height: 200px;">
                                            </div>
                                        @else
                                            <div id="imagePreview" style="display: none;">
                                                <img id="previewImg" class="img-fluid rounded" style="max-height: 200px;">
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="card">
                                    <div class="card-body">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fa fa-save"></i> Update News
                                        </button>
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

document.getElementById('is_published').addEventListener('change', function() {
    const publishDateField = document.getElementById('publishDateField');
    publishDateField.style.display = this.checked ? 'block' : 'none';
});

document.getElementById('excerpt').addEventListener('input', function() {
    const maxLength = 500;
    const currentLength = this.value.length;
    const remaining = maxLength - currentLength;
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
