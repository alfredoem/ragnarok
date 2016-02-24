<?php

namespace Alfredoem\Ragnarok\Soul;

use Alfredoem\Ragnarok\Environment\EnvironmentInterface;
use Alfredoem\Ragnarok\Environment\EnvironmentTrait;
use Illuminate\Support\Facades\Session;

class AuthRagnarok implements EnvironmentInterface
{
    use EnvironmentTrait;

    const ENVIRONMENT_NAME = 'Crona$user';

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
     * Get User Session Object attribute
     * @param $name
     * @return string|null
     */
    public function retrieve($name)
    {
        $RagnarokUser = Session::get(self::ENVIRONMENT_NAME);

        if (property_exists($RagnarokUser, $name)) {
            return $RagnarokUser->$name;
        }

        return null;
    }

    /**
     * Get User Session Object
     * @return \Alfredoem\Ragnarok\Soul\AuthRagnarok
     */
    public static function user()
    {
        return self::retrieveAll();
    }

    /**
     * Make User Session Object
     * @return \Alfredoem\Ragnarok\Soul\AuthRagnarok
     */
    public function make($user)
    {
        $this->fill($user);
        Session::put(self::ENVIRONMENT_NAME, $this);
        return Session::get(self::ENVIRONMENT_NAME);
    }

    /**
     * Get a new instance of the User
     * @param $data
     * @return $this
     */
    public function instance($data)
    {
        $this->fill($data);
        return $this;
    }

    /**
     * Fill the user attributes
     * @param $data
     */
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
}