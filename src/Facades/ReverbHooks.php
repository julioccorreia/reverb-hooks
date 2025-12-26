<?php

declare(strict_types=1);

namespace JulioCCorreia\ReverbHooks\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void onChannelCreated((array | string | Closure) $channels, (Closure | string | null) $callback = null)
 * @method static void onChannelRemoved((array | string | Closure) $channels, (Closure | string | null) $callback = null)
 * @method static void onMessageSent((array | string | Closure) $channels, (Closure | string | null) $callback = null)
 * @method static void onMessageReceived((Closure | string) $callback)
 * @method static void onConnectionPruned((Closure | string) $callback)
 *
 * @see \JulioCCorreia\ReverbHooks\ReverbHooksManager
 */
class ReverbHooks extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'reverb-hooks';
    }
}
