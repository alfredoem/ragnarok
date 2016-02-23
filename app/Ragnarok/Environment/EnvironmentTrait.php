<?php

namespace Alfredoem\Ragnarok\Environment;

use Illuminate\Support\Facades\Session;

trait EnvironmentTrait
{
    /**
     * Retrieve session object
     * @return string|null
     */
    public static function retrieveAll()
    {
        if (self::check()) {
            return Session::get(self::ENVIRONMENT_NAME);
        }

        return null;
    }

    /**
     * Check if the session exists
     * @return bool
     */
    public static function check()
    {
        if (Session::has(self::ENVIRONMENT_NAME)) {
            return true;
        }

        Session::forget(self::ENVIRONMENT_NAME);
        return false;
    }

    /**
     * Destroy session
     */
    public static function forget()
    {
        Session::forget(self::ENVIRONMENT_NAME);
    }
}