<?php namespace Alfredoem\Ragnarok;

use Alfredoem\Ragnarok\Utilities\Make;
use Illuminate\Support\Facades\Session;

class AuthRagnarok
{

    protected $sessionName = 'Crona$user';

    public static function check()
    {
        if(Session::has('Crona$user')){
            return true;
        }else{
            Session::forget('Crona$user');
            return false;
        }
    }


    public static function user()
    {
        if(self::check()){
            return Session::get('Crona$user');
        }else{
            return null;
        }
    }


    public static function make($user)
    {
        Session::put('Crona$user', '');

        $data = [
            'userId' => $user->userId,
            'email' => $user->email,
            'firstName' => $user->firstName,
            'lastName'  => $user->lastName,
            'status'    => $user->status,
            'userSessionId' => $user->userSessionId,
            'sessionCode' => $user->sessionCode,
            'ipAddress' => $user->ipAddress,
            'remember_token' => $user->remember_token,
        ];

        Session::put('Crona$user', Make::arrayToObject($data));
    }




}


