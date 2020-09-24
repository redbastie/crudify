<?php

namespace Redbastie\Crudify\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MakeCrudCommand extends Command
{
    protected $signature = 'make:crud {model}';
    private $replace;

    public function handle()
    {
        $singular = Str::title(Str::snake($this->argument('model'), ' '));
        $plural = Str::plural($singular);
        $this->replace = [
            'dummy_table' => Str::snake($plural),
            'dummy-route' => Str::snake($plural, '-'),
            'DummySingular' => $singular,
            'DummyPlural' => $plural,
            'Dummy' => $this->argument('model'),
            'dummy' => Str::camel($this->argument('model')),
            '.stub' => '',
        ];
        $filesystem = new Filesystem;

        foreach ($filesystem->allFiles(__DIR__ . '/../../resources/stubs/make') as $stub) {
            if ($stub->getRelativePath()) {
                $filesystem->ensureDirectoryExists(base_path($this->replace($stub->getRelativePath())));
                $filesystem->put(base_path($this->replace($stub->getRelativePathname())), $this->replace($stub->getContents()));
            }
        }

        $navItem = $this->replace(rtrim($filesystem->get(__DIR__ . '/../../resources/stubs/make/nav-item.blade.php.stub')));
        $layout = $filesystem->get(resource_path('views/layouts/app.blade.php'));
        if (!Str::contains($layout, $navItem)) {
            $hook = '{{--nav-item hook--}}';
            $layout = str_replace($hook, $navItem . PHP_EOL . str_repeat(' ', 24) . $hook, $layout);
            $filesystem->put(resource_path('views/layouts/app.blade.php'), $layout);
        }

        $autoRoute = $this->replace(rtrim($filesystem->get(__DIR__ . '/../../resources/stubs/make/AutoRoute.stub')));
        $routes = rtrim($filesystem->get(base_path('routes/web.php')));
        if (!Str::contains($routes, $autoRoute)) {
            $routes = str_replace($routes, $routes . PHP_EOL . $autoRoute . PHP_EOL, $routes);
            $filesystem->put(base_path('routes/web.php'), $routes);
        }

        $this->info($this->argument('model') . ' CRUD made.');
        $this->warn("Don't forget to <info>migrate:auto</info> after configuring migration.");
    }

    private function replace($contents)
    {
        return str_replace(array_keys($this->replace), array_values($this->replace), $contents);
    }
}
