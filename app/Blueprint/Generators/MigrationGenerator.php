<?php

namespace App\Blueprint\Generators;

use Carbon\Carbon;
use \Blueprint\Generators\MigrationGenerator as ParentMigrationGenerator;

class MigrationGenerator extends ParentMigrationGenerator
{

    protected function getTablePath($tableName, Carbon $timestamp, $overwrite = false)
    {
        $dir = config('blueprint.base_path') . '/' . 'database/migrations/';
        $name = '_create_' . $tableName . '_table.php';

        if ($overwrite) {
            $migrations = collect($this->filesystem->files($dir))
                ->filter(fn (SplFileInfo $file) => str_contains($file->getFilename(), $name))
                ->sort();

            if ($migrations->isNotEmpty()) {
                $migration = $migrations->first()->getPathname();

                $migrations->diff($migration)
                    ->each(function (SplFileInfo $file) {
                        $path = $file->getPathname();
                        $this->filesystem->delete($path);
                        $this->output['deleted'][] = $path;
                    });

                return $migration;
            }
        }

        return $dir . $timestamp->format('Y_m_d_His') . $name;
    }

}
