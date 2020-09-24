<?php

namespace Redbastie\Crudify\Commands;

use Doctrine\DBAL\Schema\Comparator;
use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use ReflectionClass;

class MigrateAutoCommand extends Command
{
    protected $signature = 'migrate:auto {--fresh} {--seed}';

    public function handle()
    {
        if ($this->option('fresh')) {
            Artisan::call('migrate:fresh --force');
        }
        else {
            Artisan::call('migrate --force');
        }

        foreach ((new Filesystem)->allFiles(app_path('Models')) as $modelFile) {
            $modelClass = str_replace([app_path(), '/', '.php'], ['App', '\\', ''], $modelFile->getPathname());
            $reflection = new ReflectionClass($modelClass);

            if ($reflection->hasMethod('migration')) {
                $model = app($reflection->name);

                if (Schema::hasTable($model->getTable())) {
                    $tempTable = 'temp_' . $model->getTable();

                    Schema::dropIfExists($tempTable);
                    Schema::create($tempTable, function (Blueprint $table) use ($model) {
                        $model->migration($table);
                    });

                    $schemaManager = $model->getConnection()->getDoctrineSchemaManager();
                    $modelTableDetails = $schemaManager->listTableDetails($model->getTable());
                    $tempTableDetails = $schemaManager->listTableDetails($tempTable);
                    $tableDiff = (new Comparator)->diffTable($modelTableDetails, $tempTableDetails);

                    if ($tableDiff) {
                        $schemaManager->alterTable($tableDiff);
                    }

                    Schema::drop($tempTable);
                }
                else {
                    Schema::create($model->getTable(), function (Blueprint $table) use ($model) {
                        $model->migration($table);
                    });
                }
            }
        }

        $this->info('Migration complete.');

        if ($this->option('seed')) {
            Artisan::call('db:seed');

            $this->info('Seeding complete.');
        }
    }
}
