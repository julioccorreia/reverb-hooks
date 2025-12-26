<?php

declare(strict_types=1);

namespace JulioCCorreia\ReverbHooks\Concerns;

use Closure;
use Illuminate\Support\Str;
use Laravel\Reverb\Events\ChannelCreated;
use Laravel\Reverb\Events\ChannelRemoved;
use Laravel\Reverb\Events\ConnectionPruned;
use Laravel\Reverb\Events\MessageReceived;
use Laravel\Reverb\Events\MessageSent;

trait InteractsWithEvents
{
    /**
     * Register a callback to be executed when a channel is created.
     *
     * @param  array<int, string>|string|Closure  $channels
     * @param  Closure|string|null  $callback
     * @return void
     */
    public function onChannelCreated(array|string|Closure $channels, Closure|string|null $callback = null): void
    {
        $this->registerChannelEvent(ChannelCreated::class, $channels, $callback);
    }

    /**
     * Register a callback to be executed when a channel is removed.
     *
     * @param  array<int, string>|string|Closure  $channels
     * @param  Closure|string|null  $callback
     * @return void
     */
    public function onChannelRemoved(array|string|Closure $channels, Closure|string|null $callback = null): void
    {
        $this->registerChannelEvent(ChannelRemoved::class, $channels, $callback);
    }

    /**
     * Register a callback to be executed when a message is sent.
     *
     * @param  array<int, string>|string|Closure  $channels
     * @param  Closure|string|null  $callback
     * @return void
     */
    public function onMessageSent(array|string|Closure $channels, Closure|string|null $callback = null): void
    {
        $this->registerChannelEvent(MessageSent::class, $channels, $callback);
    }

    /**
     * Register a callback to be executed when a message is received.
     *
     * @param  Closure|string  $callback
     * @return void
     */
    public function onMessageReceived(Closure|string $callback): void
    {
        $this->app->make('events')->listen(MessageReceived::class, $this->resolveCallback($callback));
    }

    /**
     * Register a callback to be executed when a connection is pruned.
     *
     * @param  Closure|string  $callback
     * @return void
     */
    public function onConnectionPruned(Closure|string $callback): void
    {
        $this->app->make('events')->listen(ConnectionPruned::class, $this->resolveCallback($callback));
    }

    /**
     * Register a generic channel event hook with filtering capabilities.
     *
     * @param  string  $eventClass
     * @param  array<int, string>|string|Closure  $channels
     * @param  Closure|string|null  $callback
     * @return void
     */
    protected function registerChannelEvent(string $eventClass, array|string|Closure $channels, Closure|string|null $callback): void
    {
        if ($channels instanceof Closure || (is_string($channels) && class_exists($channels))) {
            $callback = $channels;
            $channels = '*';
        }

        if ($callback === null) {
            return;
        }

        $this->app->make('events')->listen($eventClass, function ($event) use ($channels, $callback): void {
            /** @var mixed $channel */
            $channel = data_get($event, 'channel');

            if (is_object($channel) && method_exists($channel, 'name') && Str::is($channels, $channel->name())) {
                $this->resolveCallback($callback)($event);
            }
        });
    }

    /**
     * Resolve the callback to a callable.
     *
     * @param  Closure|string  $callback
     * @return callable
     */
    protected function resolveCallback(Closure|string $callback): callable
    {
        if (is_string($callback)) {
            return $this->app->make($callback);
        }

        return $callback;
    }
}
