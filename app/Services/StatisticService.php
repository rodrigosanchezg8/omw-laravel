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

        $iterateUntil = $hasSalesSinceAYear ? 12 : 6;

        $query = Delivery::where('sender_id', $user->id);

        if (isset($predictionData['statistics_for'])) {
            $query->where('company_is_sending', $predictionData['statistics_for']);
        }

        return $this->toLinearRegressionData($query, $iterateUntil, $predictionData['month_offset']);

    }

    public function linearRegressionByCity($regressionParams)
    {
        $deliveriesByCity = Delivery::plainTextCity(
            $regressionParams['city'],
            isset($regressionParams['origin_type']) ? $regressionParams['origin_type'] : null,
            isset($regressionParams['statistics_for']) ? $regressionParams['statistics_for'] : null
        );

        if ($deliveriesByCity->count() <= 0) {

            throw new \Exception("No se encontraron entregas para la ciudad seleccionada con los parametros elegidos", 1);

        }

        if (isset($regressionParams['statistics_for'])) {
            $deliveriesByCity->where('company_is_sending', $regressionParams['statistics_for']);
        }

        return $this->toLinearRegressionData(
            $deliveriesByCity,
            $numberOfMounths = 12,
            $regressionParams['month_offset']
        );
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
        $halfYearAgo = now()->subMonths(5);
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

    private function toLinearRegressionData($deliveriesQuery, $numberOfMounths, $monthOffset)
    {
        $regression = new LeastSquares();

        $deliveriesCount = [];
        $months = [];
        $monthsInDate = [];
        $samples = [];
        $predictionFunction = [];
        $dateComparator = now()->subMonths($numberOfMounths - 1);

        for ($i = 0; $i < $numberOfMounths; $i++) {

            $dateToCheck = $dateComparator->addMonths($i);

            $monthsInDate[] = $dateToCheck->year. '-'. $dateToCheck->month;

            $months[] = $i;

            $query = clone $deliveriesQuery;

            $query = $query->whereDate('arrival_date', '>=', $dateToCheck->startOfMonth())
                                     ->whereDate('arrival_date', '<=', $dateToCheck->endOfMonth())
                                     ->whereHas('deliveryStatus', function ($query) {
                                         $query->where('status', config('constants.delivery_statuses.finished'));
                                     });

            $deliveriesCount[$i] = $query->count();

            $samples[] = [
                $i,
            ];

            $dateComparator = now()->subMonths($numberOfMounths - 1);

        }

        $regression->train($samples, $deliveriesCount);

        for ($i = 0; $i < $numberOfMounths; $i++) {

            $predictionFunction[] = $regression->predict([$i]);

        }

        $predictionValue = $regression->predict([
            ($numberOfMounths - 1) + $monthOffset,
        ]);

        return [
            'months_axis' => $monthsInDate, //X1
            'deliveries_monthly_count' => $deliveriesCount, //Y
            'correlation_rate_x1' => $this->pearson($months, $deliveriesCount),
            'prediction_function' => $predictionFunction,
            'prediction_month' => now()->addMonths($monthOffset)->year. '-'. now()->addMonths($monthOffset)->month,
            'prediction_value' => $predictionValue > 0 ? $predictionValue : 0,
        ];
    }

    private function pearson(array $x, array $y): float
    {
        if (count($x) !== count($y)) {
            throw new \Exception('Size of given arrays does not match');
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
