<?php

namespace App\Http\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class PowerUsageTenMinutes extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'average',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'integer',
        'average' => 'float',
    ];


    public static function createOrUpdateRecord($requestData)
    {
        try {
            $sixHoursAgo = Carbon::now()->subHours(6);
            $latestRecords = self::where('name', $requestData['name'])
                ->where('average', $requestData['average'])
                ->whereBetween('timestamp', [$sixHoursAgo, Carbon::now()])
                ->latest('timestamp')
                ->get();

            if ($latestRecords->count() >= 2) {
                $latestRecord = $latestRecords->first();
                $latestRecord->touch(); // Update only the 'updated_at' timestamp
                return ['message' => 'Updated existing record'];
            }

            self::create([
                'name' => $requestData['name'],
                'type' => $requestData['type'],
                'average' => $requestData['average'],
                'timestamp' => time(),
            ]);

            return ['message' => 'Created new record'];
        } catch (QueryException $e) {
            return ['message' => 'Database error: ' . $e->getMessage()];

        } catch (\Exception $e) {
            return ['message' => 'An error occurred: ' . $e->getMessage()];

        }
    }
}
