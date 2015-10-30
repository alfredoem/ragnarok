<?php namespace Alfredoem\Ragnarok\Http\Controllers\Auth;

use Alfredoem\Authentication\SecUser as User;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;


class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $loginPath = '/login';
    protected $redirectPath = '/';
    protected $redirectAfterLogout = '/login';

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    public function getLogin()
    {
        return view('Ragnarok::auth.authenticate');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstName' => 'required|max:255',
            'lastName' => 'required|max:255',
            'email' => 'required|email|max:255|unique:SecUsers',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    public function getRegister()
    {
        return view('Ragnarok::auth.register');
    }

    protected function create(array $data)
    {
        return User::create([
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

}
