@extends('layouts.user-dashboard')

@section('title', 'Challenge Result')

@section('content')
<div class="container mt-4">
    <div class="alert alert-{{ $isCorrect ? 'success' : 'danger' }}">
        <h4>
            <i class="fa {{ $isCorrect ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
            {{ $isCorrect ? 'Correct!' : 'Incorrect!' }}
        </h4>
        <p>{{ $isCorrect ? "You've earned $points points!" : 'Better luck next time!' }}</p>
    </div>

    <a href="{{ route('user.trivia.daily-challenge') }}" class="btn btn-secondary">
        <i class="fa fa-arrow-left"></i> Back to Trivia
    </a>
</div>
@endsection
