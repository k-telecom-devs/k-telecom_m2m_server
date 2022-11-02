<?php


namespace App\Http\Controllers;


use App\Models\Version;
use App\Models\DeviceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'file_url' => 'required',
            'description' => 'required',
            'version' => 'required',
            'device_type_id' => 'required'
        ]);
        $version_device_type = DeviceType::find($request->device_type_id);
        if(!$version_device_type){
            return response()->json(['message' => 'No device type with this id']);
        }

        try{
        $version = new Version();
        $version->file_url = $request->file_url;
        $version->device_type_id = $request->device_type_id;
        $version->description = $request->description;
        $version->version = $request->version;

        if($version->save())
            return response()->json(['message' => 'Version created successfully. Versinon id - '.$version->id]);
        else
            return response()->json(['message' => 'Something gone wrong.']);
        }   
        catch (\Exception $e) {
            $version->delete();
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json(['message' => Version::all()]);
    }

}
