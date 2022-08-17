<?php


namespace App\Http\Services;


class Signature
{
    /**
     * @var string
     */
    private $secret;

    /**
     * @var string
     */
    private $signature;

    /**
     * @var array
     */
    private $body;

    /**
     * @param string $secret
     * @param string $signature
     * @param array $body
     * @return void
     */
    public function __construct($secret, $signature, array $body)
    {
        $this->secret = $secret;
        $this->signature = $signature;
        $this->body = $body;
    }

    /**
     * Check to see if the signature is valid
     *
     * @return bool
     */
    public function isValid()
    {
        $expected = hash_hmac('sha256', json_encode($this->body), $this->secret, false);

        return $this->signature == $expected;
    }
}