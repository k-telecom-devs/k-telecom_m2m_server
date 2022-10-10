<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Sensor;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index()
    {
        return Data::all();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'value' => 'required',
            'mac' => 'required',
            'uptime' => 'required'
        ]);
        try {

            $sensor = Sensor::where(['mac' => $request->mac])->first();

            $data = new Data();
            $data->value = $request->value;


            $data->sensor_id = $sensor->id;
            $sensor->uptime = $request->uptime;

            if ($data->save() && $sensor->save()) {
                return response()->json(['message' => 'Data created successfully, sensor updated']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
