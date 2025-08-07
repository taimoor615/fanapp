@extends('layouts.user-dashboard')

@section('title', 'Trivia History')

@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4><i class="fa fa-history"></i> My Trivia History</h4>
        </div>
        <div class="card-body">
            @if($attempts->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Question</th>
                                <th>Answer</th>
                                <th>Correct</th>
                                <th>Points</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attempts as $index => $attempt)
                                <tr>
                                    <td>{{ $attempts->firstItem() + $index }}</td>
                                    <td>{{ $attempt->question->question ?? 'N/A' }}</td>
                                    <td>{{ $attempt->selected_answer }}</td>
                                    <td>
                                        @if($attempt->is_correct)
                                            <span class="badge bg-success">Yes</span>
                                        @else
                                            <span class="badge bg-danger">No</span>
                                        @endif
                                    </td>
                                    <td>{{ $attempt->points_earned }}</td>
                                    <td>{{ $attempt->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $attempts->links() }} <!-- Pagination links -->
            @else
                <p class="text-muted">You haven’t attempted any trivia yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection
