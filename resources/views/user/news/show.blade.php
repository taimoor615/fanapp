@extends('layouts.user-dashboard')

@section('title', 'Games')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                @if($news->is_featured)
                    <div class="card-header bg-warning text-dark">
                        <i class="fa fa-star"></i> Featured News
                    </div>
                @endif

                @if($news->featured_image)
                    <img src="{{ asset('storage/news/'.$news->featured_image) }}" class="card-img-top" alt="News Image" style="height: 400px; object-fit: cover;">
                @endif

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-primary fs-6">{{ ucfirst(str_replace('_', ' ', $news->post_type)) }}</span>
                        <span class="text-muted">
                            <i class="fa fa-calendar"></i> {{ date('F d, Y \a\t g:i A', strtotime($news->created_at)) }}
                        </span>
                    </div>

                    <h1 class="card-title mb-4">{{ $news->title }}</h1>
                    <div class="card-text fs-5 lh-lg">
                        {!! nl2br(e($news->content)) !!}
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('user.news.index') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-arrow-left"></i> Back to News
                        </a>

                        {{-- <div class="btn-group">
                            <a href="{{ route('news.edit', $news->id) }}" class="btn btn-outline-warning">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('news.destroy', $news->id) }}" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this news?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
