<?php namespace Alfredoem\Ragnarok\Api\v1;

use Alfredoem\Ragnarok\Utilities\Make;
use Illuminate\Support\Facades\Auth;
use Alfredoem\Ragnarok\SecUsers\SecUserSessions;

class RagnarokApi
{
    protected $success = false;
    protected $user = array();

    public function login($email, $password, $remember, $ipAddress)
    {

        if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {

            $user = auth()->user();

            // Crea una session activa para el usuario
            $session = SecUserSessions::create(['userId' => $user->userId, 'sessionCode' => Make::uniqueString(),
                'ipAddress' => $ipAddress, 'status' => 1, 'dateIns' => date('Y-m-d'),
                'datetimeIns' => date('Y-m-d H:m:s')]);

            $this->user = [
                'userId' => $user->userId, 'email' => $user->email, 'firstName' => $user->firstName,
                'lastName'  => $user->lastName, 'status' => $user->status, 'ipAddress' => $ipAddress,
                'sessionCode' => $session->sessionCode, 'userSessionId' => $session->userSessionId,
                'remember_token' => $user->remember_token
            ];

            // Logout default Auth user. Se creara una session de usuario personalizada
            Auth::logout();

            $this->success = true;
        }

        return ['success' => $this->success, 'user' => $this->user];
    }

}