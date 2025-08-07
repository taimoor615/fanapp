@extends('layouts.app')

@section('title', 'Games')

@section('content')

@forelse($games as $game)

@if($game->ticket_url)
@endif

@empty
@endforelse
{{ $games->links() }}
@endsection
