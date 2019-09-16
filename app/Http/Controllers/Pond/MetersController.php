<?php

namespace App\Http\Controllers\Pond;

use App\Http\Models\MeterReadings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MetersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pageSize=15;
        $allValues = MeterReadings::meterValuesStructured($pageSize);
        $annualStats = MeterReadings::averageWaterCalculator(31536000);
        $weekStats = MeterReadings::averageWaterCalculator(604800);
        $monthStats = MeterReadings::averageWaterCalculator(2592000);

        return view('pages.pond.meters.index', compact(['allValues', 'weekStats', 'monthStats', 'annualStats']));
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
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('pages.pond.meters.index');
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


    public function metersSubmit(Request $request)
    {

        $this->validate($request, [
            'meter_id' => 'required|numeric',
            'readings' => 'required|regex:/^\d+(\.\d{1,5})?$/',
        ]);
        $items = $request->all();
        if (!isset($items['message']) || $items['message'] == '') {
            $items['message'] = '';
        }
        $items['timestamp'] = time();
        (new MeterReadings())->create($items);
        return response()->json(null, 200);
    }
}
