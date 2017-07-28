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


    public static function getProxyForClient()
    {
        $randList = ProxyList::getRandomProxy(null, 1);
        $cred = explode(':', env('PROXY', '" "":" "'));
        array_walk($randList, function (&$ip) use ($cred) {
            $ip = (object)array(
                'ip' => $ip['ip'],
                'user' => trim($cred[0]),
                'pwd' => trim($cred[1])
            );
        });
        return reset($randList);
    }

    public static function getCurlPage($oProxy, $url)
    {
        $proxy = "$oProxy->user:$oProxy->pwd@$oProxy->ip";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
        curl_setopt($ch,CURLOPT_USERAGENT, UserAgentList::inRandomOrder()->get()->first()->Agent);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $page = curl_exec($ch);
        curl_close($ch);
        return $page;
    }
}
