<?php

namespace App\Fhir\Base\Resource\DomainResource;

use App\Fhir\Base\Element\Annotation;
use App\Fhir\Base\Element\BackboneElement\DeviceConformsTo;
use App\Fhir\Base\Element\BackboneElement\DeviceName;
use App\Fhir\Base\Element\BackboneElement\DeviceProperty;
use App\Fhir\Base\Element\BackboneElement\DeviceUdiCarrier;
use App\Fhir\Base\Element\BackboneElement\DeviceVersion;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\CodeableReference;
use App\Fhir\Base\Element\ContactPoint;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDateTime;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUri;
use App\Fhir\Base\Element\Identifier;
use App\Fhir\Base\Element\Quantity\Count;
use App\Fhir\Base\Element\Quantity\Duration;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Resource\DomainResource;
use App\Fhir\Cardinality;

class Device extends DomainResource
{
    public static function searchParameters(): array
    {
        return [];
    }

    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'identifier' => [
                'type' => Identifier::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'displayName' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'definition' => [
                'type' => CodeableReference::class,
                'target' => [
                    DeviceDefinition::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'udiCarrier' => [
                'type' => DeviceUdiCarrier::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'status' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'isModifier' => true,
                'valueSet' => 'device-status',
            ],
            'availabilityStatus' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'device-availability-status',
            ],
            'biologicalSourceEvent' => [
                'type' => Identifier::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'manufacturer' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'manufactureDate' => [
                'type' => FhirDateTime::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'expirationDate' => [
                'type' => FhirDateTime::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'lotNumber' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'serialNumber' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'name' => [
                'type' => DeviceName::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'modelNumber' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'partNumber' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'category' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'valueSet' => 'device-category',
            ],
            'type' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'valueSet' => 'device-type',
            ],
            'version' => [
                'type' => DeviceVersion::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'conformsTo' => [
                'type' => DeviceConformsTo::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'property' => [
                'type' => DeviceProperty::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'mode' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'valueSet' => 'device-operation-mode',
            ],
            'cycle' => [
                'type' => Count::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'duration' => [
                'type' => Duration::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'owner' => [
                'type' => Reference::class,
                'target' => [
                    Organization::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'contact' => [
                'type' => ContactPoint::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'location' => [
                'type' => Reference::class,
                'target' => [
                    Location::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'url' => [
                'type' => FhirUri::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'endpoint' => [
                'type' => Reference::class,
                'target' => [
                    Endpoint::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'gateway' => [
                'type' => CodeableReference::class,
                'target' => [
                    Device::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'note' => [
                'type' => Annotation::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'safety' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'device-safety',
            ],
            'parent' => [
                'type' => Reference::class,
                'target' => [
                    Device::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
            ],
        ]);
    }
}
