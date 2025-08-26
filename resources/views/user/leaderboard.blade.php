@extends('layouts.user-dashboard')

@section('title', 'Trivia Leaderboard')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="text-center mb-4">
                <h2><i class="fa fa-trophy text-warning"></i> Leaderboard</h2>
                <p class="lead">Top scoring champions!</p>
            </div>

            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="fa fa-crown"></i> Hall of Fame</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Rank</th>
                                    <th>Player</th>
                                    <th>Score</th>
                                    <th>Badge</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaderboard as $player)
                                <tr class="{{ $player['rank'] <= 3 ? 'table-warning' : '' }}">
                                    <td>
                                        @if($player['rank'] == 1)
                                            <i class="fa fa-trophy text-warning fs-4"></i> #{{ $player['rank'] }}
                                        @elseif($player['rank'] == 2)
                                            <i class="fa fa-medal text-secondary fs-4"></i> #{{ $player['rank'] }}
                                        @elseif($player['rank'] == 3)
                                            <i class="fa fa-award text-warning fs-4"></i> #{{ $player['rank'] }}
                                        @else
                                            #{{ $player['rank'] }}
                                        @endif
                                    </td>
                                    <td class="fw-bold">{{ $player['first_name'] }} {{ $player['last_name'] }}</td>
                                    <td>
                                        <span class="badge bg-primary fs-6">{{ number_format($player['total_points']) }} pts</span>
                                    </td>
                                    <td>
                                        @if($player['rank'] == 1)
                                            <span class="badge bg-warning text-dark"><i class="fa fa-crown"></i> Champion</span>
                                        @elseif($player['rank'] <= 3)
                                            <span class="badge bg-success"><i class="fa fa-star"></i> Top 3</span>
                                        @elseif($player['total_points'] >= 500)
                                            <span class="badge bg-info"><i class="fa fa-fire"></i> Expert</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="fa fa-user"></i> Player</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
