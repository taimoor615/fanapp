@extends('layouts.admin')

@section('title', 'All Games')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Games Management</h3>
                    <div>
                        <a href="{{ route('admin.games.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New Game
                        </a>
                        <a href="{{ route('admin.games.stats') }}" class="btn btn-info">
                            <i class="fa fa-chart-bar"></i> Stats
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-2">
                                <select name="team_id" class="form-control">
                                    <option value="">All Teams</option>
                                    @foreach($teams as $team)
                                        <option value="{{ $team->id }}" {{ request('team_id') == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="live" {{ request('status') == 'live' ? 'selected' : '' }}>Live</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control" placeholder="From Date">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control" placeholder="To Date">
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search...">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-secondary">Filter</button>
                            </div>
                        </div>
                    </form>

                    <!-- Games Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th>Team</th>
                                    <th>Opponent</th>
                                    <th>Date & Time</th>
                                    <th>Venue</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Score</th>
                                    <th>Attendees</th>
                                    <th>Points</th>
                                    <th>Featured</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($games as $game)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="game_ids[]" value="{{ $game->id }}" class="game-checkbox">
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $game->team->name }}</span>
                                        </td>
                                        <td>{{ $game->opponent_team }}</td>
                                        <td>
                                            <small>{{ $game->formatted_game_date }}</small>
                                            @if($game->is_upcoming)
                                                <br><small class="text-muted">{{ $game->countdown }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $game->venue }}</td>
                                        <td>
                                            <span class="badge badge-{{ $game->home_away === 'home' ? 'success' : 'warning' }}">
                                                {{ ucfirst($game->home_away) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{
                                                $game->status === 'completed' ? 'success' :
                                                ($game->status === 'live' ? 'danger' :
                                                ($game->status === 'cancelled' ? 'dark' : 'info'))
                                            }}">
                                                {{ ucfirst($game->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($game->status === 'completed' && $game->home_score !== null)
                                                {{ $game->home_score }} - {{ $game->away_score }}
                                                @if($game->winner && $game->winner !== 'tie')
                                                    <br><small class="text-success">W: {{ $game->winner }}</small>
                                                @elseif($game->winner === 'tie')
                                                    <br><small class="text-warning">Tie</small>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $game->attendances_count ?? 0 }}</span>
                                            @if($game->attendances_count > 0)
                                                <br><a href="{{ route('admin.games.attendances', $game) }}" class="btn btn-xs btn-link">View</a>
                                            @endif
                                        </td>
                                        <td>{{ $game->attendance_points }}</td>
                                        <td>
                                            <form action="{{ route('admin.games.toggle-featured', $game) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-{{ $game->is_featured ? 'warning' : 'outline-warning' }}">
                                                    <i class="fa fa-star"></i>
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.games.show', $game) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.games.edit', $game) }}" class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @if($game->attendances_count == 0)
                                                    <form action="{{ route('admin.games.destroy', $game) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center">No games found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Bulk Actions -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-inline">
                                <select id="bulk-action" class="form-control mr-2">
                                    <option value="">Bulk Actions</option>
                                    <option value="scheduled">Mark as Scheduled</option>
                                    <option value="live">Mark as Live</option>
                                    <option value="completed">Mark as Completed</option>
                                    <option value="cancelled">Mark as Cancelled</option>
                                </select>
                                <button type="button" id="apply-bulk-action" class="btn btn-secondary mt-2">Apply</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{ $games->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Select all checkbox
    $('#select-all').change(function() {
        $('.game-checkbox').prop('checked', this.checked);
    });

    // Bulk actions
    $('#apply-bulk-action').click(function() {
        const action = $('#bulk-action').val();
        console.log(action);
        const selectedGames = $('.game-checkbox:checked').map(function() {
            return this.value;
        }).get();

        if (!action) {
            alert('Please select an action');
            return;
        }

        if (selectedGames.length === 0) {
            alert('Please select at least one game');
            return;
        }

        if (confirm(`Are you sure you want to ${action} ${selectedGames.length} game(s)?`)) {
            $.ajax({
                url: '{{ route("admin.games.bulk-update-status") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    game_ids: selectedGames,
                    status: action
                },
                success: function(response) {
                    alert(response.message);
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        }
    });
});
</script>
@endpush
@endsection
