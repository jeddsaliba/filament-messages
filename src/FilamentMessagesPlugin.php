<?php

namespace Jeddsaliba\FilamentMessages;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Jeddsaliba\FilamentMessages\Filament\Pages\Messages;

class FilamentMessagesPlugin implements Plugin
{
    /**
     * Get the plugin ID.
     *
     * @return string
     */
    public function getId(): string
    {
        return 'filament-messages';
    }

    /**
     * Register the plugin's pages with the given panel.
     *
     * @param Panel $panel The Filament panel instance to which the plugin's pages should be registered.
     * @return void
     */
    public function register(Panel $panel): void
    {
        $panel->pages([
            Messages::class,
        ]);
    }

    /**
     * Boot the plugin with the given panel.
     *
     * This function is called after all plugins have been registered. It is used to perform
     * any actions required to initialize the plugin within the given Filament panel.
     *
     * @param Panel $panel The Filament panel instance used to boot the plugin.
     * @return void
     */
    public function boot(Panel $panel): void
    {
        //
    }

    /**
     * Resolve the plugin instance from the service container.
     *
     * @return static
     */
    public static function make(): static
    {
        return app(static::class);
    }

    /**
     * Resolve the plugin instance from the Filament container.
     *
     * @return static
     */
    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
