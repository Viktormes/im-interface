<?php

namespace App\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

trait HasPeriodWithAccuracy
{
    /**
     * @return array{Carbon, Carbon, 'day'|'week'|'month', int}
     */
    protected function resolvePeriodAndAccuracy(Request $request): array
    {
        $request->validate([
            'accuracy' => ['sometimes', 'nullable', 'string', 'in:day,week,month'],
            'from' => ['sometimes', 'nullable', 'date'],
            'to' => ['sometimes', 'nullable', 'date', 'after_or_equal:from'],
        ]);

        $accuracy = $request->get('accuracy') ?? 'day';
        $totalDataPoints = 0;

        if ($request->get('from') && $request->get('to')) {
            $from = Carbon::parse($request->get('from'))?->startOfDay();
            $to = Carbon::parse($request->get('to'))?->endOfDay();

            switch ($accuracy) {
                case 'week':
                    $to = $to->endOfWeek();
                    $from = $from->startOfWeek();
                    $totalDataPoints = (int) ceil($from->diffInWeeks($to));
                    break;
                case 'month':
                    $to = $to->endOfMonth();
                    $from = $from->startOfMonth();
                    $totalDataPoints = (int) ceil($from->diffInMonths($to));
                    break;
                default:
                    $accuracy = 'day';
                    $from = $from->startOfDay();
                    $to = $to->endOfDay();
                    $totalDataPoints = (int) ceil($from->diffInDays($to));
                    break;
            }
        } else {
            switch ($accuracy) {
                case 'week':
                    $to = now()->endOfWeek();
                    $from = (new Carbon($to))->startOfWeek()->subWeeks(6);
                    $totalDataPoints = (int) ceil($from->diffInWeeks($to));
                    break;
                case 'month':
                    $to = now()->endOfMonth();
                    $from = (new Carbon($to))->startOfMonth()->subMonths(6);
                    $totalDataPoints = (int) ceil($from->diffInMonths($to));
                    break;
                default:
                    $accuracy = 'day';
                    $from = now()->startOfDay()->subDays(6);
                    $to = now()->endOfDay();
                    $totalDataPoints = (int) ceil($from->diffInDays($to));
                    break;
            }
        }

        return [$from, $to, $accuracy, $totalDataPoints];
    }
}
