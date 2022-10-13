<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorSettings;
use Illuminate\Http\Request;

class SensorSettingsController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Here will be smt']);
    }

    public function edit(Request $request)
    {
        try {
            $sensor_settings = SensorSettings::where(['sensor_id' => $request->sensor_id])->first();

            $sensor_settings->name = $request->name;
            $sensor_settings->charge = $request->charge;
            $sensor_settings->sleep = $request->sleep;
            
            if ($sensor_settings->save()) {
                return response()->json(['message' => 'Data created successfully, sensor updated']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}