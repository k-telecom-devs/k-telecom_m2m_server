<?php


namespace App\Http\Controllers;


use App\Models\DeviceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeviceTypeController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'device_type' => 'required',
        ]);

        $device_type = new DeviceType();
        $device_type->device_type = $request->device_type;

        if($device_type->save())
            return response()->json(['message' => 'Device type created successfully.']);
        else
            return response()->json(['message' => 'Something wrong.']);
    }
}
