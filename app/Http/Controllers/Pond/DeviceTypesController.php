<?php

namespace App\Http\Controllers\Pond;

use App\DeviceTypes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeviceTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.pond.types.index');
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DeviceTypes  $deviceTypes
     * @return \Illuminate\Http\Response
     */
    public function show(DeviceTypes $deviceTypes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DeviceTypes  $deviceTypes
     * @return \Illuminate\Http\Response
     */
    public function edit(DeviceTypes $deviceTypes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DeviceTypes  $deviceTypes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeviceTypes $deviceTypes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DeviceTypes  $deviceTypes
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeviceTypes $deviceTypes)
    {
        //
    }
}
