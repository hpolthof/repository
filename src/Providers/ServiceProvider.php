<?php

namespace Hpolthof\Laravel\Repository\Providers;

use Hpolthof\Laravel\Repository\Console\Command\RepositoryMakeCommand;
use Hpolthof\Laravel\Repository\Console\Command\ModelMakeCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider As BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->commands([
                RepositoryMakeCommand::class,
            ]);

            $this->app->extend('command.model.make', function () {
                return $this->app->make(ModelMakeCommand::class);
            });

        }
    }
}