<?php

namespace App\Observations\Measurements;

use App\Constants\LoincCode;
use App\Enums\MeasurementCategory;
use App\Enums\MeasurementChartType;

class CaloriesMeasurement extends Measurement
{
    public function loincCodes(): array
    {
        return [LoincCode::Calories];
    }

    public function label(): string
    {
        return 'Kalorier';
    }

    public function category(): MeasurementCategory
    {
        return MeasurementCategory::Other;
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

    public function referenceValue(): null
    {
        return null;
    }

    public function max(): int|float
    {
        return 2500;
    }

    public function icon(): ?string
    {
        return 'calories';
    }
}
