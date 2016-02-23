<?php

namespace Alfredoem\Ragnarok;

use Alfredoem\Ragnarok\Environment\EnvironmentInterface;
use Alfredoem\Ragnarok\Environment\EnvironmentTrait;
use Alfredoem\Ragnarok\SecParameters\SecParameter;
use Illuminate\Support\Facades\Session;


class RagnarokParameter implements EnvironmentInterface
{
    use EnvironmentTrait;

    const ENVIRONMENT_NAME = 'Ragnarok$parameter';

    public $SecParameter;

    /**
     * RagnarokParameter constructor.
     * @param SecParameter $SecParameter
     */
    public function __construct(SecParameter $SecParameter)
    {
        $this->SecParameter = $SecParameter;
    }

    /**
     * Return one attribute of Session Object
     * @param $name
     * @return string
     */
    public function retrieve($name)
    {
        $RagnarokParameters = new self($this->SecParameter);

        if (self::check()) {

            $RagnarokParameters = Session::get(self::ENVIRONMENT_NAME);

            if (property_exists($RagnarokParameters, $name)) {
                return $RagnarokParameters->$name;
            }

        }

        $RagnarokParameters->$name = $this->SecParameter->wherename($name)->first()->value;
        Session::put(self::ENVIRONMENT_NAME, $RagnarokParameters);
        return $RagnarokParameters->$name;
    }
}