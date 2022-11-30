<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\Group;
use App\Models\Subgroup;
use App\Models\SensorSettings;
use App\Models\DeviceType;
use App\Models\Version;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Station;

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
            'notification_start_at' => 'required',
            'notification_end_at' => 'required',
            'station_id' => 'required',
            'group_id' => 'required',
            'subgroup_id' => 'required',
            'min_trigger' => 'required',
            'max_trigger' => 'required',

        ]);

        try {
            $sensor = Sensor::find($request->sensor_id);
            if (!$sensor)
                return response()->json(['message' => 'No sensor with this id']);

            $station = Station::find($request->station_id);
            if (!$station)
                return response()->json(['message' => 'No station with this id']);

            $sensor_settings = SensorSettings::where(['sensor_id' => $request->sensor_id])->first();
            if (!$sensor_settings)
                return response()->json(['message' => 'No settings for sensor with this id']);

            $version = Version::find($request->version_id);
            if (!$version)
                return response()->json(['message' => 'No version with this id']);

            $version_device_type = DeviceType::find($version->device_type_id);
            $real_device_type = DeviceType::find($sensor->device_type_id);
            if (!$real_device_type)
                return response()->json(['message' => 'No device type with this id']);

            $created_group = Group::find($request->group_id);
            if (!$created_group)
                return response()->json(['message' => 'No group with this id']);

            $created_subgroup = Subgroup::find($request->subgroup_id);
            if (!$created_subgroup)
                return response()->json(['message' => 'No subgroup with this id']);

            if ($created_subgroup->group_id != $request->group_id)
                return response()->json(['message' => 'Subgroup does not belong to the group']);

            if ($version->device_type_id == $sensor->device_type_id)
                $sensor_settings->version_id = $request->version_id;
            else
                return response()->json(['message' => 'Wrong sensor type. this version only for ' . $version_device_type->device_type . ". Your device is " . $real_device_type->device_type]);

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

            $sensor->station_id = $request->station_id;

            if ($sensor_settings->save() && $sensor->save())
                return response()->json(['message' => 'Data created successfully, sensor updated']);
            else
                return response()->json(['message' => 'Something gone wrong']);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
