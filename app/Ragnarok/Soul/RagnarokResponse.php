<?php

namespace Alfredoem\Ragnarok\Soul;


class RagnarokResponse
{

    public $success;
    public $data;

    public function make($success, $data)
    {
        $this->success = $success;
        $this->data = $data;
        return $this;
    }

}