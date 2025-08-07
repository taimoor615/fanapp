@extends('layouts.app')

@section('title', 'Rewards')

@section('content')

@auth
    <div class="alert alert-info">
        <strong>Your Points:</strong> {{ auth()->user()->total_points }} points
    </div>
@endauth

@endsection
