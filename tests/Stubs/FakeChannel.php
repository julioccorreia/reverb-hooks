<?php

declare(strict_types=1);

namespace Tests\Stubs;

class FakeChannel
{
    public function __construct(public string $name) {}

    public function name(): string
    {
        return $this->name;
    }
}
