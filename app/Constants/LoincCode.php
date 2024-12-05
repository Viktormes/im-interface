<?php

namespace App\Constants;

class LoincCode
{
    public const Vo2Max = '94122-9';

    public const SpO2 = '2708-6';

    public const SleepREM = '93829-0';

    public const SleepLight = '93830-8';

    public const SleepDeep = '93831-6';

    public const HeartRate = '8867-4';

    public const HeartRateVariability = '80404-7';

    public const Exercise = '55411-3';

    public const Elevation = '101687-2';

    public const Distance = '55430-3';

    public const Calories = '55424-6';

    public const BreathingRate = '9279-1';

    public const Steps = '55423-8';

    public const PatientReportedOutcomeMeasureScore = '89194-5';

    public static function supportedMeasurements(): array
    {
        return [
            static::Vo2Max,
            static::SpO2,
            static::SleepREM,
            static::SleepLight,
            static::SleepDeep,
            static::HeartRate,
            static::HeartRateVariability,
            static::Exercise,
            static::Elevation,
            static::Distance,
            static::Calories,
            static::BreathingRate,
            static::Steps,
        ];
    }
}
