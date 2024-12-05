<?php

namespace App\Observations\Measurements;

use App\Constants\LoincCode;
use App\Enums\MeasurementCategory;
use App\Enums\MeasurementChartType;

class StepsMeasurement extends Measurement
{
    public function loincCodes(): array
    {
        return [LoincCode::Steps];
    }

    public function label(): string
    {
        return 'Steg';
    }

    public function category(): MeasurementCategory
    {
        return MeasurementCategory::Fitness;
    }

    public function format(): string
    {
        return '{0:N0}';
    }

    public function cumulative(): bool
    {
        return true;
    }

    public function type(): MeasurementChartType
    {
        return MeasurementChartType::Bar;
    }

    public function referenceValue(): int
    {
        return 10000;
    }

    public function max(): int|float
    {
        return 12000;
    }

    public function icon(): ?string
    {
        return 'steps';
    }
}
