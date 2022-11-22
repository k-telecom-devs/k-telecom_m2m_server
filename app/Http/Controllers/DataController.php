<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Sensor;
use App\Models\Station;
use App\Models\User;
use App\Models\SensorSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return Data::all()->where('user_id', $user['id'])->first();
    }

    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'mac' => 'required',
            'value' => 'required',
            'uptime' => 'required',
            'charge' => 'required',
        ]);

        $sensor = Sensor::where(['mac' => $request->mac])->first();
        $sensor_settings = SensorSettings::where(['sensor_id' => $sensor->id])->first();
        if(!$sensor){
            return response()->json(['message' => 'No sensor with this mac']);
        }
        $station = Station::find($sensor->station_id);
        $user = User::find($station->user_id);
        try {

            $data = new Data();
            $data->value = $request->value;
            if($request->value < $sensor_settings->min_trigger || $request->value > $sensor_settings->max_trigger){
                MailController::sendMail($user->email, 'Проверьте датчик с именем'.$sensor_settings->name.'. Он отправил '.$request->value,'Уведомление сенсора!');
            }
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
