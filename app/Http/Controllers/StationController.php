<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Version;
use App\Models\DeviceType;
use App\Models\StationSettings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return Station::with('settings')->where('user_id', $user['id'])->get()->values();
    }

    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'mac' => 'required',
            'device_type_id' => 'required',
            'version_id' => 'required',
        ]);

        try {
            $user = auth()->user();

            $station = new Station();
            $station_settings = new StationSettings();

            $version = Version::find($request->version_id);
            if(! $version){
                return response()->json(['message' => 'No version with this id']);
            }

            $version_device_type = DeviceType::find($version->device_type_id);
            $real_device_type = DeviceType::find($request->device_type_id);

            $created_station = Station::where('mac', $request->mac)->get();

            if ($version->device_type_id == $request->device_type_id) {
                $station_settings->version_id = $request->version_id;
            } else {
                return response()->json(['message' => 'Wrong sensor type. this version only for ' . $version_device_type->device_type . ". Your device is " . $real_device_type->device_type]);
            }

            if(isset($created_station[0])){
                return response()->json(['message' => 'This station alredy exists'. $created_station]);
            }

            $station->mac = $request->mac;
            $station->user_id = $user['id'];
            $station->device_type_id = $request->device_type_id;

            if ($station->save()) {
                $station_settings->name = $request->name;
                $station_settings->station_id = $station->id;
                $station_settings->version_id = $request->version_id;
            }
            else{
                return response()->json(['message' => 'Something gone wrong.']);
            }

            if ($station_settings->save()) {
                return response()->json(['message' => 'Station created successfully.']);
            } else {
                $station->delete();
                return response()->json(['message' => 'Station created but station settings cant be init.']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
