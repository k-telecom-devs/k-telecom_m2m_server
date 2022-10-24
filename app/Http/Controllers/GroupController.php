<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GroupController extends Controller
{

    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'group_name' => 'required',
        ]);

        try {
            $user = auth()->user();
            $group = new Group();
            $group->group_name = $request->group_name;

            $group->user_id = $user['id'];
            
            if ($group->save()) {
                return response()->json(['message' => 'Group created successfully']);
            }
            else {
                return response()->json(['message' => 'Something gone wrong']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
