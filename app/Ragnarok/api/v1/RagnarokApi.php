<?php namespace Alfredoem\Ragnarok\Api\v1;

use Illuminate\Support\Facades\Auth;

class RagnarokApi
{
    public function login($email, $password, $remember)
    {
        if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {

            $user = auth()->user();
            Auth::logout();
            return ['status' => 1, 'statusText' => 'OK', 'user' => ['userId' => $user->userId, 'email' => $user->email,
                                                                    'firstName' => $user->firstName, 'lastName'  => $user->lastName,
                                                                    'status'    => $user->status, 'remember_token' => $user->remember_token
                                                                    ]
            ];
        }

        return ['status' => 0, 'statusText' => 'Invalid credentials', 'user' => []];
    }

}