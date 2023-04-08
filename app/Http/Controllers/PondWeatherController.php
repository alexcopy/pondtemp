<?php

namespace App\Http\Controllers;

use App\Http\Models\PondWeather;
use Illuminate\Http\Request;

class PondWeatherController extends Controller
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
        //
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
            $all_params = $request->all();
            $dublicate_id = PondWeather::check_dublicates($all_params);
            $all_params['timestamp'] = time();
            if (!$dublicate_id) {
                $res = PondWeather::create($all_params);
            } else {

                $res = PondWeather::find($dublicate_id)->update($all_params);
                $res=$all_params;
            }

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
        //
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
