<?php

declare(strict_types=1);

namespace JulioCCorreia\ReverbHooks;

use Illuminate\Contracts\Foundation\Application;
use JulioCCorreia\ReverbHooks\Concerns\InteractsWithEvents;
use Laravel\Reverb\ServerProviderManager;
use RuntimeException;

class ReverbHooksManager
{
    use InteractsWithEvents;

    /**
     * The current Reverb driver instance.
     *
     * @var mixed
     */
    protected $driver;

    /**
     * Create a new ReverbHooks instance.
     *
     * @return void
     */
    public function __construct(protected Application $app)
    {
        if ($app->bound(ServerProviderManager::class)) {
            $this->driver = $app->make(ServerProviderManager::class)->driver();
        }
    }

    /**
     * Dynamically proxy method calls to the original Reverb driver.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (! $this->driver) {
            throw new RuntimeException('Reverb driver not initialized. Check your Reverb configuration.');
        }

        return $this->driver->$method(...$parameters);
    }
}
