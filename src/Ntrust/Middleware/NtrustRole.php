<?php namespace Klaravel\Ntrust\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class NtrustRole
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  Closure $next
	 * @param  $roles
	 * @return mixed
	 */
	public function handle($request, Closure $next, $roles)
	{
		if (auth()->guest() || !$request->user()->hasRole(explode('|', $roles))) {
			abort(403);
		}
		return $next($request);
	}
}