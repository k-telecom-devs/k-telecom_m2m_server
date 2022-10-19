<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Sensor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return Data::all()->where('user_id', $user['id']);
    }

    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'mac' => 'required',
            'value' => 'required',
            'uptime' => 'required',
            'charge' => 'required',
        ]);

        try {

            $sensor = Sensor::where(['mac' => $request->mac])->first();

            $data = new Data();
            $data->value = $request->value;

            $data->sensor_id = $sensor->id;
            $sensor->uptime = $request->uptime;
            $sensor->charge = $request->charge;

            if ($data->save() && $sensor->save()) {
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
