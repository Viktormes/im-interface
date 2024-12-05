<?php

namespace App\Fhir\Base\Element;

use App\Fhir\Base\Element;
use App\Fhir\Base\Element\BackboneType\Timing;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBase64Binary;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirBoolean;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCanonical;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirCode;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDate;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDateTime;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirDecimal;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirId;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInstant;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInteger;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirInteger64;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirMarkdown;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirOid;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirPositiveInt;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirString;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirTime;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUnsignedInt;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUri;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUrl;
use App\Fhir\Base\Element\DataType\PrimitiveType\FhirUuid;
use App\Fhir\Base\Element\Quantity\Age;
use App\Fhir\Base\Element\Quantity\Count;
use App\Fhir\Base\Element\Quantity\Distance;
use App\Fhir\Base\Element\Quantity\Duration;
use App\Fhir\Cardinality;

class Extension extends Element
{
    public function structure(): array
    {
        return array_merge(parent::structure(), [
            'url' => [
                'type' => FhirUri::class,
                'cardinality' => Cardinality::One,
            ],
            'value' => [
                'types' => [
                    'valueBase64Binary' => FhirBase64Binary::class,
                    'valueBoolean' => FhirBoolean::class,
                    'valueCanonical' => FhirCanonical::class,
                    'valueCode' => FhirCode::class,
                    'valueDate' => FhirDate::class,
                    'valueDateTime' => FhirDateTime::class,
                    'valueDecimal' => FhirDecimal::class,
                    'valueId' => FhirId::class,
                    'valueInstant' => FhirInstant::class,
                    'valueInteger' => FhirInteger::class,
                    'valueInteger64' => FhirInteger64::class,
                    'valueMarkdown' => FhirMarkdown::class,
                    'valueOid' => FhirOid::class,
                    'valuePositiveInt' => FhirPositiveInt::class,
                    'valueString' => FhirString::class,
                    'valueTime' => FhirTime::class,
                    'valueUnsignedInt' => FhirUnsignedInt::class,
                    'valueUri' => FhirUri::class,
                    'valueUrl' => FhirUrl::class,
                    'valueUuid' => FhirUuid::class,
                    'valueAddress' => Address::class,
                    'valueAge' => Age::class,
                    'valueAnnotation' => Annotation::class,
                    'valueAttachment' => Attachment::class,
                    'valueCodeableConcept' => CodeableConcept::class,
                    'valueCodeableReference' => CodeableReference::class,
                    'valueCoding' => Coding::class,
                    'valueContactPoint' => ContactPoint::class,
                    'valueCount' => Count::class,
                    'valueDistance' => Distance::class,
                    'valueDuration' => Duration::class,
                    'valueHumanName' => HumanName::class,
                    'valueIdentifier' => Identifier::class,
                    'valueMoney' => Money::class,
                    'valuePeriod' => Period::class,
                    'valueQuantity' => Quantity::class,
                    'valueRange' => Range::class,
                    'valueRatio' => Ratio::class,
                    'valueRatioRange' => RatioRange::class,
                    'valueReference' => Reference::class,
                    'valueSampledData' => SampledData::class,
                    'valueSignature' => Signature::class,
                    'valueTiming' => Timing::class,
                    'valueContactDetail' => ContactDetail::class,
                    'valueMeta' => Meta::class,
                    //TODO: Add missing types
                    /*
                    'valueDataRequirement' => DataRequirement::class,
                    'valueExpression' => Expression::class,
                    'valueParameterDefinition' => ParameterDefinition::class,
                    'valueRelatedArtifact' => RelatedArtifact::class,
                    'valueTriggerDefinition' => TriggerDefinition::class,
                    'valueUsageContext' => UsageContext::class,
                    'valueAvailability' => Availability::class,
                    'valueExtendedContactDetail' => ExtendedContactDetail::class,
                    'valueDosage' => Dosage::class,
                    */
                ],
                'cardinality' => Cardinality::ZeroOrOne,
            ],
        ]);
    }
}
