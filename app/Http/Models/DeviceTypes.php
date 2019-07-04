<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceTypes extends Model
{
   protected $fillable=[
       'pond_id',
       'deviceType',
       'description'
   ];
}
