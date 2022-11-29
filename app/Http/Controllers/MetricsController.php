<?php

namespace App\Http\Controllers;

use App\Models\Metrics;
use App\Models\Group;
use App\Models\Subgroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MetricsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $metrics = Metrics::all()->where('user_id', $user['id']);
        return response()->json(['message' => $metrics]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();
        if(! Group::find($request->group_id)){
            return response()->json(['message' => "Can't find group"]);
        }
        if(!$user){
            return response()->json(['message' => "Can't find user"]);
        }
        try {
            $metrics = new Metrics;
            $metrics->user_id = $user['id'];
            $metrics->group_id = $request->group_id;
            if ($metrics->save()){
                return response()->json(['message' => 'Metrics saved']);
            }
            else{
                return response()->json(['message' => 'Smth gone wrong']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
