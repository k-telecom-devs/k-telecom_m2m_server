<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['message' => City::all()]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'city_name' => 'required',
        ]);

        try {

            $city = new City;

            $city->city_name = $request->city_name;

            if ($city->save()) {
                return response()->json(['message' => 'City created successfully']);
            }
            else {
                return response()->json(['message' => 'Something gone wrong']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
