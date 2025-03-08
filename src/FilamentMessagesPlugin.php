<?php

namespace Jeddsaliba\FilamentMessages;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Jeddsaliba\FilamentMessages\Filament\Pages\Messages;

class FilamentMessagesPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-messages';
    }

    public function register(Panel $panel): void
    {
        $panel->pages([
            Messages::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
