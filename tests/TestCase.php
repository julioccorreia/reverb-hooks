<?php

declare(strict_types=1);

namespace Tests;

use JulioCCorreia\ReverbHooks\ReverbHooksServiceProvider as ReverbHooksReverbHooksServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            ReverbHooksReverbHooksServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
    }
}
