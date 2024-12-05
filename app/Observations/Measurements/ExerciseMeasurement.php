<?php

namespace App\Observations\Measurements;

use App\Constants\LoincCode;
use App\Enums\MeasurementCategory;
use App\Enums\MeasurementChartType;

class ExerciseMeasurement extends Measurement
{
    public function loincCodes(): array
    {
        return [LoincCode::Exercise];
    }

    public function label(): string
    {
        return 'Aktiva minuter';
    }

    public function category(): MeasurementCategory
    {
        return MeasurementCategory::Fitness;
    }

    public function format(): string
    {
        return '{0:N0} min';
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
        return 30;
    }

    public function max(): int|float
    {
        return 60;
    }

    public function icon(): ?string
    {
        return 'exercise';
    }
}
