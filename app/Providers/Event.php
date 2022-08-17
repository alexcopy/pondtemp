<?php


namespace App\Providers;


abstract class Event
{

    /**
     * Check to see if the Webhook is valid
     *
     * @return bool
     */
    abstract public function isValid();

    /**
     * Handle the Event
     *
     * @return void
     */
    abstract public function handle();

    /**
     * Get the UUID from the channel name
     *
     * @param string $channel
     * @return string
     */
    protected function getUuidFromChannelName($channel)
    {
        $position = strpos($channel, ‘ - ‘, strpos($channel, ‘ - ‘) + 2);

        return substr($channel, $position + 1);
    }
}