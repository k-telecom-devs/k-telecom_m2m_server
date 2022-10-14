<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SensorsSettingsController extends Controller
{
    public function index(Request $request)
    {
        $sensor = Sensor::where('mac', $request->mac)->get()->first();

        return SensorSettings::where('sensor_id', $sensor['id'])->get()->values();
    }

    public function edit(Request $request): JsonResponse
    {
        try {
            $sensors_settings = SensorSettings::where(['sensor_id' => $request->sensor_id])->first();

            $sensors_settings->name = $request->name;
            $sensors_settings->sleep = $request->sleep;

            if ($sensors_settings->save()) {
                return response()->json(['message' => 'Data created successfully, sensor updated']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
