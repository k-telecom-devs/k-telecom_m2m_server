<?php

namespace App\Http\Controllers;

use App\Models\StationSettings;
use App\Models\Station;
use App\Models\City;
use App\Models\Version;
use App\Models\DeviceType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StationSettingsController extends Controller
{
    public function index(Request $request)
    {
        return StationSettings::where('station_id', $request->station_id)->get()->values();
    }

    public function edit(Request $request): JsonResponse
    {
        $this->validate($request, [
            'station_id' => 'required',
            'name' => 'required',
            'version_id' => 'required',
            'city_id' => 'required',
        ]);

        try {
            $city = City::find($request->city_id);
            if (!$city)
                return response()->json(['message' => 'No city with this id']);

            $station = Station::find($request->station_id);
            if (!$station)
                return response()->json(['message' => 'No station with this id']);

            $station_settings = StationSettings::where(['station_id' => $request->station_id])->first();
            if (!$station_settings)
                return response()->json(['message' => 'No settings for station with this id']);

            $version = Version::find($request->version_id);
            if (!$version)
                return response()->json(['message' => 'No version with this id']);

            $version_device_type = DeviceType::find($version->device_type_id);
            $real_device_type = DeviceType::find($station->device_type_id);

            if ($version->device_type_id == $station->device_type_id)
                $station_settings->version_id = $request->version_id;
            else
                return response()->json([
                    'message' => 'Wrong sensor type. this version only for '
                        . $version_device_type->device_type . ". Your device is " . $real_device_type->device_type]);

            $station_settings->version_id = $request->version_id;
            $station_settings->name = $request->name;
            $station_settings->version_id = $request->version_id;
            $station_settings->city_id = $request->city_id;

            if ($station_settings->save())
                return response()->json(['message' => 'Data created successfully, station updated']);
            else
                return response()->json(['message' => 'Something gone wrong']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
