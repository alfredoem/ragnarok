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

            SecUserSessions::create(['userId' => $user->userId, 'sessionCode' => Make::uniqueString(), 'ipAddress' => $ipAddress,
                'status' => 1, 'datetimeIns' => date('Y-m-d H:m:s')]);

            $this->success = true;
            $this->user = [
                'userId' => $user->userId, 'email' => $user->email, 'firstName' => $user->firstName,
                'lastName'  => $user->lastName, 'status' => $user->status,
                'remember_token' => $user->remember_token
            ];

        }

        return ['success' => $this->success, 'user' => $this->user];
    }

}