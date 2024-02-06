<?php

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class Devices extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'pond_id',
        'type_id',
        'deviceName',
        'description',
    ];


    public static function createDeviceWithTypes($pondId, $typeName, $deviceName, $description)
    {
        try {
            // Create a DeviceTypes instance and retrieve its ID
            $deviceType = DeviceTypes::firstOrCreate([
                'pond_id' => $pondId,
                'name' => $typeName,
                'description' => 'YourDeviceTypeDescription', // Replace with your actual device type description
            ]);

            // Use the ID of the created device type to create a Devices instance
            return self::firstOrCreate([
                'pond_id' => $pondId,
                'type_id' => $deviceType->id,
                'deviceName' => $deviceName,
                'description' => $description,
            ]);
        } catch (\Exception $e) {
            // Handle the exception as needed
            // You might want to log the error or return a specific response
            // For simplicity, rethrowing the exception here
            throw $e;
        }
    }
}
