<?php namespace Klaravel\Ntrust\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class NtrustPermission
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  Closure $next
	 * @param  $permissions
	 * @return mixed
	 */
	public function handle($request, Closure $next, $permissions)
	{
		if (auth()->guest() || !$request->user()->can(explode('|', $permissions))) {
			abort(403);
		}
		return $next($request);
	}
}