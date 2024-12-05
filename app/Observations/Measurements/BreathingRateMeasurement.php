<?php

namespace App\Observations\Measurements;

use App\Constants\LoincCode;
use App\Enums\MeasurementCategory;
use App\Enums\MeasurementChartType;

class BreathingRateMeasurement extends Measurement
{
    public function loincCodes(): array
    {
        return [LoincCode::BreathingRate];
    }

    public function label(): string
    {
        return 'Andningsfrekvens';
    }

    public function category(): MeasurementCategory
    {
        return MeasurementCategory::Lungs;
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
            'low' => 12,
            'high' => 18,
        ];
    }

    public function min(): int|float
    {
        return 10;
    }

    public function max(): int|float
    {
        return 20;
    }

    public function icon(): ?string
    {
        return 'breathing-rate';
    }
}
