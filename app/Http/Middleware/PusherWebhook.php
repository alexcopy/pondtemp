<?php


namespace App\Http\Middleware;


use App\Http\Services\Signature;
use Closure;

class PusherWebhook
{

    /**
     * Handle an incoming request
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        $secret = config('pusher.secret');
        $signature = $request->header('X_PUSHER_SIGNATURE');

        $signature = new Signature($secret, $signature, $request->all());

        if (! $signature->isValid()) {
            throw new \Exception('invalid_webhook_signature');
        }

        return $next($request);
    }
}