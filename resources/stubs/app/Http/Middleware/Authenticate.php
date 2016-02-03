<?php namespace App\Http\Middleware;

use Alfredoem\Ragnarok\RagnarokService;
use Closure;
use Illuminate\Auth\GenericUser;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->has('uid')) {
            $RagnarokService = new RagnarokService;
            $validSession = $RagnarokService->validUserSession($request->uid, $request->usession);
            if ($validSession['success'] == true) {
                $data = $validSession['data'];
                $user = [
                    'id' => $data['userId'],
                    'email' => $data['email'],
                    'firstName' => $data['firstName'],
                    'lastName'  => $data['lastName'],
                    'status'    => $data['status'],
                    'remember_token' => 'somerandomvalue',
                ];

                $user = new GenericUser($user);
                Auth::login($user, true);

                return $next($request);
            }
        }

        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('/auth/login');
            }
        }

        return $next($request);
    }
}
