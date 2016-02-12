<?php namespace Alfredoem\Ragnarok\Http\Controllers\Auth;

use Alfredoem\Ragnarok\Http\Requests\LoginRequest;
use Alfredoem\Ragnarok\SecUsers\SecUserSessions;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

use Alfredoem\Ragnarok\RagnarokService;

use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\GenericUser;

use Alfredoem\Ragnarok\SecParameters\SecParameter;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $loginPath = '/auth/login';
    protected $redirectPath = '/';
    protected $redirectAfterLogout = '/auth/login';

    public function __construct()
    {
        $this->middleware('guest', ['except' => ['getLogout', 'getVerify']]);
    }

    public function getVerify($userId, $sessionCode)
    {
        $RagnarokService = new RagnarokService;
        $validSession = $RagnarokService->validUserSession($userId, $sessionCode);

        if ($validSession['status'] == true) {
            $data = $validSession['response']['data'];
            $user = [
                'id' => $data['userId'],
                'email' => $data['email'],
                'firstName' => $data['firstName'],
                'lastName'  => $data['lastName'],
                'status'    => $data['status'],
                'userSessionId' => $data['status'],
                'sessionId' => $data['userSessionId'],
                'sessionCode' => $data['sessionCode'],
                'ipAddress' => $data['ipAddress'],
                'remember_token' => 'somerandomvalue',
            ];

            $user = new GenericUser($user);
            Auth::login($user, true);

            return redirect()->to('/');
        }

        return $this->getLogout();
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

    public function getLogout()
    {
        $session = SecUserSessions::find(session('userSessionId'));

        if ($session) {
            $session->update(['status' => 0]);
        }

        Auth::logout();
        Session::flush();
        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

}
