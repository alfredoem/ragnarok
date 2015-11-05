<?php namespace Alfredoem\Ragnarok\Api\v1;

use Illuminate\Support\Facades\Auth;

class RagnarokApi
{
    protected $success = false;
    protected $user = array();

    public function login($email, $password, $remember)
    {
        if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {

            $user = auth()->user();
            Auth::logout();

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