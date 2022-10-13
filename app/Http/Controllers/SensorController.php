<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\Station;
use App\Models\SensorSettings;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function UserGetData()
    {
        $user = auth()->user();

        $station = Station::all()->where('user_id', $user['id'])->first();

        return Sensor::with('datas')->where('station_id', $station['id'])->get()->values();
    }

    public function create(Request $request)
    {
        try {
            $sensor = new Sensor();
            $sensor->mac = $request->mac;
            $sensor->station_id = $request->station_id;
            if ($sensor->save()){
                $sensor_settings = new SensorSettings();
                $sensor_settings->sensor_id = $sensor->id;
        }
            if ($sensor->save() && $sensor_settings->save()) {
                return response()->json(['message' => 'Sensor created successfully.']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}