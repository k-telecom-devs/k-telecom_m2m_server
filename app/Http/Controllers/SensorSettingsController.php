<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\SensorSettings;
use App\Models\DeviceType;
use App\Models\Version;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SensorSettingsController extends Controller
{
    public function index(Request $request)
    {
        $sensor = Sensor::where('mac', $request->mac)
            ->get()->first();

        return SensorSettings::with('version')->where('sensor_id', $sensor['id'])
            ->get()->values();
    }

    public function edit(Request $request): JsonResponse
    {
        $this->validate($request, [
            'sensor_id' => 'required',
            'name' => 'required',
            'sleep' => 'required',
            'version_id' => 'required',
            'group_id' => 'required',
            'subgroup_id' => 'required',
            'min_trigger' => 'required',
            'max_trigger' => 'required',
        ]);

        try {
            $sensor_settings = SensorSettings::where(['sensor_id' => $request->sensor_id])->first();
            
            $version = Version::find($request->version_id);
            if(! $version){
                return response()->json(['message' => 'No version with this id']);
            }

            $version_device_type = DeviceType::find($version->device_type_id);
            $sensor = Sensor::find($request->sensor_id);
            $real_device_type = DeviceType::find($sensor->device_type_id);

            if($version->device_type_id == $sensor->device_type_id){
                $sensor_settings->version_id = $request->version_id;
            }
            else{
                return response()->json(['message' => 'Wrong sensor type. this version only for '.$version_device_type->device_type.". Your device is ". $real_device_type->device_type]);
            }

            $sensor_settings->name = $request->name;
            $sensor_settings->sleep = $request->sleep;
            $sensor_settings->version_id = $request->version_id;
            $sensor_settings->notification_start_at = $request->notification_start_at;
            $sensor_settings->notification_end_at = $request->notification_end_at;
            $sensor_settings->station_id = $request->station_id;
            $sensor_settings->group_id = $request->group_id;
            $sensor_settings->subgroup_id = $request->subgroup_id;
            $sensor_settings->min_trigger = $request->min_trigger;
            $sensor_settings->max_trigger = $request->max_trigger;

            

            if ($sensor_settings->save()) {
                return response()
                    ->json(['message' => 'Data created successfully, sensor updated']);
            }
            else {
                return response()
                    ->json(['message' => 'Something gone wrong']);
            }
        } catch (\Exception $e) {
            return response()
                ->json(['message' => $e->getMessage()]);
        }
    }
}
