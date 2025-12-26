# Changelog
All notable changes to this project will be documented in this file.

The format is based on Keep a Changelog, and this project adheres to Semantic Versioning.

## [1.0.0] - 2025-12-26
Added
* Initial release of Laravel Reverb Hooks.
* ReverbHooks Facade for easy access to event hooks.
* onChannelCreated hook to intercept new channel instantiations.
* onChannelRemoved hook to intercept channel destruction.
* onMessageSent hook to monitor broadcasted messages.
* onMessageReceived hook for raw incoming payload interception.
* onConnectionPruned hook to track connection lifecycle.
* Support for wildcards (*) in channel filtering.
* Full integration with PHP 8.2+ and Laravel 11.x / 12.x.
* Strict typing and PHPStan Level 9 analysis.
* Laravel Pint and Rector configuration for code quality.