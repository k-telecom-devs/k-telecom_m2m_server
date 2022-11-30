<?php

namespace App\Http\Controllers;

use App\Models\Subgroup;
use App\Models\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubgroupController extends Controller
{

    public function create(Request $request): JsonResponse
    {
        $this->validate($request, [
            'subgroup_name' => 'required',
            'group_id' => 'required',
        ]);

        $group = Group::find($request->group_id);
        if (!$group)
            return response()->json(['message' => 'No group with this id']);

        try {
            $user = auth()->user();

            $subgroup = new Subgroup();
            $subgroup->subgroup_name = $request->subgroup_name;
            $subgroup->group_id = $request->group_id;
            $subgroup->user_id = $user['id'];

            if ($subgroup->save())
                return response()->json(['message' => 'Subgroup created successfully']);
            else
                return response()->json(['message' => 'Something gone wrong']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
