<?php

namespace App\Observations\Measurements;

use App\Constants\LoincCode;
use App\Enums\MeasurementCategory;
use App\Enums\MeasurementChartType;

class DistanceMeasurement extends Measurement
{
    public function loincCodes(): array
    {
        return [LoincCode::Distance];
    }

    public function label(): string
    {
        return 'Kilometer';
    }

    public function category(): MeasurementCategory
    {
        return MeasurementCategory::Fitness;
    }

    public function format(): string
    {
        return '{0:N1} km';
    }

    public function cumulative(): bool
    {
        return true;
    }

    public function type(): MeasurementChartType
    {
        return MeasurementChartType::Bar;
    }

    public function referenceValue(): float
    {
        return 6.5;
    }

    public function max(): int|float
    {
        return 8;
    }

    public function icon(): ?string
    {
        return 'distance';
    }
}
