<?php

namespace Alfredoem\Ragnarok\Http\Controllers\Auth;

use Alfredoem\Ragnarok\Soul\AuthRagnarok;
use Alfredoem\Ragnarok\Http\Requests\LoginRequest;
use Alfredoem\Ragnarok\Soul\RagnarokParameter;
use Alfredoem\Ragnarok\SecParameters\SecParameter;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

use Alfredoem\Ragnarok\Soul\RagnarokService;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $loginPath = '/auth/login';
    protected $redirectPath = '/';
    protected $redirectAfterLogout = '/auth/login';
    protected $userRagnarok;
    protected $serviceRagnarok;
    protected $maxLoginAttempts;
    protected $paramRagnarok;

    /**
     * Determina en que entorno se esta ejecutando el componente
     * 1: security server, 2: admin application
     * @var int
     */
    protected $environment = 1;

    /**
     * AuthController constructor.
     * @param AuthRagnarok $userRagnarok
     * @param RagnarokService $serviceRagnarok
     * @param RagnarokParameter $paramRagnarok
     */
    public function __construct(AuthRagnarok $userRagnarok,
                                RagnarokService $serviceRagnarok,
                                RagnarokParameter $paramRagnarok)
    {
        $this->userRagnarok = $userRagnarok;
        $this->serviceRagnarok = $serviceRagnarok;
        $this->paramRagnarok = $paramRagnarok;
        $this->middleware('guest', ['except' => ['getLogout', 'getVerify']]);
    }

    /**
     * Check if the user have a active session
     * @param $userId
     * @param $sessionCode
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getVerify($userId, $sessionCode)
    {
        $validSession = $this->serviceRagnarok->validUserSession($userId, $sessionCode);

        if ($validSession->success) {
            $data = $validSession->response->data;
            $this->userRagnarok->make($data);
            return redirect()->to('/');
        }

        return $this->getLogout();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getLogin()
    {
        $serverUrl =  $this->paramRagnarok->retrieve(SecParameter::SERVER_SECURITY_URL);

        if ($serverUrl != url('/')) {

            if ($this->serviceRagnarok->checkConnection()) {
                return redirect()->to($serverUrl);
            }

            $this->environment = 2;
        }

        Session::put('environment', $this->environment);

        return view('Ragnarok::auth.authenticate');
    }

    /**
     * @param LoginRequest $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function postLogin(LoginRequest $request)
    {
        $this->maxLoginAttempts = $this->paramRagnarok->retrieve(SecParameter::MAX_LOGIN_ATTEMPTS);

        $request->merge(['ipAddress' => $request->ip()]);

        $login = $this->serviceRagnarok->login($request->all());

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

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function getLogout()
    {
        $this->serviceRagnarok->forgetUserSession();
        Session::flush();
        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

}