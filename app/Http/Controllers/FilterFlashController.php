<?php

namespace App\Http\Controllers;

use App\Http\Models\FilterFlash;
use App\Http\Models\SolarMeter;
use Illuminate\Http\Request;

class FilterFlashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $all_flashes = FilterFlash::all();
        return response()->json([
            'payload' => [$all_flashes],
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
        $req_data = $request->all();
        $solar = SolarMeter::firstOrCreate([
            'name' => $request->name,
            'units' => "Amp",
            'description' => ''
        ]);
        $req_data['meter_id'] = $solar->id;
        $all_flashes = FilterFlash::create($req_data);
        return response()->json([
            'payload' => [$all_flashes],
            'errors' => []
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
