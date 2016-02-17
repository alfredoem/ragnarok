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
        $this->fill($user);
        Session::put(self::SESSION_NAME, $this);
        return Session::get(self::SESSION_NAME);
    }

    public function instance($data)
    {
        $this->fill($data);
        return $this;
    }

    public function fill($data)
    {
        $this->userId = $data->userId;
        $this->email = $data->email;
        $this->firstName = $data->firstName;
        $this->lastName = $data->lastName;
        $this->status = $data->status;
        $this->remember_token = $data->remember_token;
        $this->userSessionId = $data->userSessionId;
        $this->sessionCode = $data->sessionCode;
        $this->ipAddress = $data->ipAddress;
        $this->environment = $data->environment;
    }

    public static function forget()
    {
        Session::forget(self::SESSION_NAME);
    }

}