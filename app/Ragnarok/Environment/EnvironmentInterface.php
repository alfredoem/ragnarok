<?php namespace Alfredoem\Ragnarok\Environment;


interface EnvironmentInterface
{
    /**
     * Return one attribute of Session Object
     * @param $name
     * @return string
     */
    public function retrieve($key);

    /**
     * Return session object
     * @return object
     */
    public static function retrieveAll();

    /**
     * Check if session exists
     * @return bool
     */
    public static function check();

    /**
     * Destroy session
     */
    public static function forget();
}