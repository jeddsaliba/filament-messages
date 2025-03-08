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

    public function packageRegistered(): void
    {
        parent::packageRegistered();
    }

    public function packageBooted(): void
    {
        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Livewire
        Livewire::component('fm-inbox', Inbox::class);
        Livewire::component('fm-messages', Messages::class);
        Livewire::component('fm-search', Search::class);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'jeddsaliba/filament-messages';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            //
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentMessagesCommand::class
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_fm_inboxes_table',
            'create_fm_messages_table',
        ];
    }
}
