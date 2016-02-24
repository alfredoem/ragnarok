<?php namespace App\Http\Middleware;

use Alfredoem\Ragnarok\Soul\AuthRagnarok;
use Closure;

class RedirectIfAuthenticated
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ( ! AuthRagnarok::check()) {
            return $next($request);
        }

        return redirect('/');
    }
}
