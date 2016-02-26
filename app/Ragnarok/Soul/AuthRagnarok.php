<?php

namespace Alfredoem\Ragnarok\Soul;

use Alfredoem\Ragnarok\Environment\EnvironmentInterface;
use Alfredoem\Ragnarok\Environment\EnvironmentTrait;
use Alfredoem\Ragnarok\SecUsers\SecUser;
use Illuminate\Support\Facades\Session;


class AuthRagnarok implements EnvironmentInterface
{
    use EnvironmentTrait;

    const ENVIRONMENT_NAME = 'Crona$user';

    protected $userRagnarok;

    /**
     * Get User Session Object attribute
     * @param $name
     * @return string|null
     */
    public function retrieve($name)
    {
        $this->userRagnarok = Session::get($this->getName());

        if (property_exists($this->userRagnarok, $name)) {
            return $this->userRagnarok->$name;
        }

        return null;
    }

    /**
     * Get User Session Object
     * @return \Alfredoem\Ragnarok\SecUsers\SecUser
     */
    public static function user()
    {
        return self::retrieveAll();
    }

    /**
     * Make User Session Object
     * @return \Alfredoem\Ragnarok\SecUsers\SecUser
     */
    public function make($user)
    {
        $RagnarokUser = new SecUser();
        $this->userRagnarok = $RagnarokUser->populate($user);
        Session::put($this->getName(), $this->userRagnarok);
        return $this->userRagnarok;
    }

    /**
     * Get a new instance of the User
     * @param $data
     * @return $this
     */
    public function instance($data)
    {
        $RagnarokUser = new SecUser();
        $this->userRagnarok = $RagnarokUser->populate($data);
        return $this->userRagnarok;
    }
}