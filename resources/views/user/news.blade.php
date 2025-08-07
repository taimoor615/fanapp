@extends('layouts.user-dashboard')

@section('title', 'News')

@section('content')

@forelse($news as $article)

@if($article->featured_image)
@endif

@empty
@endforelse
{{ $news->links() }}
@endsection
