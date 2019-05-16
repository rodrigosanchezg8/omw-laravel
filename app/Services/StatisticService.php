<?php

namespace App\Services;

use DB;
use App\Delivery;
use App\DeliveryProduct;
use App\User;
use Phpml\Math\Statistic\Correlation;
use Phpml\Math\Statistic\Mean;
use Phpml\Regression\LeastSquares;

class StatisticService
{
    public function monthlyAvgSalesRegression(User $user, $predictionData)
    {
        $regression = new LeastSquares();

        $deliveriesCount = [];
        $salesAverages = [];
        $months = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
        $monthsInDate = [];
        $samples = [];
        $halfYearAgo = now()->subMonths(11);

        for ($i = 0; $i < 12; $i++) {

            $dateToCheck = $halfYearAgo->addMonths($i);

            $monthsInDate[] = $dateToCheck->toDateString();

            $deliveriesCount[$i] = Delivery::whereDate('updated_at', '>=', $dateToCheck->startOfMonth())
                                                                     ->whereDate('updated_at', '<=', $dateToCheck->endOfMonth())
                                                                     ->where('sender_id', $user->id)
                                                                     ->count();

            $salesPerMonth = DB::table('delivery_products')
                               ->join('deliveries', 'delivery_products.delivery_id', 'deliveries.id')
                               ->select(DB::raw('amount * cost as total_cost'))
                               ->whereDate('deliveries.updated_at', '>=', $dateToCheck->startOfMonth())
                               ->whereDate('deliveries.updated_at', '<=', $dateToCheck->endOfMonth())
                               ->where('deliveries.sender_id', $user->id)
                               ->pluck('total_cost')
                               ->toArray();

            $salesPerMonthMean = Mean::median($salesPerMonth);

            $salesAverages[$i] = floatval($salesPerMonthMean);

            $samples[] = [
                $i,
                $salesAverages[$i],
            ];

            $halfYearAgo = now()->subMonths(11);
        }

        $regression->train($samples, $deliveriesCount);

        $predictionValue = $regression->predict([11 + $predictionData['month_offset'], $predictionData['sales_avg']]);
        dd($predictionValue);

        return [
            'months_axis' => $monthsInDate, //X1
            'sales_average_axis' => $salesAverages, //X2
            'deliveries_monthly_count' => $deliveriesCount, //Y
            'correlation_rate_x1' => Correlation::pearson($months, $deliveriesCount),
            'correlation_rate_x2' => Correlation::pearson($salesAverages, $deliveriesCount),
        ];


    }
}
