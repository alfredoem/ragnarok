<?php namespace Alfredoem\Ragnarok;

use Illuminate\Support\Facades\Session;

class AuthRagnarok
{
    const SESSION_NAME = 'Crona$user';

    public $userId = '';
    public $email = '';
    public $firstName = '';
    public $lastName = '';
    public $status = '';
    public $remember_token = '';
    public $userSessionId = '';
    public $sessionCode = '';
    public $ipAddress = '';
    public $environment = 0;

    /**
     * @return bool
     */
    public static function check()
    {
        if (Session::has(self::SESSION_NAME)) {
            return true;
        }

        Session::forget(self::SESSION_NAME);
        return false;
    }

    /**
     * @return \Alfredoem\Ragnarok\AuthRagnarok
     */
    public static function user()
    {
        if (self::check()) {
            return Session::get(self::SESSION_NAME);
        }

        return null;
    }

    /**
     * @return \Alfredoem\Ragnarok\AuthRagnarok
     */
    public function make($user)
    {
        $this->userId = $user->userId;
        $this->email = $user->email;
        $this->firstName = $user->firstName;
        $this->lastName = $user->lastName;
        $this->status = $user->status;
        $this->remember_token = $user->remember_token;
        $this->userSessionId = $user->userSessionId;
        $this->sessionCode = $user->sessionCode;
        $this->ipAddress = $user->ipAddress;
        $this->environment = $user->environment;

        Session::put(self::SESSION_NAME, $this);

        return Session::get(self::SESSION_NAME);
    }

    public static function forget()
    {
        Session::forget(self::SESSION_NAME);
    }

}