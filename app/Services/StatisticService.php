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
        $months = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
        $monthsInDate = [];
        $samples = [];
        $yearAgo = now()->subMonths(11);

        for ($i = 0; $i < 12; $i++) {

            $dateToCheck = $yearAgo->addMonths($i);

            $monthsInDate[] = $dateToCheck->year. '-'. $dateToCheck->month;

            $deliveriesCount[$i] = Delivery::whereDate('updated_at', '>=', $dateToCheck->startOfMonth())
                                           ->whereDate('updated_at', '<=', $dateToCheck->endOfMonth())
                                           ->where('sender_id', $user->id)
                                           ->whereHas('deliveryStatus', function ($query) use($user) {
                                               $query->where('status', config('constants.delivery_statuses.finished'));
                                           })
                                           ->count();
            $samples[] = [
                $i,
            ];

            $yearAgo = now()->subMonths(11);
        }

        $regression->train($samples, $deliveriesCount);

        $predictionValue = $regression->predict([
            11 + $predictionData['month_offset'],
        ]);

        return [
            'months_axis' => $monthsInDate, //X1
            'deliveries_monthly_count' => $deliveriesCount, //Y
            'correlation_rate_x1' => Correlation::pearson($months, $deliveriesCount),
            'prediction_month' => now()->addMonths($predictionData['month_offset'])->year. '-'. now()->addMonths($predictionData['month_offset'])->month,
            'prediction_value' => $predictionValue,
        ];


    }
}
