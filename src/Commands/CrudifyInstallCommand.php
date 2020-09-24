<?php

namespace Redbastie\Crudify\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CrudifyInstallCommand extends Command
{
    protected $signature = 'crudify:install';

    public function handle()
    {
        Artisan::call('ui bootstrap --auth', [], $this->getOutput());

        $filesystem = new Filesystem;

        foreach ($filesystem->allFiles(__DIR__ . '/../../resources/stubs/install') as $stub) {
            $path = base_path(Str::replaceLast('.stub', '', $stub->getRelativePathname()));
            $filesystem->put($path, $stub->getContents());
        }

        $appConfig = $filesystem->get(config_path('app.php'));
        $appVersion = "'version' => '1.0.0',";
        if (!Str::contains($appConfig, $appVersion)) {
            $appName = str_repeat(' ', 4) . "'name' => env('APP_NAME', 'Laravel'),";
            $appConfig = str_replace($appName, $appName . PHP_EOL . $appVersion, $appConfig);
            $filesystem->put(config_path('app.php'), $appConfig);
        }

        $packages = json_decode($filesystem->get(base_path('package.json')), true);
        $packages['devDependencies']['@fortawesome/fontawesome-free'] = '^5.14.0';
        $packages['devDependencies']['datatables.net-bs4'] = '^1.10.22';
        $packages['devDependencies']['datatables.net-responsive-bs4'] = '^2.2.6';
        $packages = json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        $filesystem->put(base_path('package.json'), $packages);

        exec('npm install && npm run dev');

        $this->info('Crudify installed.');
    }
}
