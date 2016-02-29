<?php

namespace Alfredoem\Ragnarok\Soul;

use Alfredoem\Ragnarok\Environment\EnvironmentInterface;
use Alfredoem\Ragnarok\Environment\EnvironmentTrait;
use Alfredoem\Ragnarok\SecParameters\SecParameter;
use Illuminate\Support\Facades\Session;


class RagnarokParameter implements EnvironmentInterface
{
    use EnvironmentTrait;

    const ENVIRONMENT_NAME = 'Ragnarok$parameter';

    public $SecParameter;

    public function __construct(SecParameter $SecParameter)
    {
        $this->SecParameter = $SecParameter;
    }

    /**
     * Get value of a RagnarokParameter attribute
     * @param $name: the name of parameter in the database
     * @param $force: force to get the parameter value of the database
     * @return mixed
     */
    public function retrieve($name, $force = false)
    {
        $RagnarokParameters = new self($this->SecParameter);

        if (self::check()) {
            $RagnarokParameters = Session::get($this->getName());

            if (property_exists($RagnarokParameters, $name)) {

                if($force) {
                    $value = $this->SecParameter->wherename($name)->first()->value;
                    Session::push($this->getName().$name, $value);
                }

                $value = $RagnarokParameters->$name;
                return $value;
            }
        }

        $RagnarokParameters->$name = $this->SecParameter->wherename($name)->first()->value;
        Session::put($this->getName(), $RagnarokParameters);
        return $RagnarokParameters->$name;
    }
}