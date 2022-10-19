<?php


namespace App\Http\Controllers;


use App\Models\Version;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $version = new Version();

        $version->file_url = $request->file_url;
        $version->description = $request->description;
        $version->version = $request->version;

        if($version->save())
            return response()->json(['message' => 'Version created successfully.']);
        else
            return response()->json(['message' => 'Something wrong.']);
    }
}
