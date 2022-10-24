<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\Station;
use App\Models\Version;
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
            'device_type_id' => 'required',
        ]);

        try
        {
            $sensor = new Sensor();            
            $sensor_settings = new SensorSettings();
            $version = Version::where('id', $request->version_id);
            
            if(! $version){
                return response()->json(['message' => 'No version with this id']);
            }

           /*if($version->device_type_id != $request->sensor_type){
                $sensor_settings->version_id = $request->version_id;
            }
            else{
                return response()->json(['message' => 'Wrong sensor type. this sensor only for '.$version->sensor_type]);
            }*/
            
            $sensor->mac = $request->mac;
            $sensor->station_id = $request->station_id;
            $sensor->device_type_id = $request->device_type_id;

            if ($sensor->save()){ 
                $sensor_settings->name = $request->name;
                $sensor_settings->sleep = $request->sleep;
                $sensor_settings->notification_start_at = $request->notification_start_at;
                $sensor_settings->notification_end_at = $request->notification_end_at;
                $sensor_settings->version_id = $request->version_id;
                $sensor_settings->sensor_id = $sensor->id;
                $sensor_settings->station_id = $request->station_id;

        }
        else{
            return response()->json(['message' => 'Something gone wrong.']);
        }
            if ($sensor_settings->save() && $sensor->save())
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
