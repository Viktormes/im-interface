<?php

namespace App\Observations\Measurements;

use App\Constants\LoincCode;
use App\Enums\MeasurementCategory;
use App\Enums\MeasurementChartType;

class Vo2MaxMeasurement extends Measurement
{
    public function loincCodes(): array
    {
        return [LoincCode::Vo2Max];
    }

    public function label(): string
    {
        return 'Vo2Max';
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
        return false;
    }

    public function type(): MeasurementChartType
    {
        return MeasurementChartType::Line;
    }

    public function referenceValue(): null
    {
        return null;
    }

    public function min(): int|float
    {
        return 10;
    }

    public function max(): int|float
    {
        return 50;
    }

    public function icon(): ?string
    {
        return 'vo2max';
    }
}
