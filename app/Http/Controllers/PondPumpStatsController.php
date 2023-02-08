<?php

namespace App\Http\Controllers;

use App\Http\Models\DeviceTypes;
use App\Http\Models\PondPumpStats;
use App\Http\Requests\StorePondPumpRequest;
use Illuminate\Http\Request;

class PondPumpStatsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return response()->json([
            'payload' => [PondPumpStats::all()],
            'errors' => ['non']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePondPumpRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $pond_pump = DeviceTypes::firstOrCreate([
            'name' => $request->name,
            'pond_id' => 1,
            'description'=>'Variable speed PondPump'
        ]);
        $all_params = $request->all();
        $all_params['device_id']=$pond_pump->id;
        $all_params['timestamp']=time();
        $res = PondPumpStats::create($all_params);

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
