<?php

namespace Jeddsaliba\FilamentMessages\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class FilamentMessagesCommand extends Command
{
    public $signature = 'filament-messages:install';

    public $description = 'Install the Filament Messages plugin';

    public function handle(): int
    {
        $this->info('Starting Filament Messages installation...');
        $this->publishAssets();
        $this->runMigrations();
        $this->comment('All done');

        return self::SUCCESS;
    }

    protected function publishAssets()
    {
        $this->info('Publishing assets...');

        // Publish migrations
        Artisan::call('vendor:publish', [
            '--provider' => 'Jeddsaliba\FilamentMessages\FilamentMessagesServiceProvider',
            '--tag' => 'filament-messages-migrations',
        ]);

        // Publish configuration
        Artisan::call('vendor:publish', [
            '--provider' => 'Jeddsaliba\FilamentMessages\FilamentMessagesServiceProvider',
            '--tag' => 'filament-messages-config',
        ]);

        $this->info('Assets published.');
    }

    protected function runMigrations()
    {
        $this->info('Running migrations...');
        Artisan::call('migrate');
        $this->info('Migrations completed.');
    }
}
