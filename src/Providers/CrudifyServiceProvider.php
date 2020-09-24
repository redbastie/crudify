<?php

namespace Redbastie\Crudify\Providers;

use Illuminate\Support\ServiceProvider;
use Redbastie\Crudify\Commands\CrudifyInstallCommand;
use Redbastie\Crudify\Commands\MakeCrudCommand;
use Redbastie\Crudify\Commands\MigrateAutoCommand;

class CrudifyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CrudifyInstallCommand::class,
                MakeCrudCommand::class,
                MigrateAutoCommand::class,
            ]);
        }

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'crudify');
    }
}
