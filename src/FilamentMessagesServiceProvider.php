<?php

namespace Jeddsaliba\FilamentMessages;

use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentIcon;
use Jeddsaliba\FilamentMessages\Livewire\Messages\Inbox;
use Jeddsaliba\FilamentMessages\Livewire\Messages\Messages;
use Jeddsaliba\FilamentMessages\Livewire\Messages\Search;
use Jeddsaliba\FilamentMessages\Commands\FilamentMessagesCommand;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentMessagesServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-messages';

    public static string $viewNamespace = 'filament-messages';

    /**
     * Configure the package.
     *
     * @param \Spatie\LaravelPackageTools\Package $package
     * @return void
     */
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands());

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile($configFileName);
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    /**
     * Registers the package.
     *
     * This method is called after the package has been registered with the Laravel service container.
     *
     * @return void
     */
    public function packageRegistered(): void
    {
        parent::packageRegistered();
    }

    /**
     * Boots the package after registration.
     *
     * Registers custom icons and Livewire components for the Filament Messages package.
     * This includes components such as 'fm-inbox', 'fm-messages', and 'fm-search'.
     * 
     * @return void
     */
    public function packageBooted(): void
    {
        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Livewire
        Livewire::component('fm-inbox', Inbox::class);
        Livewire::component('fm-messages', Messages::class);
        Livewire::component('fm-search', Search::class);
    }

    /**
     * The name of the package that contains the assets for the Filament Messages package.
     * Returns 'jeddsaliba/filament-messages'.
     *
     * @return string|null
     */
    protected function getAssetPackageName(): ?string
    {
        return 'jeddsaliba/filament-messages';
    }

    /**
     * The assets that the Filament Messages package registers to the Filament application.
     * This function should return an array of Asset instances that the package registers.
     * If there are no assets to be registered, return an empty array.
     * 
     * @return array<\Filament\Support\Assets\Asset> The array of Asset instances.
     */
    protected function getAssets(): array
    {
        return [
            //
        ];
    }

    /**
     * Get the artisan commands for the Filament Messages package.
     *
     * This function should return an array of artisan command classes that the package registers.
     * If there are no commands to be registered, return an empty array.
     *
     * @return array<class-string<\Illuminate\Console\Command>> The array of command classes.
     */
    protected function getCommands(): array
    {
        return [
            FilamentMessagesCommand::class
        ];
    }

    /**
     * @return array<string, string> A key-value array of [icon_name => icon_path] where the path is relative to the package's resources/icons directory.
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * Get the routes for the Filament Messages package.
     *
     * This function should return an array of routes that the package registers.
     * If there are no routes to be registered, return an empty array.
     *
     * @return array<string> The array of route definitions.
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * Gets the script data to be passed to the JavaScript application.
     *
     * If your package has JavaScript components that need to access data from the server,
     * you can add key-value pairs to this array. The values will be passed to the JavaScript
     * application as a global variable named after the package.
     *
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * Get the list of migration filenames for the Filament Messages package.
     *
     * @return array<string> The array of migration filenames.
     */
    protected function getMigrations(): array
    {
        return [
            'create_fm_inboxes_table',
            'create_fm_messages_table',
        ];
    }
}
