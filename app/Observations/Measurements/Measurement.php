<?php

namespace App\Observations\Measurements;

use App\Enums\MeasurementCategory;
use App\Enums\MeasurementChartType;

abstract class Measurement
{
    abstract public function loincCodes(): array;

    abstract public function label(): string;

    abstract public function category(): MeasurementCategory;

    abstract public function format(): string;

    abstract public function cumulative(): bool;

    abstract public function type(): MeasurementChartType;

    abstract public function referenceValue(): mixed;

    public function min(): int|float
    {
        return 0;
    }

    abstract public function max(): int|float;

    public function icon(): ?string
    {
        return null;
    }
}
