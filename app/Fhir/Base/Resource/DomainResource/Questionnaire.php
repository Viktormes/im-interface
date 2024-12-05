<?php

namespace App\Fhir\Base\Resource\DomainResource;

use App\Fhir\Base\Element\BackboneElement\QuestionnaireItem;
use App\Fhir\Base\Element\CodeableConcept;
use App\Fhir\Base\Element\Coding;
use App\Fhir\Base\Element\ContactDetail;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBoolean;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCanonical;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDate;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDateTime;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirMarkdown;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUri;
use App\Fhir\Base\Element\Identifier;
use App\Fhir\Base\Element\Period;
use App\Fhir\Base\Element\UsageContext;
use App\Fhir\Base\Resource\DomainResource;
use App\Fhir\Cardinality;

class Questionnaire extends DomainResource
{
    public static function searchParameters(): array
    {
        return [];
    }

    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'url' => [
                'type' => FhirUri::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'identifier' => [
                'type' => Identifier::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'version' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'versionAlgorithm' => [
                'types' => [
                    'versionAlgorithmString' => FhirString::class,
                    'versionAlgorithmCoding' => Coding::class,
                ],
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'name' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'title' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'translatable' => true,
            ],
            'derivedFrom' => [
                'type' => FhirCanonical::class,
                'target' => [
                    Questionnaire::class,
                ],
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'status' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::One,
                'summary' => true,
                'isModifier' => true,
                'valueSet' => 'publication-status',
            ],
            'experimental' => [
                'type' => FhirBoolean::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'subjectType' => [
                'type' => FhirCode::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'resource-types',
            ],
            'date' => [
                'type' => FhirDateTime::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'publisher' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'translatable' => true,
            ],
            'contact' => [
                'type' => ContactDetail::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'description' => [
                'type' => FhirMarkdown::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
                'translatable' => true,
            ],
            'useContext' => [
                'type' => UsageContext::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
            ],
            'jurisdiction' => [
                'type' => CodeableConcept::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'jurisdiction',
            ],
            'purpose' => [
                'type' => FhirMarkdown::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'translatable' => true,
            ],
            'copyright' => [
                'type' => FhirMarkdown::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'translatable' => true,
            ],
            'copyrightLabel' => [
                'type' => FhirString::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'translatable' => true,
            ],
            'approvalDate' => [
                'type' => FhirDate::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'lastReviewDate' => [
                'type' => FhirDate::class,
                'cardinality' => Cardinality::ZeroOrOne,
            ],
            'effectivePeriod' => [
                'type' => Period::class,
                'cardinality' => Cardinality::ZeroOrOne,
                'summary' => true,
            ],
            'code' => [
                'type' => Coding::class,
                'cardinality' => Cardinality::ZeroOrMany,
                'summary' => true,
                'valueSet' => 'questionnaire-questions',
            ],
            'item' => [
                'type' => QuestionnaireItem::class,
                'cardinality' => Cardinality::ZeroOrMany,
            ],
        ]);
    }
}
