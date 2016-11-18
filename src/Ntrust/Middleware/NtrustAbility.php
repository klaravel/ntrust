<?php namespace Klaravel\Ntrust\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class NtrustAbility
{
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param Closure $next
	 * @param $roles
	 * @param $permissions
	 * @param bool $validateAll
	 * @return mixed
	 */
	public function handle($request, Closure $next, $roles, $permissions, $validateAll = false)
	{
		if (auth()->guest() || !$request->user()->ability(explode('|', $roles), explode('|', $permissions), array('validate_all' => $validateAll))) {
			abort(403);
		}
		return $next($request);
	}
}