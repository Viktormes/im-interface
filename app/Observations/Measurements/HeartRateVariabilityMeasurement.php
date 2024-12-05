<?php

namespace App\Observations\Measurements;

use App\Constants\LoincCode;
use App\Enums\MeasurementCategory;
use App\Enums\MeasurementChartType;

class HeartRateVariabilityMeasurement extends Measurement
{
    public function loincCodes(): array
    {
        return [LoincCode::HeartRateVariability];
    }

    public function label(): string
    {
        return 'Hjärtfrekvensvariabilitet';
    }

    public function category(): MeasurementCategory
    {
        return MeasurementCategory::Heart;
    }

    public function format(): string
    {
        return '{0:N0}';
    }

    public function cumulative(): bool
    {
        return false;
    }

    public function type(): MeasurementChartType
    {
        return MeasurementChartType::Line;
    }

    public function referenceValue(): array
    {
        return [
            'low' => 19,
            'high' => 75,
        ];
    }

    public function min(): int|float
    {
        return 20;
    }

    public function max(): int|float
    {
        return 75;
    }

    public function icon(): ?string
    {
        return 'heart-rate-variability';
    }
}
