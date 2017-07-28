<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ProxyList extends Model
{

    protected $fillable = ['ip', 'country', 'town'];


    public static function getRandomProxy($country = null, $limit = 1)
    {

        if (!$country) {
            return self::where('is_disabled', '<', 2)->inRandomOrder()->limit($limit)->get()->toArray();
        } else {
            return self::where('is_disabled', '<', 2)->where("country", $country)->inRandomOrder()->limit($limit)->get();
        }
    }

    public static function setProxyDisable($ip)
    {
        $currentVal = self::where("ip", $ip)->get()->first();
        if (!$currentVal) {
            throw new \Exception("IP address is not exists");
        }
        $disabled = (int)$currentVal->is_disabled;
        if ($disabled < 100) {
            $disabledVal = self::find($currentVal->id);
            $disabledVal->is_disabled = $disabled + 1;
            $disabledVal->save();
        }
    }
}
