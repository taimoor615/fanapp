@extends('layouts.admin')

@section('title', 'View Trivia Question')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-{{ $question->difficulty == 'easy' ? 'success' : ($question->difficulty == 'medium' ? 'warning' : 'danger') }} me-2">
                            {{ ucfirst($question->difficulty) }}
                        </span>
                        <span class="badge bg-primary me-2">{{ $question->points }} pts</span>
                        <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $question->type)) }}</span>
                    </div>
                    <div>
                        @if($question->is_active)
                            <span class="badge bg-success"><i class="fa fa-check"></i> Active</span>
                        @else
                            <span class="badge bg-secondary"><i class="fa fa-pause"></i> Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <h4 class="card-title mb-4">{{ $question->question }}</h4>

                    <h6 class="mb-3">Answer Options:</h6>
                    <div class="list-group mb-4">
                        @foreach($question->options as $index => $option)
                            @php
                                $isCorrect = $option === $question->correct_answer;
                            @endphp
                            <div class="list-group-item d-flex justify-content-between align-items-center {{ $isCorrect ? 'list-group-item-success' : '' }}">
                                <div>
                                    <strong>{{ chr(65 + $index) }}.</strong> {{ $option }}
                                </div>
                                @if($isCorrect)
                                    <span class="badge bg-success rounded-pill">
                                        <i class="fa fa-check"></i> Correct
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted">
                            <small><i class="fa fa-calendar"></i> Created: {{ date('F d, Y \a\t g:i A', strtotime($question->created_at)) }}</small>
                        </div>

                        <div class="btn-group">
                            <a href="{{ route('admin.trivia.index') }}" class="btn btn-outline-secondary">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                            <a href="{{ route('admin.trivia.edit', $question->id) }}" class="btn btn-outline-warning">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('admin.trivia.destroy', $question->id) }}" class="d-inline"
                                    onsubmit="return confirm('Are you sure you want to delete this question?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
