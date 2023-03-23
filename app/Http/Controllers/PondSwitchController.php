<?php

namespace App\Http\Controllers;

use App\Http\Models\DeviceTypes;
use App\Http\Models\PondSwitch;
use Illuminate\Http\Request;

class PondSwitchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $error = false;
        $err_msg = '';
        try {
            $pond_pump = DeviceTypes::firstOrCreate([
                'name' => $request->device_id,
                'pond_id' => 1,
                'description' => $request->name
            ]);
            $all_params = $request->all();
            $all_params['switch_id'] = $pond_pump->id;
            $all_params['timestamp'] = time();// todo: this is a temp  adhoc to write proper values into table remove later and add proper Verification class (have no time now to do it)
            $res = PondSwitch::create($all_params);
        } catch (\Exception $e) {
            $res = $request->all();
            $error = true;
            $err_msg = $e->getMessage();
        }

        return response()->json([
            'payload' => $res,
            'errors' => $error,
            'errors_msg' => $err_msg
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
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
