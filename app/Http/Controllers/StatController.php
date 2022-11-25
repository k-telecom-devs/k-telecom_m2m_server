<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\DailyStat;
use App\Models\MonthlyStat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatController extends Controller
{
    public function dailyStat(Request $request)
    {
        $sensor = Sensor::where('mac', $request->mac)->first();
        return DailyStat::all()->where('sensor_id', $sensor['id']);
    }

    public function monthlyStat(Request $request)
    {
        $sensor = Sensor::where('mac', $request->mac)->first();
        return MonthlyStat::all()->where('sensor_id', $sensor['id']);
    }
}
