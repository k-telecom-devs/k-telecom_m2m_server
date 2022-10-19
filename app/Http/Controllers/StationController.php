<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\StationSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return Station::with('settings')->where('user_id', $user['id'])->get()->values();
    }

    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        try {
            $user = auth()->user();

            $station = new Station();
            $station_settings = new StationSettings();

            $station->user_id = $user['id'];

            if ($station->save()) {
                $station_settings->name = $request->name;
                $station_settings->station_id = $station->id;
            }

            if ($station_settings->save()) {
                return response()->json(['message' => 'Station created successfully.']);
            } else {
                $station->delete();
                return response()->json(['message' => 'Station created but station settings cant be init.']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
