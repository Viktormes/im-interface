<?php

namespace App\Exceptions;

class ResourceRequiredException extends InvalidContentException
{
    public function __construct()
    {
        parent::__construct('A resource is required', 400);
    }
}
