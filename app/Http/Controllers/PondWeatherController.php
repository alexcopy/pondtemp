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
        $err_msg = 'NO ERROR';
        try {
            $all_params = $request->all();
            if (isset($all_params['is_valid'])) {
                unset($all_params['is_valid']);
            }
            $check_duplicates = PondWeather::check_duplicates($all_params);
            $all_params['timestamp'] = time();
            if (!$check_duplicates) {
                $res = PondWeather::create($all_params);
            } else {
                PondWeather::find($check_duplicates)->update($all_params);
                $res = $all_params;
            }

        } catch (\Exception $e) {
            $res = $request->all();
            $error = true;
            $err_msg = 'PondWeatherController: '.$e->getMessage();
        }

        return response()->json([
            'payload' => json_encode($res),
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
