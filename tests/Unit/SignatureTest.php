<?php

namespace Tests\Unit;

use App\Http\Services\Signature;
use Tests\TestCase;

class SignatureTest extends TestCase
{
    /** @test */
    public function should_return_false_when_signature_is_invalid()
    {
        $signature = new Signature('secret', 'signature', ['hello' => 'world']);
        $this->assertFalse($signature->isValid());
    }

    /** @test */
    public function should_return_true_when_signature_is_valid()
    {
        $signature = new Signature(
            'secret',
            '2677ad3e7c090b2fa2c0fb13020d66d5420879b8316eb356a2d60fb9073bc778',
            ['hello' => 'world']
        );

        $this->assertTrue($signature->isValid());
    }
}
