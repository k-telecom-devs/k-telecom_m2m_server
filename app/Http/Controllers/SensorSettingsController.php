<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SensorSettingsController extends Controller
{
    public function index(Request $request)
    {
        $sensor = Sensor::where('mac', $request->mac)
            ->get()->first();

        return SensorSettings::with('version')->where('sensor_id', $sensor['id'])
            ->get()->values();
    }

    public function edit(Request $request): JsonResponse
    {
        $this->validate($request, [
            'sensor_id' => 'required',
            'name' => 'required',
            'sleep' => 'required',
            'version_id' => 'required',
        ]);

        try {
            $sensors_settings = SensorSettings::where(['sensor_id' => $request->sensor_id])->first();

            $sensors_settings->name = $request->name;
            $sensors_settings->sleep = $request->sleep;
            $sensors_settings->version_id = $request->version_id;

            if ($sensors_settings->save()) {
                return response()
                    ->json(['message' => 'Data created successfully, sensor updated']);
            }
            else {
                return response()
                    ->json(['message' => 'Something gone wrong']);
            }
        } catch (\Exception $e) {
            return response()
                ->json(['message' => $e->getMessage()]);
        }
    }
}
