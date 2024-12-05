<?php

namespace App\Exceptions;

class UnknownTypeException extends InvalidContentException
{
    public function __construct(string $type)
    {
        parent::__construct("Resource Type \"$type\" not recognised", 404);
    }
}
