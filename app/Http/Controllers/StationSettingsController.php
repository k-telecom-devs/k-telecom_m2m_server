<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\StationSettings;
use Illuminate\Http\Request;

class StationSettingsController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Here will be smt']);
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