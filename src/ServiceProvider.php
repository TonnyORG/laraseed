<?php

namespace TonnyORG\LaraSeed;

use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * @var string
     */
    protected $publishesPrefix = 'laraseed';

    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommands();
        $this->registerMigrations();
        $this->registerPublishes();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laraseed.php', 'laraseed'
        );
    }

    /**
     * Register the package commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\Commands\UpdateAppRevisionNumber::class,
            ]);
        }
    }

    /**
     * Register the migrations path.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Register all the publishes instructions.
     *
     * @return void
     */
    protected function registerPublishes()
    {
        $this->publishes([
            __DIR__ . '/../config/laraseed.php' => config_path('laraseed.php')
        ], $this->publishesPrefix . '-config');

        $this->registerOptionalMigrationsPublishes();
    }

    /**
     * Register all the migrations related publishes.
     *
     * @return void
     */
    protected function registerOptionalMigrationsPublishes()
    {
        $localMigrationsPath = __DIR__ . '/../database/optional-migrations';

        $optionalMigrationGroups = [
            'administrators',
            'renaming',
            'soft-deletes',
        ];

        foreach ($optionalMigrationGroups as $group) {
            $this->publishes([
                $localMigrationsPath . '/' . $group . '/' => database_path('migrations/')
            ], $this->publishesPrefix . '-migration-' . $group);
        }
    }
}
