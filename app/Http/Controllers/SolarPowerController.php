<?php

namespace App\Http\Controllers;

use App\Http\Models\SolarMeter;
use App\Http\Models\SolarPower;
use App\Http\Requests\StoreSolarRequest;
use Illuminate\Http\Request;

class SolarPowerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'payload' => [SolarPower::all()],
            'errors' => ['non']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSolarRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $solar_meter = SolarMeter::firstOrCreate([
            'name' => $request->name,
            'units' => $request->value_type,
            'description'=>''
        ]);
        $all_params = $request->all();
        $all_params['meter_id']=$solar_meter->id;
        if (!is_string($all_params['serialized'])){
            $all_params['serialized']=json_encode($all_params['serialized']);
        }
        $res = SolarPower::create($all_params);

        return response()->json([
            'payload' => [$res],
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
            'payload' => [],
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
            'payload' => [],
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
            'payload' => [],
            'errors' => []
        ]);
    }
}
