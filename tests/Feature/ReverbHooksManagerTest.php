<?php

declare(strict_types=1);

use JulioCCorreia\ReverbHooks\Facades\ReverbHooks;
use JulioCCorreia\ReverbHooks\ReverbHooksManager;
use Laravel\Reverb\ServerProviderManager;
use Mockery\MockInterface;

describe('Architecture & Binding', function (): void {
    it('binds the manager singleton to the container', function (): void {
        $instance = app('reverb-hooks');

        expect($instance)->toBeInstanceOf(ReverbHooksManager::class);
    });

    it('resolves the facade root correctly', function (): void {
        expect(ReverbHooks::getFacadeRoot())->toBeInstanceOf(ReverbHooksManager::class);
    });
});

describe('Driver Proxying', function (): void {
    it('delegates method calls to the underlying Reverb driver', function (): void {
        $driverMock = Mockery::mock();
        $driverMock->shouldReceive('someReverbMethod')
            ->once()
            ->with('arg1')
            ->andReturn('success');

        $managerMock = Mockery::mock(ServerProviderManager::class, function (MockInterface $mock) use ($driverMock): void {
            $mock->shouldReceive('driver')->andReturn($driverMock);
        });

        app()->instance(ServerProviderManager::class, $managerMock);

        $reverbHooks = new ReverbHooksManager(app());

        expect($reverbHooks->someReverbMethod('arg1'))->toBe('success');
    });

    it('throws an exception if the Reverb driver is unavailable', function (): void {
        app()->forgetInstance(ServerProviderManager::class);

        $reverbHooks = new ReverbHooksManager(app());

        $reverbHooks->someMethod();
    })->throws(RuntimeException::class, 'Reverb driver not initialized');
});
