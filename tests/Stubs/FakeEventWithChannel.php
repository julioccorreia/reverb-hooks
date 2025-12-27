<?php

declare(strict_types=1);

namespace Tests\Stubs;

class FakeEventWithChannel
{
    public function __construct(public FakeChannel $channel) {}
}
