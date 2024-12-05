<?php

namespace App\Observations\Measurements;

use App\Constants\LoincCode;
use App\Enums\MeasurementCategory;
use App\Enums\MeasurementChartType;

class HeartRateMeasurement extends Measurement
{
    public function loincCodes(): array
    {
        return [LoincCode::HeartRate];
    }

    public function label(): string
    {
        return 'Puls';
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
        return MeasurementChartType::HighLow;
    }

    public function referenceValue(): array
    {
        return [
            'low' => 60,
            'high' => 100,
        ];
    }

    public function min(): int|float
    {
        return 40;
    }

    public function max(): int|float
    {
        return 150;
    }

    public function icon(): ?string
    {
        return 'heart-rate';
    }
}
