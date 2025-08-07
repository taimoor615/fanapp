<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Team;

class TeamMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $teamSlug = $request->route('team') ?? $request->input('team_slug');

        if ($teamSlug) {
            $team = Team::where('slug', $teamSlug)->where('is_active', true)->first();

            if (!$team) {
                abort(404, 'Team not found');
            }

            // Share team data globally
            view()->share('currentTeam', $team);
            app()->instance('currentTeam', $team);
        }

        return $next($request);
    }
}
