<?php

namespace App\Http\Controllers;

use App\Http\Models\PowerDevice;
use App\Http\Models\PowerUsageTenMinutes;
use Illuminate\Http\Request;

class PowerDeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'payload' => ["ALL GOOD"],
            'errors' => []
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return response()->json([
            'payload' => ["ALL GOOD"],
            'errors' => []
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $all_params = $request->all();

        if (in_array($all_params['type'], ["daily", "hourly"])) {
            $vals = PowerDevice::createOrUpdateRecord($all_params);
        }
        if ($all_params['type']=="ten_minutes"){
            $vals = PowerUsageTenMinutes::createOrUpdateRecord($all_params);
        }
        return response()->json([
            'payload' => [$vals],
            'errors' => []
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return response()->json([
            'payload' => ["ALL GOOD"],
            'errors' => []
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        return response()->json([
            'payload' => ["ALL GOOD"],
            'errors' => []
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        return response()->json([
            'payload' => ["ALL GOOD"],
            'errors' => []
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return response()->json([
            'payload' => ["ALL GOOD"],
            'errors' => []
        ]);
    }
}
