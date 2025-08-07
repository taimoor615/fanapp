@extends('layouts.admin')

@section('title', 'Create Game')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Game</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.games.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back to Games
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.games.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Team Selection -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="team_id">Team <span class="text-danger">*</span></label>
                                    <select name="team_id" id="team_id" class="form-control @error('team_id') is-invalid @enderror" required>
                                        <option value="">Select Team</option>
                                        @foreach($teams as $team)
                                            <option value="{{ $team->id }}" {{ old('team_id') == $team->id ? 'selected' : '' }}>
                                                {{ $team->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('team_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Opponent Team -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="opponent_team">Opponent Team <span class="text-danger">*</span></label>
                                    <input type="text" name="opponent_team" id="opponent_team"
                                           class="form-control @error('opponent_team') is-invalid @enderror"
                                           value="{{ old('opponent_team') }}" required>
                                    @error('opponent_team')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Game Date -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="game_date">Game Date & Time <span class="text-danger">*</span></label>
                                    <input type="datetime-local" name="game_date" id="game_date"
                                           class="form-control @error('game_date') is-invalid @enderror"
                                           value="{{ old('game_date') }}" required>
                                    @error('game_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Game Type -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="home_away">Game Type <span class="text-danger">*</span></label>
                                    <select name="home_away" id="home_away" class="form-control @error('home_away') is-invalid @enderror" required>
                                        <option value="">Select Type</option>
                                        <option value="home" {{ old('home_away') == 'home' ? 'selected' : '' }}>Home</option>
                                        <option value="away" {{ old('home_away') == 'away' ? 'selected' : '' }}>Away</option>
                                    </select>
                                    @error('home_away')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Venue -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="venue">Venue <span class="text-danger">*</span></label>
                                    <input type="text" name="venue" id="venue"
                                           class="form-control @error('venue') is-invalid @enderror"
                                           value="{{ old('venue') }}" required>
                                    @error('venue')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Attendance Points -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="attendance_points">Attendance Points <span class="text-danger">*</span></label>
                                    <input type="number" name="attendance_points" id="attendance_points"
                                           class="form-control @error('attendance_points') is-invalid @enderror"
                                           value="{{ old('attendance_points', 50) }}" min="1" max="1000" required>
                                    @error('attendance_points')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Ticket URL -->
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="ticket_url">Ticket URL</label>
                                    <input type="url" name="ticket_url" id="ticket_url"
                                           class="form-control @error('ticket_url') is-invalid @enderror"
                                           value="{{ old('ticket_url') }}" placeholder="https://...">
                                    @error('ticket_url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Ticket Price -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ticket_price">Ticket Price ($)</label>
                                    <input type="number" name="ticket_price" id="ticket_price"
                                           class="form-control @error('ticket_price') is-invalid @enderror"
                                           value="{{ old('ticket_price') }}" step="0.01" min="0">
                                    @error('ticket_price')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Featured Game -->
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="is_featured" id="is_featured"
                                       class="custom-control-input" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_featured">
                                    Featured Game
                                    <small class="text-muted d-block">Featured games will be highlighted in the app</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Create Game
                        </button>
                        <a href="{{ route('admin.games.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Set minimum date to today
    const now = new Date();
    const today = now.toISOString().slice(0, 16); // Format: YYYY-MM-DDTHH:mm
    $('#game_date').attr('min', today);
});
</script>
@endpush
@endsection
