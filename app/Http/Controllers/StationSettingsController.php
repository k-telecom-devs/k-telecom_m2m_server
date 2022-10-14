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
        return StationSettings::where('station_id', $request->id)->get()->values();
    }

    public function edit(Request $request): JsonResponse
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
