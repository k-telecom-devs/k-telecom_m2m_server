<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\StationSettings;
use App\Models\Station;
use Illuminate\Http\Request;

class StationSettingsController extends Controller
{
    public function index()
    {
        $station = Station::where('mac', $request->mac)->get()->first();
        
        return SensorsSettings::where('sensor_id', $sensor['id'])->get()->values();
    }

    public function edit(Request $request)
    {
        try {
            $station_settings = StationSettings::where(['station_id' => $request->station_id])->first();
            $station_settings->name = $request->name;
            

            if ($station_settings->save()) {
                return response()->json(['message' => 'Data created successfully, sensor updated']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}