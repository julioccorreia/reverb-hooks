# Laravel Reverb Hooks

[![Latest Version on Packagist](https://img.shields.io/packagist/v/julioccorreia/reverb-hooks.svg?style=flat-square)](https://packagist.org/packages/julioccorreia/reverb-hooks)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/julioccorreia/reverb-hooks/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/julioccorreia/reverb-hooks/actions)
[![Total Downloads](https://img.shields.io/packagist/dt/julioccorreia/reverb-hooks.svg?style=flat-square)](https://packagist.org/packages/julioccorreia/reverb-hooks)
[![PHPStan Level 9](https://img.shields.io/badge/PHPStan-level%209-brightgreen.svg?style=flat-square)](https://github.com/phpstan/phpstan)

A lightweight and powerful package to intercept and hook into Laravel Reverb server events with ease. 

This package allows you to listen to channel creation, message delivery, and connection lifecycle events using a clean, fluent API.

## Requirements

* PHP 8.2 or higher
* Laravel 11.x or 12.x
* Laravel Reverb

## Installation

You can install the package via composer:

```bash
composer require julioccorreia/reverb-hooks
```

# Usage
The best place to register your hooks is in the `boot` method of your `AppServiceProvider` or a dedicated `EventServiceProvider`.

# Channel Hooks
You can listen to when channels are created or removed. Supports wildcards for flexible filtering.

```php
use JulioCCorreia\ReverbHooks\Facades\ReverbHooks;

// Listen to any channel creation
ReverbHooks::onChannelCreated(function ($event) {
    Log::info("Channel created: {$event->channel->name()}");
});

// Listen to specific channels using wildcards
ReverbHooks::onChannelCreated('chat.*', function ($event) {
    // This only triggers for channels starting with 'chat.'
});
```

# Message Hooks
Intercept messages as they flow through the Reverb server.

```php
// Triggers when a message is successfully sent to a channel
ReverbHooks::onMessageSent('orders.*', function ($event) {
    // Perfect for logging or metrics
});

// Triggers when any raw message is received by the server
ReverbHooks::onMessageReceived(function ($event) {
    // Low-level access to the incoming payload
});
```

# Connection Hooks
Monitor the health of your WebSocket server.

```php
// Triggers when a stale connection is pruned by the server
ReverbHooks::onConnectionPruned(function ($event) {
    Log::warning("Connection pruned for user: {$event->connection->identifier()}");
});
```

# Available

| Method | Description |
| --- | --- |
| onChannelCreated($channels, $callback) | Triggered when a new channel is instantiated. |
| onChannelRemoved($channels, $callback) | Triggered when a channel is destroyed. |
| onMessageSent($channels, $callback) | Triggered after a message is broadcasted. |
| onMessageReceived($callback) | Triggered upon receiving a raw message from a client. |
| onConnectionPruned($callback) | Triggered when the server cleans up an inactive connection. |

# Development & Quality
This package maintains high code quality standards:

 - **PHPStan**: Analyzed at Level 9 for maximum type safety.
 - **Laravel Pint**: Follows the official Laravel coding style.
 - **Rector**: Automated code refactoring for modern PHP features.
 - **Strict Types**: All files use declare(strict_types=1).

# Testing

You can run the tests using Pest:
```php
composer test
```

# Changelog

Please see CHANGELOG for more information on what has changed recently.

# Credits
**Júlio César Correia Gazige**

[![GitHub](https://img.shields.io/badge/github-%23121011.svg?style=for-the-badge&logo=github&logoColor=white)](https://github.com/julioccorreia)
[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=for-the-badge&logo=linkedin&logoColor=white)](https://www.linkedin.com/in/julioccorreia/)


# License
The MIT License (MIT). Please see License File for more information.