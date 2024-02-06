<?php

namespace App\Http\Controllers;

use App\Http\Models\Devices;
use App\Http\Models\DeviceTypes;
use App\Http\Models\WaterTempSensor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WaterTempController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {

            $device = Devices::createDeviceWithTypes($request->pondId, $request->typeName, $request->deviceName, $request->description);
            $tempCurrent = ($request->temp_current == 0) ? 0 : (int) $request->temp_current / 10.0;

            // Create a WaterTempSensor instance
            $waterTempSensor = WaterTempSensor::create([
                'temp_current' => $tempCurrent,
                'device_id' => $device->id,
                'temp_unit_convert' => $request->temp_unit_convert ?? 'C',
                'humidity_value' => $request->humidity_value ?? 0,
                'bright_value' => $request->bright_value ?? 0,
                'temp_calibration' => $request->temp_calibration ?? 0,
                'timestamp' => now()->timestamp,
            ]);


            return response()->json([
                'payload' => $waterTempSensor,
                'errors' => []
            ]);
        } catch (\Exception $e) {
            // Handle the exception as needed
            // You might want to log the error or return a specific response
            return response()->json([
                'payload' => [],
                'errors' => $e->getMessage(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
