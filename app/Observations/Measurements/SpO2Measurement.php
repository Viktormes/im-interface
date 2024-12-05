<?php

namespace App\Observations\Measurements;

use App\Constants\LoincCode;
use App\Enums\MeasurementCategory;
use App\Enums\MeasurementChartType;

class SpO2Measurement extends Measurement
{
    public function loincCodes(): array
    {
        return [LoincCode::SpO2];
    }

    public function label(): string
    {
        return 'Syremättad';
    }

    public function category(): MeasurementCategory
    {
        return MeasurementCategory::Lungs;
    }

    public function format(): string
    {
        return '{0:P0}';
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
            'low' => 0.9,
            'high' => 1,
        ];
    }

    public function min(): int|float
    {
        return 0.9;
    }

    public function max(): int|float
    {
        return 1;
    }

    public function icon(): ?string
    {
        return 'spo2';
    }
}
