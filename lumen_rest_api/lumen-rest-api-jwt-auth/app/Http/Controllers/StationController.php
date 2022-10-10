<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function index()
    {
        return Station::all();
    }

    public function store(Request $request)
    {
        try {
            $station = new Station();

            if ($station->save()) {
                return response()->json(['message' => 'Station created successfully.']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}