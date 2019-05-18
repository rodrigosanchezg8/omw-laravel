<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests\AvgSalesRegression;
use App\Services\StatisticService;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
    public function __construct(StatisticService $service)
    {
        $this->service = $service;
    }

    public function client_linear_regression(User $user, AvgSalesRegression $request)
    {
        try {

            $regressionInfo = $this->service->monthlyAvgSalesRegression($user, $request->all());

            return response()->json([
                'header' => 'Estadistica encontrada',
                'status' => 'success',
                'regression_info' => $regressionInfo,
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(). ' '. $e->getFile(). ' '. $e->getLine(),
            ]);

        }
    }
}