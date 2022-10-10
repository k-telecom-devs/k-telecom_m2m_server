<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function index()
    {
        return Sensor::all();
    }

    public function store(Request $request)
    {
        try {

            $sensor = new Sensor();
            $sensor->mac = $request->mac;
            $sensor->station_id = $request->station_id;

            if ($sensor->save()) {
                return response()->json(['message' => 'Sensor created successfully.']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}