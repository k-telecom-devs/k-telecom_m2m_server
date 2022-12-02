<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Sensor;
use App\Models\Station;
use App\Models\User;
use App\Models\DailyStat;
use App\Models\MonthlyStat;
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

        if (!$sensor)
            return response()->json(['message' => 'No sensor with this mac']);

        if (!$sensor_settings)
            return response()->json(['message' => 'Sesor dont have settings']);


        $station = Station::find($sensor->station_id);
        $user = User::find($station->user_id);

        if (!$station)
            return response()->json(['message' => 'Sesor dont have station']);

        if (!$user)
            return response()->json(['message' => 'Sesor dont have user']);

        $data = new Data();
        $data->sensor_id = $sensor->id;
        $sensor->uptime = $request->uptime;
        $sensor->charge = $request->charge;
        $data->value = $request->value;

        try {
            //отправляем письмо на почту, если данные превышают выставленную норму
            $sensorDataAlert = $request->value < $sensor_settings->min_trigger || $request->value > $sensor_settings->max_trigger;

            if ($sensorDataAlert && !$sensor_settings->alert) {

                $content = 'Проверьте датчик с именем '
                    . $sensor_settings->name;

                if ($request->value < $sensor_settings->min_trigger )
                    $content = $content
                        . '. Значение преодолело минимальный порог '
                        . $sensor_settings->min_trigger
                        . ' и составляет'
                        . $request->value;
                else
                    $content = $content
                        . '. Значение преодолело максимальный порог '
                        . $sensor_settings->max_trigger
                        . ' и составляет'
                        . $request->value;

                (new MailController)->sendMail($user->email, $content, 'Уведомление сенсора!');
                $sensor_settings->alert = true;
                $sensor_settings->save();
            }

            if (!$sensorDataAlert && $sensor_settings->alert) {
                $sensor_settings->alert = false;
                $sensor_settings->save();
            }

            //сохраняем все
            if ($data->save() && $sensor->save()) {
                $dailyStats = DailyStat::where('sensor_id', $sensor->id)->get()->last();

                if (!$dailyStats) {
                    $dailyStats = new DailyStat();
                    $dailyStats->sensor_id = $sensor->id;
                }

                $dateStats = strtotime($dailyStats->created_at);
                $dateData = strtotime($data->updated_at);
                if (date('y-m-d', $dateData) != date('y-m-d', $dateStats)) {
                    $dailyStats = new DailyStat();
                    $dailyStats->sensor_id = $sensor->id;
                }

                //аналогично для месячной отправки
                $monthlyStats = MonthlyStat::where('sensor_id', $sensor->id)->get()->last();
                if (!$monthlyStats || date('y-m', $dateData) != date('y-m', $dateData)) {
                    $monthlyStats = new MonthlyStat();
                    $monthlyStats->sensor_id = $sensor->id;
                }

                $dailyStats->measurements_number = $dailyStats->measurements_number + 1;
                $dailyStats->average = ($dailyStats->average * ($dailyStats->measurements_number - 1) + $request->value) / $dailyStats->measurements_number;

                $monthlyStats->measurements_number = $monthlyStats->measurements_number + 1;
                $monthlyStats->average = ($monthlyStats->average * ($monthlyStats->measurements_number - 1) + $request->value) / $monthlyStats->measurements_number;

                if ($dailyStats->save() && $monthlyStats->save())
                    return response()->json(['message' => 'Data created successfully, sensor updated']);
                else
                    return response()->json(['message' => 'Something gone wrong']);

            } else {
                return response()->json(['message' => 'Something gone wrong']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
