<?php

namespace App\Fhir;

enum Cardinality: int
{
    case One = 0;
    case ZeroOrOne = 2;
    case ZeroOrMany = 3;
    case OneOrMany = 4;
}
