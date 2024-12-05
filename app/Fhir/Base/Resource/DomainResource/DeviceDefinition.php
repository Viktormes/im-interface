<?php

namespace App\Fhir\Base\Resource\DomainResource;

use App\Fhir\Base\Element\Annotation;
use App\Fhir\Base\Element\BackboneElement\DeviceDefinitionChargeItem;
use App\Fhir\Base\Element\BackboneElement\DeviceDefinitionClassification;
use App\Fhir\Base\Element\BackboneElement\DeviceDefinitionConformsTo;
use App\Fhir\Base\Element\BackboneElement\DeviceDefinitionCorrectiveAction;
use App\Fhir\Base\Element\BackboneElement\DeviceDefinitionDeviceName;
use App\Fhir\Base\Element\BackboneElement\DeviceDefinitionGuideline;
use App\Fhir\Base\Element\BackboneElement\DeviceDefinitionHasPart;
use App\Fhir\Base\Element\BackboneElement\DeviceDefinitionLink;
use App\Fhir\Base\Element\BackboneElement\DeviceDefinitionMaterial;
use App\Fhir\Base\Element\BackboneElement\DeviceDefinitionPackaging;
use App\Fhir\Base\Element\BackboneElement\DeviceDefinitionProperty;
use App\Fhir\Base\Element\BackboneElement\DeviceDefinitionRegulatoryIdentifier;
use App\Fhir\Base\Element\BackboneElement\DeviceDefinitionUdiDeviceIdentifier;
use App\Fhir\Base\Element\BackboneElement\DeviceDefinitionVersion;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\ContactPoint;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirMarkdown;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\Identifier;
use App\Fhir\Base\Element\ProductShelfLife;
use App\Fhir\Base\Element\Reference;
use App\Fhir\Base\Resource\DomainResource;
use App\Fhir\Cardinality;

class DeviceDefinition extends DomainResource
{
    public static function searchParameters(): array
    {
        return [];
    }

    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'description' => [
                'type' => FhirMarkdown::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'identifier' => [
                'type' => Identifier::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'udiDeviceIdentifier' => [
                'type' => DeviceDefinitionUdiDeviceIdentifier::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'regulatoryIdentifier' => [
                'type' => DeviceDefinitionRegulatoryIdentifier::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'partNumber' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'manufacturer' => [
                'type' => Reference::class,
                'target' => [
                    Organization::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'deviceName' => [
                'type' => DeviceDefinitionDeviceName::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'modelNumber' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'classification' => [
                'type' => DeviceDefinitionClassification::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'conformsTo' => [
                'type' => DeviceDefinitionConformsTo::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'hasPart' => [
                'type' => DeviceDefinitionHasPart::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'packaging' => [
                'type' => DeviceDefinitionPackaging::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'version' => [
                'type' => DeviceDefinitionVersion::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'safety' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'device-safety',
            ],
            'shelfLifeStorage' => [
                'type' => ProductShelfLife::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'languageCode' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'property' => [
                'type' => DeviceDefinitionProperty::class,
                'cardinality' => Cardinality::ZeroOrMany,
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
            'link' => [
                'type' => DeviceDefinitionLink::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'note' => [
                'type' => Annotation::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'material' => [
                'type' => DeviceDefinitionMaterial::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
            'productionIdentifierInUDI' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'valueSet' => 'device-productionidentifierinudi',
            ],
            'guideline' => [
                'type' => DeviceDefinitionGuideline::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'correctiveAction' => [
                'type' => DeviceDefinitionCorrectiveAction::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'chargeItem' => [
                'type' => DeviceDefinitionChargeItem::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ]);
    }
}
