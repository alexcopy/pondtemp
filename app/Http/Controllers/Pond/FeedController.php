<?php

namespace App\Http\Controllers\Pond;

use App\Http\Controllers\ApiController;
use App\Http\Models\FishFeed;
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
        $total = FishFeed::select(['food_type', 'weight', 'created_at']) ->get()->groupBy(function ($date) {
            return Carbon::parse($date->created_at)->format('d/m');
        });
        return array($ponds, $feed, $total);
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
        list($ponds, $feed, $total) = self::feedComputedData($request);
        return response()->json([
            'pellets' => $feed->where('food_type', 'pellets')->count(),
            'sinking' => $feed->where('food_type', 'sinkpellets')->count(),
            'ponds' => $ponds,
            'total' => $total
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
