<?php namespace Alfredoem\Ragnarok\Api\v1;

use Illuminate\Support\Facades\Auth;

class RagnarokApi
{
    public function login($email, $password)
    {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            return ['status' => 1, 'statusText' => 'OK'];
        }

        return ['status' => 0, 'statusText' => 'Invalid credentials'];
    }
}