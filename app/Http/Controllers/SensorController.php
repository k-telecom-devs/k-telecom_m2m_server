<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\Station;
use App\Models\Version;
use App\Models\SensorSettings;
use App\Models\DeviceType;
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
            'group_id' => 'required',
            'subgroup_id' => 'required',
            'min_trigger' => 'required',
            'max_trigger' => 'required',
        ]);

        try
        {
            $sensor = new Sensor();
            $sensor_settings = new SensorSettings();
            $version = Version::find($request->version_id);

            if(! $version){
                return response()->json(['message' => 'No version with this id']);
            }

            $version_device_type = DeviceType::find($version->device_type_id);
            $real_device_type = DeviceType::find($request->device_type_id);


           if($version->device_type_id == $request->device_type_id){
                $sensor_settings->version_id = $request->version_id;
            }
            else{
                return response()->json(['message' => 'Wrong sensor type. this version only for '.$version_device_type->device_type.". Your device is ". $real_device_type->device_type]);
            }

            $created_sensor = Sensor::where('mac', $request->mac)->get();
            if(!empty($created_sensor)){
                return response()->json(['message' => 'This sensor alredy exists '.$created_sensor]);
            }

            $sensor->station_id = $request->station_id;
            $sensor->device_type_id = $request->device_type_id;
            $sensor->mac = $request->mac;


            if ($sensor->save()){
                $sensor_settings->name = $request->name;
                $sensor_settings->sleep = $request->sleep;
                $sensor_settings->notification_start_at = $request->notification_start_at;
                $sensor_settings->notification_end_at = $request->notification_end_at;
                $sensor_settings->version_id = $request->version_id;
                $sensor_settings->sensor_id = $sensor->id;
                $sensor_settings->station_id = $request->station_id;
                $sensor_settings->group_id = $request->group_id;
                $sensor_settings->subgroup_id = $request->subgroup_id;
                $sensor_settings->min_trigger = $request->min_trigger;
                $sensor_settings->max_trigger = $request->max_trigger;

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
            return response()->json(['exception' => $e->getMessage()]);
        }
    }
}
