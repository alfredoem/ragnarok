<?php namespace Alfredoem\Ragnarok\Http\Controllers\Auth;

use Alfredoem\Ragnarok\AuthRagnarok;
use Alfredoem\Ragnarok\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;

use Alfredoem\Ragnarok\RagnarokService;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $loginPath = '/auth/login';
    protected $redirectPath = '/';
    protected $redirectAfterLogout = '/auth/login';
    protected $userRagnarok;
    protected $serviceRagnarok;
    /**
     * 1: security server, 2: admin application
     * @var int
     */
    protected $environment = 1;

    public function __construct(AuthRagnarok $userRagnarok, RagnarokService $serviceRagnarok)
    {
        $this->userRagnarok = $userRagnarok;
        $this->serviceRagnarok = $serviceRagnarok;
        $this->middleware('guest', ['except' => ['getLogout', 'getVerify']]);
    }

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

    public function getLogin()
    {
        $serverUrl =  $this->serviceRagnarok->getServerSecurityUrl();

        if ($serverUrl != url('/')) {

            if ($this->serviceRagnarok->checkConnection()) {
                return redirect()->to($serverUrl);
            }

            $this->environment = 2;
        }

        Session::put('environment', $this->environment);

        return view('Ragnarok::auth.authenticate');
    }

    public function postLogin(LoginRequest $request)
    {
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

    public function getLogout()
    {
        $this->serviceRagnarok->forgetUserSession();
        Session::flush();
        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }

}