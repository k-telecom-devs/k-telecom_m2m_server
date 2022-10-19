<?php

namespace App\Http\Controllers;

use App\Models\StationSettings;
use App\Models\Station;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StationSettingsController extends Controller
{
    public function index(Request $request)
    {
        return StationSettings::where('station_id', $request->station_id)->get()->values();
    }

    public function edit(Request $request): JsonResponse
    {
        $this->validate($request, [
            'station_id' => 'required',
            'name' => 'required',
        ]);

        try {
            $station_settings = StationSettings::where(['station_id' => $request->station_id])->first();
            $station_settings->name = $request->name;

            if ($station_settings->save()) {
                return response()->json(['message' => 'Data created successfully, sensor updated']);
            }
            else {
                return response()->json(['message' => 'Something gone wrong']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
