<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirPositiveInt;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUrl;
use App\Fhir\Cardinality;

class VirtualServiceDetail extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'channelType' => [
                'type' => Coding::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'valueSet' => 'virtual-service-type',
            ],
            'address' => [
                'types' => [
                    'addressUrl' => FhirUrl::class,
                    'addressString' => FhirString::class,
                    'addressContactPoint' => ContactPoint::class,
                    'addressExtendedContactDetail' => ExtendedContactDetail::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'additionalInfo' => [
                'type' => FhirUrl::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'maxParticipants' => [
                'type' => FhirPositiveInt::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'sessionKey' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
        ]);
    }
}
