<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use JulioCCorreia\ReverbHooks\Facades\ReverbHooks;
use Laravel\Reverb\Events\ChannelCreated;
use Laravel\Reverb\Events\ChannelRemoved;
use Laravel\Reverb\Events\ConnectionPruned;
use Laravel\Reverb\Events\MessageReceived;
use Laravel\Reverb\Events\MessageSent;
use Laravel\Reverb\ServerProviderManager;
use Tests\Stubs\FakeChannel;
use Tests\Stubs\FakeEventWithChannel;

beforeEach(function (): void {
    app()->bind(ServerProviderManager::class, function () {
        $mock = Mockery::mock();
        $mock->shouldReceive('driver')->andReturnNull();

        return $mock;
    });
});

describe('Channel Events', function (): void {
    it('registers listeners for channel lifecycle events', function (): void {
        ReverbHooks::onChannelCreated('*', fn (): bool => true);
        ReverbHooks::onChannelRemoved('*', fn (): bool => true);
        ReverbHooks::onMessageSent('*', fn (): bool => true);

        expect(Event::hasListeners(ChannelCreated::class))->toBeTrue()
            ->and(Event::hasListeners(ChannelRemoved::class))->toBeTrue()
            ->and(Event::hasListeners(MessageSent::class))->toBeTrue();
    });

    it('executes callback only when channel name matches the wildcard', function (): void {
        $executed = false;

        ReverbHooks::onChannelCreated('chat.*', function () use (&$executed): void {
            $executed = true;
        });

        Event::dispatch(ChannelCreated::class, new FakeEventWithChannel(new FakeChannel('chat.room1')));
        expect($executed)->toBeTrue();

        $executed = false;

        Event::dispatch(ChannelCreated::class, new FakeEventWithChannel(new FakeChannel('orders.123')));
        expect($executed)->toBeFalse();
    });

    it('supports omitting the channel argument (defaults to wildcard)', function (): void {
        $executed = false;

        ReverbHooks::onChannelCreated(function () use (&$executed): void {
            $executed = true;
        });

        Event::dispatch(ChannelCreated::class, new FakeEventWithChannel(new FakeChannel('any.channel')));

        expect($executed)->toBeTrue();
    });
});

describe('Global Events', function (): void {
    it('registers listeners for server events', function (): void {
        ReverbHooks::onMessageReceived(fn (): bool => true);
        ReverbHooks::onConnectionPruned(fn (): bool => true);

        expect(Event::hasListeners(MessageReceived::class))->toBeTrue()
            ->and(Event::hasListeners(ConnectionPruned::class))->toBeTrue();
    });
});

describe('Callback Resolution', function (): void {
    it('resolves invokable classes from the container', function (): void {
        $spyListener = new class
        {
            public bool $invoked = false;

            public function __invoke($event): void
            {
                $this->invoked = true;
            }
        };

        $className = 'App\\Listeners\\MyInvokableListener';
        app()->instance($className, $spyListener);

        ReverbHooks::onMessageReceived($className);

        Event::dispatch(MessageReceived::class, ['fake-payload']);

        expect($spyListener->invoked)->toBeTrue();
    });
});

describe('Edge Cases', function (): void {
    it('ignores registration if callback is null', function (): void {
        Event::forget(ChannelCreated::class);

        ReverbHooks::onChannelCreated('channel', null);

        expect(Event::hasListeners(ChannelCreated::class))->toBeFalse();
    });

    it('fails gracefully when event payload has invalid channel object', function (): void {
        $executed = false;

        ReverbHooks::onChannelCreated('*', function () use (&$executed): void {
            $executed = true;
        });
        $brokenEvent = new stdClass;
        $brokenEvent->channel = new stdClass;

        Event::dispatch(ChannelCreated::class, $brokenEvent);

        expect($executed)->toBeFalse();
    });
});
