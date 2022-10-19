<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\Station;
use App\Models\SensorSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stations = Station::where('user_id', $user['id'])->pluck('id')->all();

        return Sensor::with('data')
            ->whereIn('station_id', $stations)
            ->get()->values();
    }

    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'mac' => 'required',
            'station_id' => 'required',
            'name' => 'required',
            'version_id' => 'required',
        ]);

        try
        {
            $sensor = new Sensor();

            $sensor->mac = $request->mac;
            $sensor->station_id = $request->station_id;

            if ($sensor->save())
            {
                $sensor_settings = new SensorSettings();
                $sensor_settings->sensor_id = $sensor->id;
                $sensor_settings->name = $request->name;
                $sensor_settings->version_id = $request->version_id;
            }

            if ($sensor_settings->save())
            {
                return response()->json(['message' => 'Sensor created successfully.']);
            }
            else
            {
                $sensor->delete();
                return response()->json(['message' => 'Sensor created but station settings cant be init.']);
            }
        }
        catch (\Exception $e)
        {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
