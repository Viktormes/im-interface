<?php

namespace App\Enums;

enum MeasurementChartType: string
{
    case HighLow = 'high-low';
    case Bar = 'bar';
    case Line = 'line';
}
