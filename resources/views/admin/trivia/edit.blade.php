@extends('layouts.user-dashboard')

@section('title', 'Update Question')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="fa fa-edit"></i> Edit Trivia Question</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.trivia.update', $question->id) }}">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="team_id" value="1">
                        <input type="hidden" name="category" value="category">
                        <div class="mb-3">
                            <label for="question" class="form-label">Question *</label>
                            <textarea class="form-control @error('question') is-invalid @enderror"
                                        id="question" name="question" rows="3" required>{{ old('question', $question->question) }}</textarea>
                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Question Type *</label>
                                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="multiple_choice" {{ old('type', $question->type) == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                        <option value="true_false" {{ old('type', $question->type) == 'true_false' ? 'selected' : '' }}>True/False</option>
                                        <option value="text" {{ old('type', $question->type) == 'text' ? 'selected' : '' }}>Text Answer</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="difficulty" class="form-label">Difficulty *</label>
                                    <select class="form-select @error('difficulty') is-invalid @enderror" id="difficulty" name="difficulty" required>
                                        <option value="">Select Difficulty</option>
                                        <option value="easy" {{ old('difficulty', $question->difficulty) == 'easy' ? 'selected' : '' }}>Easy</option>
                                        <option value="medium" {{ old('difficulty', $question->difficulty) == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="hard" {{ old('difficulty', $question->difficulty) == 'hard' ? 'selected' : '' }}>Hard</option>
                                    </select>
                                    @error('difficulty')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="points" class="form-label">Points *</label>
                                    <input type="number" class="form-control @error('points') is-invalid @enderror"
                                            id="points" name="points" value="{{ old('points', $question->points) }}" min="1" max="100" required>
                                    @error('points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="options-container">
                            <label class="form-label">Answer Options *</label>
                            <div id="options-list">
                                @foreach($question->options as $index => $option)
                                    @php
                                        $isCorrect = $question->correct_answer === $option;
                                    @endphp
                                    <div class="option-group mb-2">
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <input class="form-check-input mt-0" type="radio" name="correct_answer"
                                                    value="{{ $index }}" {{ $isCorrect ? 'checked' : '' }} required>
                                            </div>
                                            <input type="text" class="form-control" name="options[{{ $index }}][text]"
                                                placeholder="Option {{ $index + 1 }}" value="{{ $option }}" required>
                                            @if($index > 1)
                                                <button type="button" class="btn btn-outline-danger remove-option">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="add-option">
                                <i class="fa fa-plus"></i> Add Option
                            </button>
                        </div>

                        <hr>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                                    {{ old('is_active', $question->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <i class="fa fa-check-circle text-success"></i> Active (available for gameplay)
                            </label>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.trivia.show', $question->id) }}" class="btn btn-secondary me-md-2">
                                <i class="fa fa-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fa fa-save"></i> Update Question
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let optionCount = {{ count($question->options) }};

    document.getElementById('add-option').addEventListener('click', function() {
        if (optionCount < 6) {
            const optionsList = document.getElementById('options-list');
            const newOption = document.createElement('div');
            newOption.className = 'option-group mb-2';
            newOption.innerHTML = `
                <div class="input-group">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="radio" name="correct_answer" value="${optionCount}" required>
                    </div>
                    <input type="text" class="form-control" name="options[${optionCount}][text]" placeholder="Option ${optionCount + 1}" required>
                    <button type="button" class="btn btn-outline-danger remove-option">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            `;
            optionsList.appendChild(newOption);
            optionCount++;
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-option') || e.target.parentElement.classList.contains('remove-option')) {
            if (document.querySelectorAll('.option-group').length > 2) {
                e.target.closest('.option-group').remove();
            }
        }
    });
</script>
@endsection
