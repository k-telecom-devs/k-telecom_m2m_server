<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\StationSettings;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return Station::all()->where('user_id', $user['id'])->first();
    }

    public function create(Request $request)
    {
        try {
            $user = auth()->user();

            $station = new Station();
            $station->user_id = $user['id'];

            if ($station->save()){            
                $station_settings = new StationSettings();
                $station_settings-> name = $request->name;
                $station_settings -> station_id = $station -> id;
            }
        
            if ($station->save() && $station_settings->save()) {
                return response()->json(['message' => 'Station created successfully.']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}