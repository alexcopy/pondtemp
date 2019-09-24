<?php

namespace App\Http\Controllers\Pond;

use App\Http\Controllers\ApiController;
use App\Http\Models\FishFeed;
use App\Http\Models\MeterReadings;
use App\Http\Models\Tanks;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeedController extends Controller
{

    /**
     * @param Request $request
     * @return array
     */
    public static function feedComputedData(Request $request): array
    {
        $daysInSeconds = $request->get('daysInSeconds', 1) * 54000;
        $ponds = Tanks::all(['tankName', 'id'])->toArray();
        $feed = FishFeed::whereBetween('timestamp', [time() - $daysInSeconds, time()])->get();
        $feedVals = $feed->toArray();
        array_walk($feedVals, function ($v) use (&$feedIDs) {
            $feedIDs[$v['food_type']][] = $v['id'];
        });
        $total = FishFeed::select(['food_type', 'weight', 'created_at'])->get()->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('d/m');
        })->reverse();
        return array($ponds, $feed, $total, $feedIDs);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.pond.feed.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $pondName = $request->get('pond', null);
        $scoops = $request->get('scoops', 1);
        try {
            $pondId = Tanks::where('tankName', $pondName)->firstOrFail()->id;
        } catch (\Exception $exception) {
            $pondId = 1;
        }
        $foodType = $request->get('foodType');
        FishFeed::create([
            'pond_id' => $pondId,
            'food_type' => $foodType,
            'weight' => ($foodType == 'pellets' ? 40 : 80) * $scoops,
            'description' => ' ',
            'is_disabled' => 0,
            'timestamp' => time()
        ]);
        list($ponds, $feed, $total, $feedIDs) = self::feedComputedData($request);
        return response()->json([
            'pellets' => $feed->where('food_type', 'pellets')->count(),
            'sinking' => $feed->where('food_type', 'sinkpellets')->count(),
            'ponds' => $ponds,
            'total' => $total,
            'feedids' => $feedIDs,
        ]);
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
    public function destroy(Request $request)
    {
        $id = (int)$request->get('id', 0);
        FishFeed::destroy($id);
        list($ponds, $feed, $total, $feedIDs) = self::feedComputedData($request);
        return response()->json([
            'pellets' => $feed->where('food_type', 'pellets')->count(),
            'sinking' => $feed->where('food_type', 'sinkpellets')->count(),
            'ponds' => $ponds,
            'total' => $total,
            'feedids' => $feedIDs,
        ]);

    }
}
