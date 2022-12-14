<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\Station;
use App\Models\Version;
use App\Models\Group;
use App\Models\Subgroup;
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
            ->with('settings')
            ->whereIn('station_id', $stations)
            ->get()->values();
    }

    public function del(Request $request): JsonResponse
    {
        $user = auth()->user();

        $sensor = Sensor::where('mac', $request->mac)->first();

        if ($sensor) {
            $station = Station::where('id', $sensor->station_id)->first();

            if ($user['id'] == $station->user_id) {
                $sensor_settings = SensorSettings::where('sensor_id', $sensor->id)->first();
                $sensor_settings->delete();
                $sensor->delete();
                return response()->json(['message' => 'Delete successfully']);
            } else {
                return response()->json(['message' => "Sensor don't belongs to this user"]);
            }
        } else {
            return response()->json(['message' => "Can't find mac"]);
        }
    }


    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'mac' => 'required',
            'station_id' => 'required',
            'name' => 'required',
            'version_id' => 'required',
            'device_type_id' => 'required',
            'notification_start_at' => 'required',
            'notification_end_at' => 'required',
            'sleep' => 'required',
            'group_id' => 'required',
            'subgroup_id' => 'required',
            'min_trigger' => 'required',
            'max_trigger' => 'required',
        ]);

        $user = auth()->user();
        $sensor = new Sensor();
        $sensor_settings = new SensorSettings();

        $station = Station::find($request->station_id);
        if (!$station)
            return response()->json(['message' => 'No station with this id']);

        $version = Version::find($request->version_id);
        if (!$version)
            return response()->json(['message' => 'No version with this id']);

        $version_device_type = DeviceType::find($version->device_type_id);

        $real_device_type = DeviceType::find($request->device_type_id);
        if (!$real_device_type)
            return response()->json(['message' => 'No device type with this id']);

        $created_sensor = Sensor::where('mac', $request->mac)->get();

        if (!empty($created_sensor[0]))
            return response()->json(['message' => 'This sensor alredy exists ' . $created_sensor]);

        $created_group = Group::find($request->group_id);
        if (!$created_group)
            return response()->json(['message' => 'No group with this id']);

        $created_subgroup = Subgroup::find($request->subgroup_id);
        if (!$created_subgroup)
            return response()->json(['message' => 'No subgroup with this id']);

        if ($created_subgroup->group_id != $request->group_id)
            return response()->json(['message' => 'Subgroup does not belong to the group']);

        if ($station->user_id != $user['id'])
            return response()->json(['message' => 'Station does not belong to this user']);

        try {

            $sensor_settings->version_id = $request->version_id;
            /*
            if ($version->device_type_id == $request->device_type_id)
                $sensor_settings->version_id = $request->version_id;
            else
                return response()->json(['message' => 'Wrong sensor type. this version only for ' . $version_device_type->device_type . ". Your device is " . $real_device_type->device_type]);
            */

            $sensor->station_id = $request->station_id;
            $sensor->device_type_id = $request->device_type_id;
            $sensor->mac = $request->mac;

            $sensor_settings->name = $request->name;
            $sensor_settings->sleep = $request->sleep;
            $sensor_settings->notification_start_at = $request->notification_start_at;
            $sensor_settings->notification_end_at = $request->notification_end_at;
            $sensor_settings->version_id = $request->version_id;
            $sensor_settings->station_id = $request->station_id;
            $sensor_settings->group_id = $request->group_id;
            $sensor_settings->subgroup_id = $request->subgroup_id;
            $sensor_settings->min_trigger = $request->min_trigger;
            $sensor_settings->max_trigger = $request->max_trigger;

            if ($sensor->save()) {
                $sensor_settings->sensor_id = $sensor->id;
                if ($sensor_settings->save())
                    return response()->json(['message' => $sensor]);
                else
                    return response()->json(['message' => 'settings dont save =(']);
            } else {
                $sensor->delete();
                $sensor_settings->delete();
                return response()->json(['message' => 'Something gone wrong.']);
            }
        } catch (\Exception $e) {
            $sensor->delete();
            $sensor_settings->delete();
            return response()->json(['exception' => $e->getMessage()]);
        }
    }

    public function generateID(Request $request) : JsonResponse
    {
	$this->validate($request, [
	    'mac' => 'required',
	]);

	$id = md5($request->mac . time());

	return response()->json(['id' => $id]);
    }
}
