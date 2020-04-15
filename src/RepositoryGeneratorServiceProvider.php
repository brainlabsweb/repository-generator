<?php

namespace Brainlabs\Generator;

use Illuminate\Support\ServiceProvider;
use Brainlabs\Generator\Console\Commands\CreateRepository;

class RepositoryGeneratorServiceProvider extends ServiceProvider
{
    /**
     * boot service provider
     */
    public function boot()
    {
        // publish repository files
        $this->publishes([
            __DIR__ . '/config/repository.php' => config_path('repository.php'),
        ], 'config');

        $this->loadCommands();
    }

    protected function loadCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateRepository::class,
            ]);
        }
    }

}