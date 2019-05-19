<?php

namespace App\Services;

use App\Delivery;
use App\User;
use Phpml\Math\Statistic\Mean;
use Phpml\Regression\LeastSquares;

class StatisticService
{
    public function monthlyAvgSalesRegression(User $user, $predictionData)
    {
        $hasSalesSinceAYear = $this->clientHasSalesSinceAYear(
            $user,
            isset($predictionData['statistics_for']) ? $predictionData['statistics_for'] : null
        );

        $hasSalesSinceHalfYear = $this->clientHasSalesSinceHalfYear(
            $user,
            isset($predictionData['statistics_for']) ? $predictionData['statistics_for'] : null
        );

        if (!$hasSalesSinceAYear && !$hasSalesSinceHalfYear) {
            throw new \Exception("El cliente debe tener al menos 6 meses de ventas para poder hacer una prediccion", 1);
        }

        $regression = new LeastSquares();

        $deliveriesCount = [];
        $months = [];
        $monthsInDate = [];
        $samples = [];
        $predictionFunction = [];
        $dateComparator = null;
        $iterateUntil = 0;

        if ($hasSalesSinceAYear) {

            $months = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
            $dateComparator = now()->subMonths(11);
            $iterateUntil = 12;

        } else {

            $months = [0, 1, 2, 3, 4, 5];
            $dateComparator = now()->subMonths(6);
            $iterateUntil = 6;
        }

        for ($i = 0; $i < $iterateUntil; $i++) {

            $dateToCheck = $dateComparator->addMonths($i);

            $monthsInDate[] = $dateToCheck->year. '-'. $dateToCheck->month;

            $query = Delivery::whereDate('arrival_date', '>=', $dateToCheck->startOfMonth())
                             ->whereDate('arrival_date', '<=', $dateToCheck->endOfMonth())
                             ->where('sender_id', $user->id)
                             ->whereHas('deliveryStatus', function ($query) use($user) {
                                 $query->where('status', config('constants.delivery_statuses.finished'));
                             });

            if (isset($predictionData['statistics_for'])) {
                $query->where('company_is_sending', $predictionData['statistics_for']);
            }

            $deliveriesCount[$i] = $query->count();

            $samples[] = [
                $i,
            ];

            $dateComparator = now()->subMonths($iterateUntil - 1);

        }

        $regression->train($samples, $deliveriesCount);

        for ($i = 0; $i < $iterateUntil; $i++) {

            $predictionFunction[] = $regression->predict([$i]);

        }

        $predictionValue = $regression->predict([
            ($iterateUntil - 1) + $predictionData['month_offset'],
        ]);

        return [
            'months_axis' => $monthsInDate, //X1
            'deliveries_monthly_count' => $deliveriesCount, //Y
            'correlation_rate_x1' => $this->pearson($months, $deliveriesCount),
            'prediction_function' => $predictionFunction,
            'prediction_month' => now()->addMonths($predictionData['month_offset'])->year. '-'. now()->addMonths($predictionData['month_offset'])->month,
            'prediction_value' => $predictionValue > 0 ? $predictionValue : 0,
        ];
    }

    private function clientHasSalesSinceAYear(User $user, $statistics_for = null)
    {
        $yearAgo = now()->subMonths(11);
        $halfYearAgo = now()->subMonths(6);

        $query = Delivery::whereDate('arrival_date', '>=', $yearAgo->startOfMonth())
                         ->whereDate('arrival_date', '<=', $halfYearAgo->endOfMonth())
                         ->where('sender_id', $user->id)
                         ->whereHas('deliveryStatus', function ($query) use($user) {
                             $query->where('status', config('constants.delivery_statuses.finished'));
                         });

        if ($statistics_for != null) {
            $query->where('company_is_sending', $statistics_for);
        }

        return $query->count() > 0;
    }

    private function clientHasSalesSinceHalfYear(User $user, $statistics_for = null)
    {
        $halfYearAgo = now()->subMonths(6);
        $now = now();

        $query = Delivery::whereDate('arrival_date', '>=', $halfYearAgo->startOfMonth())
                         ->whereDate('arrival_date', '<=', $now->endOfMonth())
                         ->where('sender_id', $user->id)
                         ->whereHas('deliveryStatus', function ($query) use($user) {
                             $query->where('status', config('constants.delivery_statuses.finished'));
                         });

        if ($statistics_for != null) {
            $query->where('company_is_sending', $statistics_for);
        }

        return $query->count() > 0;
    }

    private function pearson(array $x, array $y): float
    {
        if (count($x) !== count($y)) {
            throw new InvalidArgumentException('Size of given arrays does not match');
        }

        $count = count($x);
        $meanX = Mean::arithmetic($x);
        $meanY = Mean::arithmetic($y);

        $axb = 0;
        $a2 = 0;
        $b2 = 0;

        for ($i = 0; $i < $count; ++$i) {
            $a = $x[$i] - $meanX;
            $b = $y[$i] - $meanY;
            $axb += ($a * $b);
            $a2 += $a ** 2;
            $b2 += $b ** 2;
        }

        return (($a2 * $b2)) != 0
                             ? $axb / ($a2 * $b2) ** .5
                             : 0;
    }
}
