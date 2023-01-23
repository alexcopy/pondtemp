<?php

namespace App\Http\Controllers;

use App\Http\Models\SolarMeter;
use Illuminate\Http\Request;

class SolarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $all_meters = SolarMeter::all();
        return response()->json([
            'payload' => [$all_meters],
            'errors' => []
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $vals =SolarMeter::firstOrCreate($request->all());
        return response()->json([
            'payload' => [$vals],
            'errors' => []
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return response()->json([
            'payload' => [],
            'errors' => []
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        return response()->json([
            'payload' => [],
            'errors' => []
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return response()->json([
            'payload' => [],
            'errors' => []
        ]);
    }
}
