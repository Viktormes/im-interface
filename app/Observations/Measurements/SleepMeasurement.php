<?php

namespace App\Observations\Measurements;

use App\Constants\LoincCode;
use App\Enums\MeasurementCategory;
use App\Enums\MeasurementChartType;

class SleepMeasurement extends Measurement
{
    public function loincCodes(): array
    {
        return [LoincCode::SleepREM, LoincCode::SleepLight, LoincCode::SleepDeep];
    }

    public function label(): string
    {
        return 'Sömn';
    }

    public function category(): MeasurementCategory
    {
        return MeasurementCategory::Sleep;
    }

    public function format(): string
    {
        return "{0:h'h'[' 'm'm']}";
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
        return 28800;
    }

    public function max(): int|float
    {
        return 36000;
    }

    public function icon(): ?string
    {
        return 'sleep';
    }
}
