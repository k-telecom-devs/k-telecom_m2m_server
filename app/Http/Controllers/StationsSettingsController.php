<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\StationsSettings;
use App\Models\Station;
use Illuminate\Http\Request;

class StationsSettingsController extends Controller
{
    public function index(Request $request)
    {
        $station = Station::where($request->stations_id)->get()->first();
        
        return StationsSettings::where('station_id', $station['id'])->get()->values();
    }

    public function edit(Request $request)
    {
        try {
            $station_settings = StationsSettings::where(['station_id' => $request->station_id])->first();
            $station_settings->name = $request->name;
            

            if ($station_settings->save()) {
                return response()->json(['message' => 'Data created successfully, sensor updated']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}