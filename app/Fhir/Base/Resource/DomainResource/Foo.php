<?php

namespace App\Fhir\Base\Resource\DomainResource;

use App\Fhir\Base\Resource\DomainResource;

class Foo extends DomainResource
{
    public static function searchParameters(): array
    {
        return [];
    }

    public function structure(): array
    {
        return array_merge(parent::structure(), [

        ]);
    }
}
