@extends('layouts.user-dashboard')

@section('title', 'Daily Challenge')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark">
            <h4><i class="fa fa-calendar-day"></i> Daily Challenge</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('user.trivia.submit-daily-challenge') }}" method="POST">
                @csrf
                <input type="hidden" name="question_id" value="{{ $question->id }}">
                <h5>{{ $question->question }}</h5>
                @foreach ($question->options as $option)
                    <div class="form-check my-2">
                        <input class="form-check-input" type="radio" name="selected_answer" value="{{ $option }}" required>
                        <label class="form-check-label">{{ $option }}</label>
                    </div>
                @endforeach
                <button class="btn btn-primary mt-3"><i class="fa fa-check"></i> Submit Answer</button>
            </form>
        </div>
    </div>
</div>
@endsection
