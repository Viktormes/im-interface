<?php

namespace App\Services;

use App\Observations\Measurements\BreathingRateMeasurement;
use App\Observations\Measurements\CaloriesMeasurement;
use App\Observations\Measurements\DistanceMeasurement;
use App\Observations\Measurements\ElevationMeasurement;
use App\Observations\Measurements\ExerciseMeasurement;
use App\Observations\Measurements\HeartRateMeasurement;
use App\Observations\Measurements\HeartRateVariabilityMeasurement;
use App\Observations\Measurements\Measurement;
use App\Observations\Measurements\SleepMeasurement;
use App\Observations\Measurements\SpO2Measurement;
use App\Observations\Measurements\StepsMeasurement;
use App\Observations\Measurements\Vo2MaxMeasurement;
use Illuminate\Support\Carbon;

class MeasurementsService
{
    private static array $allMeasurements = [
        HeartRateMeasurement::class,
        StepsMeasurement::class,
        Vo2MaxMeasurement::class,
        SpO2Measurement::class,
        SleepMeasurement::class,
        HeartRateVariabilityMeasurement::class,
        ExerciseMeasurement::class,
        ElevationMeasurement::class,
        DistanceMeasurement::class,
        CaloriesMeasurement::class,
        BreathingRateMeasurement::class,
    ];

    public function average(array $observations, Carbon $from, int $totalDataPoints, string $accuracy): array
    {
        return $this->aggregate($observations, $from, $totalDataPoints, $accuracy, 'avg');
    }

    private function aggregate(array $observations, Carbon $from, int $totalDataPoints, string $accuracy, string $aggregate): array
    {
        return array_map(function ($observation) use ($totalDataPoints, $accuracy, $from) {
            $result = [...$observation];
            $data = array_fill(0, $totalDataPoints, []);

            foreach ($observation['data'] as $date => $values) {
                $bucket = match ($accuracy) {
                    'month' => (int) floor($from->diffInMonths(Carbon::parse($date)->startOfMonth())),
                    'week' => (int) floor($from->diffInWeeks(Carbon::parse($date)->startOfWeek())),
                    default => (int) floor($from->diffInDays(Carbon::parse($date)->startOfDay())),
                };

                if ($result['cumulative']) {
                    $data[$bucket][] = collect($values)->sum('value');
                } else {
                    collect($values)->each(function ($value, $key) use ($bucket, &$data) {
                        if (is_array($value['value'])) {
                            $data[$bucket][] = array_sum($value['value']) / count($value['value']);

                            return;
                        }

                        $data[$bucket][] = $value['value'];
                    });
                }
            }

            $result['data'] = array_map(function ($value) {
                return ! count($value) ? null : array_sum($value) / count($value);
            }, $data);

            return $result;
        }, $observations);
    }

    public function group(array $observations): array
    {
        $result = [];

        foreach (static::$allMeasurements as $measurementClass) {
            /** @var Measurement $measurement */
            $measurement = new $measurementClass;

            $transformed = [
                'label' => $measurement->label(),
                'identifier' => implode(',', $measurement->loincCodes()),
                'category' => $measurement->category(),
                'format' => $measurement->format(),
                'cumulative' => $measurement->cumulative(),
                'type' => $measurement->type(),
                'referenceValue' => $measurement->referenceValue(),
                'min' => $measurement->min(),
                'max' => $measurement->max(),
                'icon' => $measurement->icon(),
                'data' => [],
            ];

            foreach ($measurement->loincCodes() as $loincCode) {
                if (! array_key_exists($loincCode, $observations)) {
                    continue;
                }

                $relevantObservations = &$observations[$loincCode];

                foreach ($relevantObservations as $date => $o) {
                    $transformed['data'][$date] = $o;
                }

                unset($relevantObservations);
            }

            $result[] = $transformed;
        }

        return $result;
    }

    public function min(array $observations, Carbon $from, int $totalDataPoints, string $accuracy): array
    {
        return $this->aggregate($observations, $from, $totalDataPoints, $accuracy, 'min');
    }

    public function max(array $observations, Carbon $from, int $totalDataPoints, string $accuracy): array
    {
        return $this->aggregate($observations, $from, $totalDataPoints, $accuracy, 'max');
    }
}
