<?php namespace Alfredoem\Ragnarok\Http\Controllers\Auth;

use Alfredoem\Ragnarok\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

use Alfredoem\Ragnarok\RagnarokService;

use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\GenericUser;

use Alfredoem\Ragnarok\SecParameters\SecParameter;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $loginPath = '/auth/login';
    protected $redirectPath = '/';
    protected $redirectAfterLogout = '/auth/login';

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    public function getLogin()
    {
        $serverUrl =  SecParameter::find(RagnarokService::SERVER_SECURITY_URL)->value;

        if ($serverUrl != url('/')) {

            if (RagnarokService::checkConnection()) {
                return redirect()->to($serverUrl);
            }

        }

        return view('Ragnarok::auth.authenticate');
    }

    public function postLogin(LoginRequest $request)
    {
        $request->merge(['ipAddress' => $request->ip()]);

        $service = new RagnarokService;

        $login = $service->login($request->all());

        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        if ($login->success) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        if ($throttles) {
            $this->incrementLoginAttempts($request);
        }

        return redirect($this->loginPath())
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
    }

    public function AuthUser($data)
    {
        $user = [
            'id' => $data->userId,
            'email' => $data->email,
            'firstName' => $data->firstName,
            'lastName'  => $data->lastName,
            'status'    => $data->status,
            'remember_token' => $data->remember_token
        ];

        $user = new GenericUser($user);
        Auth::login($user, true);
    }

}
