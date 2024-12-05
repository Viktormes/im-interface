<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBase64Binary;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDateTime;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDecimal;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInteger64;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirPositiveInt;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUrl;
use App\Fhir\Cardinality;

class Attachment extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'contentType' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'mimetypes',
            ],
            'language' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'all-languages',
            ],
            'data' => [
                'type' => FhirBase64Binary::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'url' => [
                'type' => FhirUrl::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'size' => [
                'type' => FhirInteger64::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'hash' => [
                'type' => FhirBase64Binary::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'title' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'translatable' => true,
            ],
            'creation' => [
                'type' => FhirDateTime::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'height' => [
                'type' => FhirPositiveInt::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'width' => [
                'type' => FhirPositiveInt::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'frames' => [
                'type' => FhirPositiveInt::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'duration' => [
                'type' => FhirDecimal::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'pages' => [
                'type' => FhirPositiveInt::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
        ]);
    }
}
