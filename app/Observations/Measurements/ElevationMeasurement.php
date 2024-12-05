<?php

namespace App\Observations\Measurements;

use App\Constants\LoincCode;
use App\Enums\MeasurementCategory;
use App\Enums\MeasurementChartType;

class ElevationMeasurement extends Measurement
{
    public function loincCodes(): array
    {
        return [LoincCode::Elevation];
    }

    public function label(): string
    {
        return 'Höjdskillnad';
    }

    public function category(): MeasurementCategory
    {
        return MeasurementCategory::Fitness;
    }

    public function format(): string
    {
        return '{0:N0} m';
    }

    public function cumulative(): bool
    {
        return true;
    }

    public function type(): MeasurementChartType
    {
        return MeasurementChartType::Bar;
    }

    public function referenceValue(): null
    {
        return null;
    }

    public function min(): int|float
    {
        return 0;
    }

    public function max(): int|float
    {
        return 30;
    }

    public function icon(): ?string
    {
        return 'elevation';
    }
}
